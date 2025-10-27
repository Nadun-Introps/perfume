{!! view_render_event('bagisto.shop.checkout.onepage.stripe_payment.before') !!}

<v-stripe-payment @processing="stepForward" @processed="stepProcessed">
    <x-shop::shimmer.checkout.onepage.payment-method />
</v-stripe-payment>

{!! view_render_event('bagisto.shop.checkout.onepage.stripe_payment.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-stripe-payment-template"
    >
        <div class="mb-7 max-md:last:!mb-0">
            <template v-if="! loaded">
                <!-- Payment Method shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.payment-method />
            </template>

            <template v-else>
                {!! view_render_event('bagisto.shop.checkout.onepage.stripe_payment.accordion.before') !!}

                <!-- Accordion Blade Component -->
                <x-shop::accordion class="overflow-hidden !border-b-0 max-md:rounded-lg max-md:!border-none max-md:!bg-gray-100">
                    <!-- Accordion Blade Component Header -->
                    <x-slot:header class="px-0 py-4 max-md:p-3 max-md:text-sm max-md:font-medium max-sm:p-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-medium max-md:text-base">
                                @lang('shop::app.checkout.onepage.payment.payment-method')
                            </h2>
                        </div>
                    </x-slot>

                    <!-- Accordion Blade Component Content -->
                    <x-slot:content class="mt-8 !p-0 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
                        <!-- Stripe Payment Element -->
                        <div class="mb-4">
                            <div id="stripe-payment-element" class="stripe-payment-element"></div>
                        </div>

                        <!-- Stripe Payment Messages -->
                        <div id="stripe-payment-message" class="stripe-payment-message hidden text-red-600 mb-4"></div>

                        <!-- Pay Button -->
                        <div class="flex justify-end">
                            <x-shop::button
                                type="button"
                                class="primary-button w-max rounded-2xl bg-navyBlue px-11 py-3 max-md:mb-4 max-md:w-full max-md:max-w-full max-md:rounded-lg max-sm:py-1.5"
                                :title="trans('shop::app.checkout.onepage.summary.place-order')"
                                ::disabled="isProcessing || !stripeInitialized"
                                ::loading="isProcessing"
                                @click="processPayment"
                            />
                        </div>
                    </x-slot>
                </x-shop::accordion>

                {!! view_render_event('bagisto.shop.checkout.onepage.stripe_payment.accordion.after') !!}
            </template>
        </div>
    </script>

    <!-- Load Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>

    <script type="module">
        app.component('v-stripe-payment', {
            template: '#v-stripe-payment-template',

            data() {
                return {
                    loaded: false,
                    isProcessing: false,
                    stripeInitialized: false,
                    stripe: null,
                    elements: null,
                    paymentElement: null,
                    clientSecret: null,
                    paymentIntentId: null,
                    observer: null
                }
            },

            mounted() {
                // Wait for the component to be fully rendered
                this.$nextTick(() => {
                    this.loaded = true;

                    // Use MutationObserver to detect when the payment section becomes visible
                    this.setupObserver();
                });
            },

            beforeUnmount() {
                // Clean up observer
                if (this.observer) {
                    this.observer.disconnect();
                }
            },

            methods: {
                setupObserver() {
                    const targetNode = this.$el;

                    this.observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.type === 'attributes' && mutation.attributeName ===
                                'class') {
                                this.checkVisibility();
                            }
                        });
                    });

                    // Start observing
                    this.observer.observe(targetNode, {
                        attributes: true,
                        attributeFilter: ['class']
                    });

                    // Initial check
                    this.checkVisibility();
                },

                checkVisibility() {
                    const element = this.$el;
                    const isVisible = element.offsetParent !== null &&
                        element.offsetWidth > 0 &&
                        element.offsetHeight > 0;

                    if (isVisible && !this.stripeInitialized) {
                        this.initializeStripe();
                    }
                },

                async initializeStripe() {
                    try {
                        // Get Stripe configuration from backend
                        const response = await this.$axios.post("{{ route('shop.stripe.process') }}");

                        if (response.data.success) {
                            this.clientSecret = response.data.client_secret;
                            this.paymentIntentId = response.data.payment_intent_id;

                            // Initialize Stripe
                            this.stripe = Stripe(response.data.publishable_key);

                            // Initialize Elements
                            this.elements = this.stripe.elements({
                                clientSecret: this.clientSecret,
                                appearance: {
                                    theme: 'stripe',
                                    variables: {
                                        colorPrimary: '#1f2937', // navyBlue color
                                        borderRadius: '8px'
                                    }
                                }
                            });

                            // Wait for the DOM element to be available
                            await this.waitForElement('#stripe-payment-element');

                            // Create and mount the Payment Element
                            this.paymentElement = this.elements.create('payment', {
                                layout: {
                                    type: 'tabs',
                                    defaultCollapsed: false
                                }
                            });

                            this.paymentElement.mount('#stripe-payment-element');

                            this.stripeInitialized = true;

                            // Hide the shimmer
                            this.loaded = true;

                        } else {
                            this.showMessage(response.data.message);
                        }
                    } catch (error) {
                        console.error('Error initializing Stripe:', error);
                        this.showMessage(
                            'Failed to initialize payment system. Please refresh the page and try again.');
                    }
                },

                waitForElement(selector, timeout = 5000) {
                    return new Promise((resolve, reject) => {
                        const element = document.querySelector(selector);

                        if (element) {
                            resolve(element);
                            return;
                        }

                        const observer = new MutationObserver((mutations) => {
                            const element = document.querySelector(selector);
                            if (element) {
                                observer.disconnect();
                                resolve(element);
                            }
                        });

                        observer.observe(document.body, {
                            childList: true,
                            subtree: true
                        });

                        setTimeout(() => {
                            observer.disconnect();
                            reject(new Error(`Element ${selector} not found within ${timeout}ms`));
                        }, timeout);
                    });
                },

                async processPayment() {
                    if (!this.stripe || !this.elements) {
                        this.showMessage('Payment system not ready. Please wait...');
                        return;
                    }

                    this.isProcessing = true;
                    this.$emit('processing', 'review');

                    try {
                        const {
                            error
                        } = await this.stripe.confirmPayment({
                            elements: this.elements,
                            confirmParams: {
                                return_url: `${window.location.origin}/stripe/return`,
                            },
                            redirect: 'if_required'
                        });

                        if (error) {
                            this.showMessage(error.message);
                            this.isProcessing = false;
                            this.$emit('processing', 'payment');
                            return;
                        }

                        // If no redirect happened, confirm payment with backend
                        await this.confirmPayment();

                    } catch (error) {
                        console.error('Payment error:', error);
                        this.showMessage('Payment failed. Please try again.');
                        this.isProcessing = false;
                        this.$emit('processing', 'payment');
                    }
                },

                async confirmPayment() {
                    try {
                        const response = await this.$axios.post("{{ route('shop.stripe.confirm') }}", {
                            payment_intent_id: this.paymentIntentId
                        });

                        if (response.data.success) {
                            // Set order_id in session for success page
                            sessionStorage.setItem('order_id', response.data.order_id);

                            // Redirect to success page
                            window.location.href = response.data.redirect_url;
                        } else {
                            this.showMessage(response.data.message);
                            this.isProcessing = false;
                            this.$emit('processing', 'payment');
                        }
                    } catch (error) {
                        console.error('Confirmation error:', error);

                        // Show specific error message
                        if (error.response && error.response.data && error.response.data.message) {
                            this.showMessage(error.response.data.message);
                        } else {
                            this.showMessage('Payment confirmation failed. Please try again.');
                        }

                        this.isProcessing = false;
                        this.$emit('processing', 'payment');
                    }
                },

                showMessage(message) {
                    const messageElement = document.getElementById('stripe-payment-message');
                    if (messageElement) {
                        messageElement.textContent = message;
                        messageElement.classList.remove('hidden');

                        setTimeout(() => {
                            if (messageElement) {
                                messageElement.classList.add('hidden');
                            }
                        }, 5000);
                    } else {
                        console.error('Message element not found:', message);
                    }
                }
            },
        });
    </script>

    <style>
        .stripe-payment-element {
            min-height: 100px;
        }

        .stripe-payment-message {
            padding: 12px;
            border-radius: 8px;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        /* Custom Stripe element styles */
        .StripeElement {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: white;
        }

        .StripeElement--focus {
            border-color: #1f2937;
            box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
        }
    </style>
@endPushOnce
