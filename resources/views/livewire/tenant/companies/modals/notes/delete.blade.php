<div>
    <x-modals.confirm click="delete">
        <x-slot name="title">
            {{ __('Delete note') }}
        </x-slot>

        <x-slot name="question">
            {{ __('You’re about to delete “:title” and its contents, this action is permanent.', ['title' => $note->title]) }}
        </x-slot>
    </x-modals.confirm>
</div>
