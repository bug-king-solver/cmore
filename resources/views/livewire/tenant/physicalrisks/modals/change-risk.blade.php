<div>
    <x-modals.form title="{!! __('Change risk: ') . __($risk['name'])  !!}" buttonPosition="justify-end">

        <h4 class="text-esg8 text-sm font-bold pt-8">
            {!! __('The risk below has been identified for the location entered:') !!}
        </h4>

        <div class="flex items-center gap-8 mt-5">
            <div class="w-8">
                @include('icons.physical_risks.' . strtolower($risk['name_slug']))
            </div>
            <div class="text-sm w-auto text-black text-nowrap">
                {!! __($risk['name']) !!}
            </div>
            <div class="flex flex-row gap-1 w-full items-center">
                <span
                    class="mt-2 mr-2 w-24 h-8 grid place-content-center block text-sm rounded-md {{ getRiskLevelColor($risk['risk_slug']) }} text-esg4">
                    {!! __(getRiskLevelLabel($risk['risk_slug'])) !!}
                </span>

                <x-form.form-col input="select" id="risk.risk_slug"
                    class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal" :extra="['options' => $riskList, 'show_blank_opt' => false]"
                    limit="1" placeholder="{{ __('Select the risk level') }}" form_div_size="min-w-[145px]" />
            </div>
        </div>

        <x-form.form-col input="checkbox" id="risk.has_contingency_plan" label="{!! __('Do you have a contingency plan?') !!}" flex="true" form_div_size="!-mt-2" class="mt-5" />
        <x-form.form-col input="textarea" id="risk.contingency_description" label="{!! __('Describe your contingency plan?') !!}" form_div_size="!mt-0" class="mt-3" />
        <x-form.form-col input="checkbox" id="risk.has_continuity_plan" label="{!! __('Do you have a continuity plan?') !!}" flex="true" form_div_size="!mt-0" class="mt-3" />
        <x-form.form-col input="textarea" id="risk.continuity_description" label="{!! __('Describe your continuity plan?') !!}" form_div_size="!mt-0" class="mt-3" />
        <x-form.form-col input="textarea" id="textChange" label="{!! __('Enter a justification for changing the criticality:') !!}" form_div_size="!mt-0" class="mt-3" />

    </x-modals.form>
</div>
