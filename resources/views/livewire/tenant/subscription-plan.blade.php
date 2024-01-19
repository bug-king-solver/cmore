<div class="mt-8" x-data="{ cancelModalOpen: false, cancelationReason: null, otherReason: '' }">
    <template x-if="cancelModalOpen">
        <div class="fixed inset-x-0 bottom-0 z-10 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
            <div class="fixed inset-0">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div @click.away="cancelModalOpen = false" class="bg-esg4 transform overflow-hidden rounded-lg shadow-xl sm:w-full sm:max-w-lg">
                <div class="bg-esg4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-headline">
                                {{ __('Cancel subscription') }}
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600">
                                {{ __('We are sad to see you go. In order to improve our services, we would appreciate you taking a few moments to tell us why this product wasn\'t suited for you.') }}
                                </p>
                                <select x-model="cancelationReason" class="form-select mt-3 w-full py-1">
                                    <option>{{ __('Cancelation reason') }}</option>
                                    @foreach(config('saas.cancelation_reasons') as $reason)
                                        <option>{{ $reason }}</option>
                                    @endforeach
                                    <option>{{ __('Other') }}</option>
                                </select>
                                <input x-show="cancelationReason == '{{ __('Other') }}'" x-model="otherReason" type="text" class="form-input mt-1 w-full" placeholder="{{ __('I\'m canceling my subscription because...') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-esg4 flex flex-col px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button @click="@this.call('cancel', cancelationReason === '{{ __('Other') }}' ? otherReason : cancelationReason); cancelModalOpen = false" type="button" class="text-esg27 focus:shadow-outline-esg6 w-full rounded-md border border-transparent bg-red-600 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out hover:bg-red-500 focus:bg-red-500 focus:outline-none active:bg-red-600">
                    {{ __('Cancel subscription') }}
                    </button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button @click="cancelModalOpen = false" type="button" class="bg-esg4 focus:shadow-outline-esg6 w-full items-center rounded-md border border-gray-300 py-1 px-4 text-sm font-medium transition duration-150 ease-in-out focus:border-blue-300 focus:outline-none active:bg-gray-50 active:text-gray-800">
                    {{ __('Close') }}
                    </button>
                </span>
                </div>
            </div>
        </div>
    </template>

</div>
