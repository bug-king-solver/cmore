<div class="p-2">
    <div class="flex justify-between">
        <span class="text-base font-bold text-esg6">{{ __('Add funds to the wallet') }}</span>
        <button type="button" wire:click="$emit('closeModal')" class="dark:hover:text-esg27 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-800">
            <svg class="text-esg8 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>

    <div class="mt-2.5 px-4">
        <div class="text-center font-bold">
            <p class="text-2xl">{{ __('Amount to deposit') }}</p>
            <p class="mt-2 text-esg5 text-6xl">{{ formatToCurrency($this->amount, false, 'EUR') }}</p>
        </div>

        <div class="mt-10">
            <div class="">
                <div class="flex items-center p-3 bg-esg7/60 gap-3 rounded-md">
                    @include('icons.card')
                    <span class="text-sm text-esg8">{{ __('Card') }}</span>
                </div>

                <div class="">
                    <p class="text-xs text-center font-bold p-4">
                        {{ __('C-MORE does not store any information about the cards and payment is processed through UNICRE.') }}
                    </p>

                    <p class="text-center p-4">
                        <x-buttons.a class="!text-base !p-2" target="_blank" href="{{ $paymentUrl }}" text="{{ __('Click here to proceed with the payment!') }}" />
                    </p>
                </div>
            </div>

            <div class="my-10 pt-3 border-t border-esg7/40 text-center">OR</div>

            <div class="">
                <div class="flex items-center p-3 bg-esg7/60 gap-3 rounded-md">
                    @include('icons.bank')
                    <span class="text-sm text-esg8">{{ __('Bank Transfer') }}</span>
                </div>

                <div class="">
                    <x-tables.table class="!min-w-[0px] mt-5">
                        <x-slot name="thead">
                            <x-tables.th colspan="2" class="!border-y-esg7 text-esg6 text-base pb-2 text-center">{{ __('Account Details') }}</x-tables.th>
                        </x-slot>
                        <x-tables.tr>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ __('Name') }}</x-tables.td>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ config('app.payments.account') }}</x-tables.td>
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ __('Use this reference') }}</x-tables.td>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ $reference }}</x-tables.td>
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ __('Bank code (BIC/SWIFT)') }}</x-tables.td>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ config('app.payments.bicswift') }}</x-tables.td>
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ __('IBAN') }}</x-tables.td>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ config('app.payments.iban') }}</x-tables.td>
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{{ __('Our bank address') }}</x-tables.td>
                            <x-tables.td class="!p-0 !py-4 !border-b-esg7/40">{!! nl2br(e(config('app.payments.address'))) !!}</x-tables.td>
                        </x-tables.tr>
                    </x-tables.table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 grid justify-center">
        <x-buttons.btn text="{{ __('OK') }}" class="!px-8 !py-2 !normal-case !bg-esg6 !rounded-md !text-sm !font-medium !border !border-esg6" wire:click="$emit('closeModal')">
        </x-buttons.btn>
    </div>
</div>
