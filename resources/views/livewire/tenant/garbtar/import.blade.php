<div class="company_assets">
    <x-form.form title="{{ __('Import File') }}"
        class="!text-[#39B54A]" buttonPosition="justify-end" buttonColor="!bg-[#44724D]">

        <div class="grid grid-cols-1 mt-6">

            <div class="w-full">
                <x-form.form-col input="file" id="file" flex="true"
                    name="file"
                    class="!text-esg8 !font-normal !text-sm !rounded-full"
                    form_div_size="w-full !-mt-4" />
            </div>

            <div class="w-full">
                <x-form.form-row
                    input="checkbox-inline" id="confirm" name="confirm"
                    label="{{ __('Replace all data') }}"
                    class="!inline !font-normal"
                    dataTest="import-confirm"
                    form_div_size="w-full"
                    fieldClass="!border-0 !bg-esg7/10 !text-esg8" />

                @error('confirm')
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
                @enderror
            </div>

        </div>

    </x-modals.form>
</div>
