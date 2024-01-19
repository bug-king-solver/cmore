<div class="min-h-[300px]">
    <x-modals.form class=""
        title="{{ $group->exists ? __('Edit: :name', ['name' => $group->name]) : __('Create a new group') }}"
        buttonPosition="justify-end mr-7" :wire:ignore.self savemethod="saveNewGroup">

        <x-form.form-col input="text" id="name" label="{{ __('Group') }}" class="after:content-['*'] after:text-red-500" placeholder="{{ __('Group name') }}"
            form_div_size='w-full' />

        <x-form.form-editor label="{{ __('Description') }}" id="description" form_div_size='w-full'
            value="{{ $this->description }}" />

    </x-modals.form>
</div>
