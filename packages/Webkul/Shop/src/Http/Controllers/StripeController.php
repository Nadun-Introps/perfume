<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
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
        DB::beginTransaction();

        try {
            $paymentIntentId = $request->input('payment_intent_id');
            $cart = Cart::getCart();

            if (! $cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ]);
            }

            $stripe = new \Webkul\Payment\Payment\Stripe();
            $paymentIntent = $stripe->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                // Save Stripe payment method to cart
                $this->saveStripePaymentToCart($cart, $paymentIntent);

                // Use Bagisto's native order creation
                $order = $this->createOrderUsingBagisto($cart, $paymentIntent);

                if ($order) {
                    DB::commit();

                    // Deactivate cart
                    Cart::deActivateCart();

                    // Set the session variables that Bagisto expects
                    session()->put('order_id', $order->id);
                    session()->flash('order', $order);

                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'redirect_url' => route('shop.checkout.onepage.success')
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create order'
                    ]);
                }
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed. Please try again.'
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Save Stripe payment method to cart
     *
     * @param  \Webkul\Checkout\Models\Cart  $cart
     * @param  \Stripe\PaymentIntent  $paymentIntent
     * @return void
     */
    protected function saveStripePaymentToCart($cart, $paymentIntent)
    {
        // First, remove any existing payment method
        $existingPayment = \Webkul\Checkout\Models\CartPayment::where('cart_id', $cart->id)->first();
        if ($existingPayment) {
            $existingPayment->delete();
        }

        // Create new payment method entry
        $cartPayment = new \Webkul\Checkout\Models\CartPayment();
        $cartPayment->cart_id = $cart->id;
        $cartPayment->method = 'stripe';
        $cartPayment->method_title = 'Stripe';

        // Store additional payment information
        $cartPayment->additional = json_encode([
            'payment_intent_id' => $paymentIntent->id,
            'checkout_method' => $paymentIntent->checkout_method,
            'amount_received' => $paymentIntent->amount_received,
            'currency' => $paymentIntent->currency,
            'status' => $paymentIntent->status,
        ]);

        $cartPayment->save();

        // Update cart's payment method (this is the correct way in Bagisto)
        $cart->checkout_method = 'stripe';
        $cart->save();

        // Refresh cart totals
        Cart::collectTotals();
    }

    /**
     * Create order using Bagisto's native flow
     *
     * @param  \Webkul\Checkout\Models\Cart  $cart
     * @param  \Stripe\PaymentIntent  $paymentIntent
     * @return \Webkul\Sales\Models\Order|null
     */
    protected function createOrderUsingBagisto($cart, $paymentIntent)
    {
        try {
            // Prepare order data following Bagisto's structure
            $orderData = (new \Webkul\Sales\Transformers\OrderResource($cart))->jsonSerialize();

            // Add Stripe payment information
            $orderData['payment'] = [
                'method' => 'stripe',
                'method_title' => 'Stripe',
                'additional' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'checkout_method' => $paymentIntent->checkout_method,
                    'amount_received' => $paymentIntent->amount_received,
                    'currency' => $paymentIntent->currency,
                    'status' => $paymentIntent->status,
                ]
            ];

            // Create order using Bagisto's repository
            $order = $this->orderRepository->create($orderData);

            // Update order payment with Stripe details
            if ($order->payment) {
                $order->payment->additional = json_encode([
                    'payment_intent_id' => $paymentIntent->id,
                    'checkout_method' => $paymentIntent->checkout_method,
                    'amount_received' => $paymentIntent->amount_received,
                    'currency' => $paymentIntent->currency,
                    'status' => $paymentIntent->status,
                ]);
                $order->payment->save();
            }

            // Fire order events
            Event::dispatch('checkout.order.save.after', $order);

            return $order;

        } catch (\Exception $e) {
            throw new \Exception('Order creation failed: ' . $e->getMessage());
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
        DB::beginTransaction();

        try {
            $paymentIntentId = $request->input('payment_intent');

            if ($paymentIntentId) {
                $stripe = new \Webkul\Payment\Payment\Stripe();
                $paymentIntent = $stripe->retrievePaymentIntent($paymentIntentId);

                if ($paymentIntent->status === 'succeeded') {
                    $cart = Cart::getCart();

                    if ($cart) {
                        // Save payment method to cart
                        $this->saveStripePaymentToCart($cart, $paymentIntent);

                        // Create order using Bagisto's method
                        $order = $this->createOrderUsingBagisto($cart, $paymentIntent);

                        if ($order) {
                            DB::commit();
                            Cart::deActivateCart();

                            // Set session variables properly
                            session()->put('order_id', $order->id);
                            session()->flash('order', $order);

                            return redirect()->route('shop.checkout.onepage.success');
                        }
                    }
                }
            }

            DB::rollBack();
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            DB::rollBack();
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
                    // You can update order status here if needed
                    break;
                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    // Handle failed payment
                    break;
                default:
                    return response()->json(['status' => 'unhandled_event']);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
