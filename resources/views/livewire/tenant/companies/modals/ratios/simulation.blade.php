<div class="company_simulation">
    <x-modals.form title="{{ __('Add Simulation') }}" class="text-esg5" buttonPosition="justify-end" buttonColor="!bg-esg5">

        <div class="flex flex-col gap-5 mt-6">
            <div class="w-full">
                <label
                    class="block after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm">{{ __('Name') }}
                </label>
                <x-form.form-col input="text" id="name" class="!text-esg8 !font-normal !text-sm w-full"
                    form_div_size="w-full" placeholder="{!! __('Name') !!}" />
            </div>
        </div>

    </x-modals.form>
</div>
