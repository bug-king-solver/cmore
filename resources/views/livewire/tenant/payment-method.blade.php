<div class="mt-8">
    <h3 class="text-lg font-medium text-esg29">{{ __('Change payment method') }}</h3>
    <div class="mt-2 overflow-hidden shadow sm:rounded-md">
        <div class="bg-esg4 px-4 py-5 sm:p-6">
            <div>
                <h4 class="font-medium text-esg29">{{ __('Current payment method') }}</h4>
                @if(tenant()->hasDefaultPaymentMethod())
                    <p class="mt-2 text-sm text-gray-600">
                        {{ ucfirst(tenant()->defaultPaymentMethod()->asStripePaymentMethod()->card->brand) }} ending in
                        {{ tenant()->defaultPaymentMethod()->asStripePaymentMethod()->card->last4 }}
                    </p>
                @else
                    <p class="mt-2 text-sm text-esg16">
                        {{ __('No payment method set yet. Please add one below.') }}
                    </p>
                @endif
            </div>
            <div class="hidden sm:block">
                <div class="py-4">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>
            <label for="card-holder-name" class="block text-sm font-medium leading-5 text-esg29">{{ __('Card holder name')   }}
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <input id="card-holder-name" type="text" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 block w-full rounded-md text-lg sm:leading-5" placeholder="Luis Coutinho">
            </div>

            <!-- Stripe Elements Placeholder -->
            <div class="relative mt-2 rounded-md shadow-sm" wire:ignore>
                <div id="card-element" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg block w-full pt-3 sm:leading-5"></div>
            </div>
            <p id="payment-method-message" class="text-sm"></p>
        </div>
        <div class="flex justify-end px-4 pb-4 pt-0 sm:px-6">
            <button id="card-button" data-secret="{{ $intent->client_secret }}" class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out">
                {{ __('Update payment method') }}
            </button>
        </div>
    </div>
</div>

@push('body')
<script nonce="{{ csp_nonce() }}">
    const stripe = Stripe('{{ config('saas.stripe_key') }}');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    function paymentMethodMessage(message, success) {
        document.getElementById('payment-method-message').innerHTML = message;
        document.getElementById('payment-method-message').classList = 'text-sm mt-4 ' + (success ? 'text-green-500' : 'text-red-500');
    }

    cardButton.addEventListener('click', async (e) => {
        const { setupIntent, error } = await stripe.confirmCardSetup(
        clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: { name: cardHolderName.value }
            }
        }
        );

        if (error) {
            paymentMethodMessage(error.message, false);
        } else {
            @this.set('paymentMethod', setupIntent.payment_method);
            @this.call('save');

            paymentMethodMessage('{{ __('Payment method updated successfuly.') }}', true);
        }
    });
</script>
@endpush
