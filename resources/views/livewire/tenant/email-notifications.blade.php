<div class="w-full">
    <div class="flex justify-between">
        <div class="">
            <label class="text-base font-semibold text-esg5 ">{{ __('Notifications') }}</label>
            <p class="text-xs text-esg8 mt-2.5">
                {{ __('Settings for general notifications related to your application.') }}</p>
        </div>
        <div class="">
            <button type="button" wire:click="update"
                class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm">
                {{ __('Save') }}
            </button>
        </div>
    </div>

    <div class="">
        <div class="bg-esg4 w-6/12 mt-6">
            <div>
                <label for="to" class="block text-sm font-medium leading-5 text-gray-700">{{ __('TO') }}
                </label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <x-inputs.text wire:model.defer="to" name="to" id="to"
                        value="{{ old('to', tenant('to')) }}" placeholder="{{ __('TO') }}" />
                </div>
            </div>

            @error('to')
                <p class="mt-4 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="bg-esg4 w-6/12 mt-6">
            <div>
                <label for="cc" class="block text-sm font-medium leading-5 text-gray-700">{{ __('CC') }}
                </label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <x-inputs.text wire:model.defer="cc" name="cc" id="cc"
                        value="{{ old('cc', tenant('cc')) }}" placeholder="{{ __('cc') }}" />
                </div>
            </div>

            @error('cc')
                <p class="mt-4 text-xs text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        @if ($success)
            <p class="mt-4 text-sm text-green-500">
                {{ $success }}
            </p>
        @endif
    </div>
</div>
