<div class="mt-8">
    <h3 class="text-base font-medium text-esg29">Change fallback subdomain</h3>
    <div class="mt-2 overflow-hidden shadow sm:rounded-md">
        <div class="bg-esg4 px-4 py-5 sm:p-6">
            <div>
                <label for="fallback_domain" class="block text-sm font-medium leading-5 text-gray-700">{{ __('Domain') }}
                </label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input id="fallback_domain" wire:model="domain" class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 text-lg block w-full flex-1 rounded-none rounded-l-md transition duration-150 ease-in-out text-lg sm:leading-5" />
                    <span class="flex items-center rounded-r-md border-t border-b border-r border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                        <span>
                            .{{ config('tenancy.central_domains')[0] }}
                        </span>
                    </span>
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
