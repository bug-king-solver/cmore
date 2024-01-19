<div class="mt-8">
    <h3 class="text-base font-medium text-esg29 md:px-0">Add a custom domain</h3>
    <div class="mt-2">
        <div class="overflow-hidden shadow sm:rounded-md">
            <div class="bg-esg4 px-4 py-5 sm:p-6">
                <div>
                    <label for="domain" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Domain') }}
                    </label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <input id="domain" autocomplete="off" wire:model="domain" value="" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg block w-full sm:leading-5" placeholder="mydomain.com" />
                    </div>
                </div>

                @error('domain')
                <p class="mt-4 text-xs text-red-500">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="flex justify-end px-4 pb-4 pt-0 sm:px-6">
                <button type="button" wire:click="save" class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out">
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
