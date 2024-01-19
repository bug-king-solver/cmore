<div>
    <x-modals.confirm  click="createFromTemplate({{$dashboardTemplate->id}})">
        <x-slot name="title">
            {{ __('Create dashboard from template') }}
        </x-slot>

        <x-form.form-row input="text" id="name" label="{{ __('Name') }}" class="after:content-['*'] after:text-red-500"/>

        <x-form.form-row input="textarea" id="description" label="{{ __('Description') }}" class="after:content-['*'] after:text-red-500"/>

    </x-modals.confirm>
</div>
