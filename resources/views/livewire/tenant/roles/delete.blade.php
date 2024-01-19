<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete role') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the role ":role"?', ['role' => $role->name]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
