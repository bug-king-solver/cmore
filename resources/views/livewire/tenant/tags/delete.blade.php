<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete tag') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the tag ":tag"?', ['tag' => $tag->name]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
