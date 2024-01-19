<div class="physcal_risk">
    <x-modals.form title="{{ $physicalRisks->exists ? __('Edit Geography') : __('New Geography') }}" class="!text-esg6"
        buttonPosition="justify-end" buttonColor="!bg-[#44724D]">

        <x-form.form-col input="text" id="name" label="{{ __('Name') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm" form_div_size="w-full" />

        <x-form.form-col input="tomselect" id="relevant" label="{{ __('Relevance') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm" :options="$relevantList"
            items="{{ $relevant ?? '' }}" limit="1" placeholder="{{ __('Select the relevance Options') }}"
            dataDescription="{{ __('Indicate the level of relevance of this location.') }}" form_div_size="w-full"
            tooltipModel="true" />

        <div class="p-2" id="relevanceDescription">
            <span class="text-sm leading-3 text-[#757575]" wire:poll>{{ $relevanceDescription }}</span>
        </div>

        <x-form.form-col input="textarea" id="note" label="{{ __('Note') }}"
            class="!text-esg8 !font-normal !text-sm resize-none" form_div_size="w-full"
            dataDescription="{{ __('Justification for the level of relevance.') }}" tooltipModel="true" />

    </x-modals.form>
</div>
