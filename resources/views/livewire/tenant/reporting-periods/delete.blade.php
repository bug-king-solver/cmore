<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete api token') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the token ":token-name"?', ['token-name' => $token->name]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
