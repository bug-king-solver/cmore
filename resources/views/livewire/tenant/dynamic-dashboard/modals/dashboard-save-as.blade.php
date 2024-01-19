<div>
    <x-modals.confirm  click="create({{$dashboard->id}})">
        <x-slot name="title">
            {{ __('Create dashboard with filters') }}
        </x-slot>
        <x-slot name="question">
            {!! __('What name do you want to use to the new dashboard?') !!}
        </x-slot>

        <x-form.form-row input="text" id="name" label="{{ __('Name') }}" class="after:content-['*'] after:text-red-500"/>

        <x-form.form-row input="textarea" id="description" label="{{ __('Description') }}" class="after:content-['*'] after:text-red-500"/>

    </x-modals.confirm>
</div>
