<div>
    <x-form.form title="{{ __('Request information') }}" savebuttontext="{{ __('Request') }}" buttonPosition="justify-end" class="!text-esg5 !text-base !font-bold" savebuttonclass="!bg-esg5 !text-esg4 !normal-case">
        <x-form.form-col input="textarea" id="request_info" label="{{ __('Specify the type of information') }}"
            dataTest="data-request-information"
            class="after:content-['*'] after:text-red-500 !text-xs !font-normal"
            form_div_size="w-full"
            fieldClass="!text-xs !font-normal" />
    </x-modals.form>
</div>
