<div class="px-4 md:px-0 reporting-periods">

    <x-slot name="header">
        <x-header title="{{ __('Reporting Period') }}" data-test="data-header"
            click="{{ route('tenant.reporting-periods.index') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="w-full">
        <x-form.form title="{{ $resource->exists ? __('Edit') : __('Create') }}">

            <x-form.form-col input="text" id="name" label="{{ __('Name') }}"
                class="after:content-['*'] after:text-red-500" readonly />

            <x-form.form-col input="text" id="custom_name" label="{{ __('Custom name') }}"
                class="after:content-['*'] after:text-red-500" />

            <x-form.form-col input="checkbox" id="enabled_questionnaires_filter" label="{!! __('Enabled questionnaire filter') !!}"
                flex="true" form_div_size="!-mt-2" class="mt-5" />

            <x-form.form-col input="checkbox" id="enabled_questionnaires_reporting" label="{!! __('Enabled questionnaire reporting') !!}"
                flex="true" form_div_size="!-mt-2" class="mt-5" />

            <x-form.form-col input="checkbox" id="enabled_monitoring_filter" label="{!! __('Enabled monitoring filter') !!}"
                flex="true" form_div_size="!-mt-2" class="mt-5" />

            <x-form.form-col input="checkbox" id="enabled_monitoring_reporting" label="{!! __('Enabled monitoring reporting') !!}"
                flex="true" form_div_size="!-mt-2" class="mt-5" />


        </x-form.form>
    </div>


</div>
