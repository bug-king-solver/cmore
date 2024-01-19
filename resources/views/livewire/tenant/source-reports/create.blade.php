<div>
    <x-slot name="header">
        <x-header title="{{ __('Reports') }}" data-test="reports-header" click="{{ route('tenant.exports.index') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
            </x-slot>
        </x-header>
    </x-slot>


    <x-form.form title="{{ __('Create a new report') }}" class="text-esg5 mb-5"
        cancel="{{ route('tenant.exports.index') }}" showButtons="true">

        <x-form.form-col input="tomselect" id="source_id" label="{{ __('Select the framework') }}" :options="$frameworkList"
            :items="$source_id ?: []" limit="1" placeholder="{{ __('Select the Framework you wish to report') }}"
            class="after:content-['*'] after:text-red-500" form_div_size="w-full" modelmodifier=".lazy"
            fieldClass="!border-0 !bg-esg7/10 !text-esg8" />

        <x-form.form-col input="tomselect" id="company_id" label="{{ __('Company') }}" :options="$companiesList" limit="1"
            :items="$company_id ?: []" placeholder="{{ __('Select the company') }}"
            class="after:content-['*'] after:text-red-500" dataTest="questionnaire-company" form_div_size="w-full"
            fieldClass="!border-0 !bg-esg7/10 !text-esg8" modelmodifier=".lazy" />

        <x-form.form-col input="tomselect" id="reporting_period_id" label="{{ __('Reporting Period') }}"
            :options="$reportingPeriodList" plugins="['no_backspace_delete', 'remove_button']"
            :items="$reporting_period_id ?: []"
            placeholder="{{ __('Select the reporting period') }}" limit=1 dataTest="reporting_period_id-country"
            form_div_size="w-full" />

        <x-form.form-col input="tomselect" id="taggableIds" label="{{ __('Tags') }}" :options="$taggableList"
            :items="$taggableIds ?: []" plugins="['no_backspace_delete', 'remove_button']" placeholder="{{ __('Select tags') }}"
            dataTest="questionnaire-tags" form_div_size="w-full" fieldClass="!border-0 !bg-esg7/10 !text-esg8" />

        @error('source_id')
            <x-form.form-row input="checkbox-inline" id="force"
                label="{{ __('You can only have one export for each framework, if you proceed this will replace your current export') }}"
                class="!inline !font-normal" dataTest="framework-force" form_div_size="w-full"
                fieldClass="!bg-esg7/10 h-12 !text-esg8" />
        @enderror
    </x-form.form>
</div>
