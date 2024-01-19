<div class="company_notes">
    <x-modals.form title="{{ ($note->exists) ? __('Edit Note') : __('Add Note') }}" class="text-esg5" buttonPosition="justify-end" buttonColor="!bg-esg5">

        <div class="flex flex-col gap-5 mt-6">
            <div class="w-full">
                <label
                    class="block after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm">{{ __('Title') }}
                </label>
                <x-form.form-col input="text" id="title" class="!text-esg8 !font-normal !text-sm w-full"
                    form_div_size="w-full" placeholder="{!! __('Insert a title for your note') !!}" />
            </div>
        </div>

        <div class="flex flex-col gap-5 mt-6">
            <div class="w-full">
                <label
                    class="block after:content-['*'] after:text-red-500 !text-esg8 !font-normal !text-sm">{{ __('Description') }}
                </label>
                <x-form.form-col input="textarea" id="description" class="!text-esg8 !font-normal !text-sm w-full placeholder:text-esg7 !border-esg67 focus:border-esg6 focus:ring-0"
                    form_div_size="w-full" placeholder="{!! __('Write your note') !!}" />
            </div>
        </div>

    </x-modals.form>
</div>
