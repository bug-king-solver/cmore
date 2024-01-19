<div class="px-4 md:px-0">
    <x-slot name="header">
        <x-header title="{{ __('Data') }}" data-test="data-header"
            click="{{ route('tenant.data.indicators.show', ['indicator' => $indicator]) }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>
    <div class="w-full">
        <x-form.form title="{{ $data->exists ? __('Edit') : __('Create a new Data') }}" class="text-esg5 mb-5"
            cancel="{{ route('tenant.data.indicators.show', ['indicator' => $indicator]) }}">

            <x-form.form-col input="select" id="user" label="{{ __('User') }}"
                class="after:content-['*'] after:text-red-500" :extra="['options' => $userList]" form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

            <x-form.form-col input="number" id="value" label="{{ __('Value') }}"
                class="after:content-['*'] after:text-red-500" form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

            <div class="text-xs text-gray-400 mt-2 mr-7 @error('value') hidden @enderror">
                <p> {{ __('This field only accepts numbers and a dot as decimal separator') }}
                <p>
            </div>

            <x-form.form-col input="text" id="origin" name="origin" label="{{ __('Data Origin') }}"
                class="after:content-['*'] after:text-red-500" form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

            <x-form.form-col input="tomselect" id="reporting_period_id" label="{{ __('Reporting Period') }}"
                :options="$reportingPeriodList" plugins="['no_backspace_delete', 'remove_button']"
                placeholder="{{ __('Select the reporting period') }}" limit=1 dataTest="reporting_period_id-country"
                form_div_size="w-full" />

            <div class="relative flex flex-col mt-12">
                <label for="dropzone-file">
                    <div
                        class="bg-esg7/10 border-esg7 relative flex cursor-pointer flex-col rounded border border-dashed">
                        <div class="text-esg13 flex flex-col items-center justify-center p-10 text-center">
                            <p class="font-medium text-sm text-esg7"> {{ __('Drag and drop or') }} <span
                                    class="text-esg5"
                                    x-on:click="document.getElementById('dropzone-file').click();">{{ __('Choose file') }}</span>
                                {{ __('to upload') }} </p>
                            <input id="dropzone-file" type="file" wire:model.lazy="upload" multiple class="hidden" />
                            <div wire:loading>
                                <div class="flex items-center mt-4">
                                    <div role="status">
                                        @include('icons.loader')
                                    </div>
                                    {{ __('Please wait') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </x-form.form>
    </div>
</div>
