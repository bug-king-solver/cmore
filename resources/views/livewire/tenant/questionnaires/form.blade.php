<div class="px-4 md:px-0 questionnaire">
    <x-slot name="header">
        <x-header title="{{ __('Questionnaire') }}" data-test="questionnaires-header"
            click="{{ route('tenant.questionnaires.panel') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>
    <div class="w-full">
        <x-form.form
            title="{{ $questionnaire->exists ? __('Edit :name', ['name' => $questionnaire->company->name]) : __('Create a new questionnaire') }}"
            class="text-esg5 mb-5" cancel="{{ route('tenant.questionnaires.panel') }}">

            @if (!$questionnaire->exists)
                <x-form.form-col input="tomselect" id="company"
                    label="{{ __('Company') }}"
                    items="{{ $company }}"
                    :options="$companiesList"
                    limit="1"
                    placeholder="{{ __('Select the company') }}"
                    dataTest="questionnaire-company"
                    modelmodifier=".lazy"
                    form_div_size="w-full" />

                <x-form.form-col input="select" id="type"
                    label="{{ __('Questionnaire') }}"
                    :extra="['options' => $typesList]"
                    placeholder="{{ __('Select the questionnaire') }}"
                    dataTest="questionnaire-type"
                    fieldClass="!border-0 !bg-esg7/10 h-12 !text-esg8"
                    form_div_size="w-full"/>
            @endif

            <x-form.form-col input="tomselect" id="countries" label="{{ __('Country') }}" :options="$countriesList"
                plugins="['no_backspace_delete', 'remove_button']"
                items="{{ isset($countries) ? implode(',', $countries) : '' }}"
                placeholder="{{ __('Select the countries') }}" max_options=30 dataTest="questionnaire-country"
                form_div_size="w-full" />

            <x-form.form-col input="tomselect" id="reporting_period_id" label="{{ __('Reporting Period') }}" :options="$reportingPeriodList"
                plugins="['no_backspace_delete', 'remove_button']"
                placeholder="{{ __('Select the reporting period') }}" limit=1 dataTest="reporting_period_id-country"
                form_div_size="w-full" />

            @if ($isOwner && $questionnaire->exists)
                <x-form.form-col input="tomselect" id="createdByUserId" label="{{ __('Owner') }}" :options="$ownerUserList"
                    :items="$createdByUserId" plugins="['remove_button']" placeholder="{{ __('Select the owner') }}"
                    limit="1" dataTest="questionnaire-owner" form_div_size="w-full" />
            @endif

            <x-form.form-col input="tomselect" id="userablesId" label="{{ __('Users') }}" :options="$userablesList"
                plugins="['remove_button', 'checkbox_options']" placeholder="{{ __('Select the users') }}"
                :items="$userablesId" :wire_ignore="false" dataTest="questionnaire-user" form_div_size="w-full" />

            @if (tenant()->hasTagsFeature)
                <x-form.form-col input="tomselect" id="taggableIds" label="{{ __('Tags') }}" :options="$taggableList"
                    :items="$taggableIds ?: []" plugins="['no_backspace_delete', 'remove_button']"
                    placeholder="{{ __('Select tags') }}" dataTest="questionnaire-tags" form_div_size="w-full" />
            @endif

            @include('livewire.tenant.wallet.payable', ['exists' => $this->questionnaire->exists])
        </x-form.form>
    </div>
</div>
