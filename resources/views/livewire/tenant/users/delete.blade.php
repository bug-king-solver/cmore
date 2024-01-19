<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete user') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the user ":user"?', ['user' => $user->name]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
