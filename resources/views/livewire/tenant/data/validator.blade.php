<div class="px-4 md:px-0 validator">
    <x-slot name="header">
        <x-header title="{{ __('Data') }}" data-test="data-header">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="w-full">
        <div class="w-full mb-6">
            <a href="{{ route('tenant.data.indicators.show', ['indicator' => $indicator->id]) }}"
                class="text-esg5 w-fit text-lg font-bold flex flex-row gap-2 items-center">
                @include('icons.back', [
                    'color' => color(5),
                    'width' => '20',
                    'height' => '16',
                ])
                {{ __('Edit:') }} {{ $indicator->name }} {{ __('For') }} {{ $company->name }}
            </a>
        </div>

        <x-form.form class="text-esg5 mb-5"
            cancel="{{ route('tenant.data.indicators.show', ['indicator' => $indicator->id]) }}">

            <div class="flex items-center">
                <input type="checkbox" id="validaterequire" name="validaterequire" wire:model="validaterequire"
                    class="text-esg5">
                <label for="auditor_require" class="ml-2 text-lg">{{ __('Require validation to update') }}</label>
            </div>

            <div class="{{ $validaterequire ? '' : 'hidden' }}">
                <x-form.form-col input="tomselect" id="user" label="{{ __('Validator') }}" :options="$userList"
                    :items="$user" plugins="['remove_button', 'checkbox_options']"
                    placeholder="{{ __('Select the users') }}" dataTest="validator-user" form_div_size="w-full"
                    class="after:content-['*'] after:text-red-500" />

                <div class="flex items-center mt-3">
                    <input type="checkbox" id="auditor_require" name="auditor_require" wire:model="auditor_require"
                        class="text-esg5">
                    <label for="auditor_require"
                        class="ml-2 text-lg">{{ __('Require auditor process to update') }}</label>
                </div>

                <div class="{{ $auditor_require ? '' : 'hidden' }}">
                    <x-form.form-col input="tomselect" id="audit_user" label="{{ __('Auditors') }}" :options="$auditorList"
                        :items="$audit_user" plugins="['remove_button', 'checkbox_options']"
                        placeholder="{{ __('Select the auditors') }}" dataTest="auditor-user" form_div_size="w-full"
                        class="after:content-['*'] after:text-red-500" />
                </div>

                <x-form.form-col input="select" id="type" label="{{ __('Type of update value') }}"
                    class="after:content-['*'] after:text-red-500" :extra="['options' => $typeList]" form_div_size="w-full"
                    fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />

                <x-form.form-col input="select" id="frequency" label="{{ __('Frequency of report') }}"
                    class="after:content-['*'] after:text-red-500" :extra="['options' => $frequencyList]" form_div_size="w-full"
                    fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8" />
            </div>
        </x-form.form>
    </div>
</div>
