@if ($product && tenant()->has_wallet_feature && empty($exists))
    <div class="mt-10 rounded-md h-auto py-4 px-6 relative shadow-md shadow-esg7/40">
        <p class="">{{ __('This will cost you') }} <span
                class="text-5xl font-bold text-esg6">{{ formatToCurrency($product->price, false, 'EUR') }}</span></p>
        <p class="">{{ __('Balance after the purchase') }}:
            {{ formatToCurrency(tenant()->balanceFloat - $product->price, false, 'EUR') }}</p>

        @if (tenant()->balanceFloat - $product->price < tenant()->walletLimitCredit)
            <p class="text-red-500">
                {{ __('With this purchase, your balance will be below the allowed credit.') }}<br>
                <span
                    class="font-bold">{{ __('In this case, access will be blocked until you add funds to your wallet.') }}</span>
            </p>
        @endif
    </div>
@endif
