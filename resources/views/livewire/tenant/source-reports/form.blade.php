<div>
    <x-modals.form title="{{ __('Create a new report') }}">

        <x-form.form-col
            input="tomselect" id="framework" label="{{ __('Select the framework') }}"
            :options="$frameworkList" :items="$framework ?: []" limit="1"
            placeholder="{{ __('Select the Framework you wish to report') }}"
            class="after:content-['*'] after:text-red-500"
            dataTest="framework-report"
            form_div_size="w-full"
            modelmodifier=".lazy"
            fieldClass="!border-0 !bg-esg7/10 !text-esg8" />

        <x-form.form-col
            input="tomselect" id="taggableIds" label="{{ __('Tags') }}"
            :options="$taggableList" :items="$taggableIds ?: []"
            plugins="['no_backspace_delete', 'remove_button']"
            placeholder="{{ __('Select tags') }}"
            dataTest="questionnaire-tags"
            form_div_size="w-full"
            fieldClass="!border-0 !bg-esg7/10 !text-esg8" />

        @error('framework')
            <x-form.form-row
                input="checkbox-inline" id="force"
                label="{{ __('You can only have one export for each framework, if you proceed this will replace your current export') }}"
                class="!inline !font-normal"
                dataTest="framework-force"
                form_div_size="w-full"
                fieldClass="!border-0 !bg-esg7/10 !text-esg8" />
        @enderror

    </x-modals.form>
</div>
