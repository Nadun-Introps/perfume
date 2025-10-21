<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shop\Http\Controllers\Controller;

class StripeController extends Controller
{
    /**
     * OrderRepository instance
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Process Stripe payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        try {
            $cart = Cart::getCart();

            if (! $cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ]);
            }

            // Create payment intent
            $stripe = new \Webkul\Payment\Payment\Stripe();
            $paymentIntent = $stripe->createPaymentIntent();

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'publishable_key' => $stripe->getPublishableKey(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle Stripe payment confirmation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request)
    {
        try {
            $paymentIntentId = $request->input('payment_intent_id');

            $stripe = new \Webkul\Payment\Payment\Stripe();
            $paymentIntent = $stripe->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                // Set payment method to stripe
                Cart::setPaymentMethod('stripe');
                Cart::save();

                // Place the order
                $order = $this->orderRepository->create(Cart::prepareDataForOrder());

                Cart::deActivateCart();

                session()->flash('order', $order);

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect_url' => route('shop.checkout.onepage.success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed. Please try again.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle Stripe return URL (for 3D Secure)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleReturn(Request $request)
    {
        try {
            $paymentIntentId = $request->input('payment_intent');

            if ($paymentIntentId) {
                $stripe = new \Webkul\Payment\Payment\Stripe();
                $paymentIntent = $stripe->retrievePaymentIntent($paymentIntentId);

                if ($paymentIntent->status === 'succeeded') {
                    // Set payment method to stripe
                    Cart::setPaymentMethod('stripe');
                    Cart::save();

                    // Place the order
                    $order = $this->orderRepository->create(Cart::prepareDataForOrder());

                    Cart::deActivateCart();

                    session()->flash('order', $order);

                    return redirect()->route('shop.checkout.onepage.success');
                }
            }

            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Handle Stripe webhooks
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = Config::get('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    // Handle successful payment
                    break;
                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    // Handle failed payment
                    break;
                // ... handle other event types
                default:
                    return response()->json(['status' => 'unhandled_event']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
