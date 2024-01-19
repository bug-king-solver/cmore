<div class="mt-8">
    <h3 class="text-base font-medium text-esg29">{{ __('Billing address') }}</h3>

    <div class="bg-esg4 flex flex-row flex-wrap py-5">
        <div class="w-1/2 p-2">
            <label for="line1" class="block text-sm font-medium leading-5 text-esg29">{{ __('Line 1') }}
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <input id="line1" wire:model="line1" type="text" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg block w-full font-medium sm:leading-5" placeholder="123 Laravel Street">
            </div>
            @error('line1')
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>
        <div class="w-1/2 p-2">
            <label for="line2" class="block text-sm font-medium leading-5 text-esg29">{{ __('Line 2') }}
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <input id="line2" wire:model="line2" type="text" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md font-medium text-lg block w-full sm:leading-5" placeholder="Apartment B">
            </div>
            @error('line2')
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>
        <div class="mt-4 w-1/2 p-2">
            <label for="city" class="block text-sm font-medium leading-5 text-esg29">{{ __('City') }}
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <input id="city" wire:model="city" type="text" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md font-medium text-lg block w-full sm:leading-5" placeholder="San Francisco">
            </div>
            @error('city')
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>
        <div class="mt-4 w-1/2 p-2">
            <label for="postal_code" class="block text-sm font-medium leading-5 text-esg29">{{ __('Postal code') }}
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <input id="postal_code" wire:model="postal_code" type="text" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md font-medium text-lg block w-full sm:leading-5" placeholder="12345">
            </div>
            @error('postal_code')
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>
        <div class="mt-4 w-1/2 p-2">
            <label for="country" class="block text-sm font-medium leading-5 text-esg29">{{ __('Country') }}
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <select id="country" wire:model="country" type="text" class="form-select h-11 pl-3.5 text-esg8 placeholder-esg10 border border-esg10 rounded-md font-medium text-lg block w-full sm:leading-5">
                    @include('partials.countries')
                </select>
            </div>
            @error('country')
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>
        <div class="mt-4 w-1/2 p-2">
            <label for="state" class="block text-sm font-medium leading-5 text-esg29">{{ __('State') }}
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <input id="state" wire:model="state" type="text" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md font-medium text-lg block w-full sm:leading-5" placeholder="California">
            </div>
            @error('state')
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>
        @if($success)
        <p class="mt-4 text-sm text-green-500">
            {{ $success }}
        </p>
        @endif
    </div>

    <div class="flex justify-end px-4 py-2 sm:px-6">
        <button wire:click="save" class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out">
            {{ __('Save billing address') }}
        </button>
    </div>
</div>
