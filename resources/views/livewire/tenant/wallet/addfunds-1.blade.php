<div class="p-2">
    <div wire:loading wire:target="add">
        <x-loading background='bg-gray-500 opacity-50'>
            <div class="flex items-center">
                <div role="status">
                    @include('icons.loader')
                </div>
            </div>
        </x-loading>
    </div>
    <div class="flex justify-between">
        <span class="text-base font-bold text-esg6">{{ __('Add funds to the wallet') }}</span>
        <button type="button" wire:click="$emit('closeModal')" class="dark:hover:text-esg27 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-800">
            <svg class="text-esg8 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>

    <div class="mt-2.5 px-4" x-data="{
        balance: {{ tenant()->balanceFloat }},
        formattedBalance: '{{ formatToCurrency(tenant()->balanceFloat, false, 'EUR') }}',
        balanceAfterDeposit: '{{ formatToCurrency(tenant()->balanceFloat, false, 'EUR') }}',
        calculateBalanceAfterDeposit(event) {
            let val = event.target.value;
            val = isNaN(val) ? 0 : parseFloat(val);
            this.balanceAfterDeposit = parseFloat(this.balance) + val;
            this.balanceAfterDeposit = this.balanceAfterDeposit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    }">
        <div>
            <label class="text-sm text-esg8 after:content-['*'] after:text-red-500">{{ __('Value to deposit') }} </label>
            <div class="mt-2.5 flex items-center w-3/12">
                <x-inputs.text id="amount" x-on:change="calculateBalanceAfterDeposit($event)" modelmodifier=".defer" class="!border-esg7/40 border-r-0 !rounded-none !rounded-tl-md !rounded-bl-md" placeholder="{{ __('Amount') }}"/>
                <span class="text-sm text-black h-[46px] grid place-content-center px-4 border border-esg7/40 rounded-tr-md rounded-br-md bg-esg7/20"> € </span>
            </div>
            @error('amount')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="mt-5">
            <label class="text-sm text-esg8 after:content-['*'] after:text-red-500">{{ __('Description') }} </label>
                <x-inputs.text id="description" modelmodifier=".defer" />
            @error('description')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="my-10 border-t border-esg7/40"></div>

        <div class="">
            <div class="flex items-center gap-3 rounded-md">
                <span class="text-sm text-esg16">{{ __('Current balance:') }}</span>
                <span class="text-lg text-esg16 font-bold" x-text="formattedBalance"></span>
            </div>

            <div class="flex items-center gap-3 rounded-md">
                <span class="text-sm text-esg16">{{ __('Balance after payment: ') }}</span>
                <span class="text-lg text-esg16 font-bold" x-text="balanceAfterDeposit"></span>
            </div>
        </div>
    </div>

    <div class="mt-10 grid justify-end">
        <div class="flex items-center gap-4">
            <x-buttons.btn text="{{ __('Cancel') }}" class="!px-8 !py-2 !normal-case !bg-esg4 !text-esg16 !border !border-esg16  !rounded-md !text-sm !font-medium" wire:click="$emit('closeModal')">
            </x-buttons.btn>

            <x-buttons.btn text="{{ __('Add') }}" class="!px-8 !py-2 !normal-case !bg-esg6 !rounded-md !text-sm !font-medium !border !border-esg6" wire:click="add()">
            </x-buttons.btn>
        </div>
    </div>
</div>
