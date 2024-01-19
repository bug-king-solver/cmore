<div>
    <x-modals.form title="{{ $tag->exists ? __('Edit: :name', ['name' => $tag->name]) : __('Create a new tag:') }}"
        buttonPosition="justify-end">
        <x-form.form-col input="text" id="name" label="{{ __('Name') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal" form_div_size="w-full"
            fieldClass="border-esg7 h-12 !text-esg8" placeholder="{{ __('Set a name to your tag') }}" />

        <x-form.form-col input="color-v1" id="color" label="{{ __('Color') }}"
            class="after:content-['*'] after:text-red-500 !text-esg8 !font-normal" />
    </x-modals.form>
</div>
