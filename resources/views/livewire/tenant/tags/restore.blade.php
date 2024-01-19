<div>
    <x-modals.confirm-restore>
        <x-slot name="title">
            {{ __('Restore tag') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to restore the tag ":tag"?', ['tag' => $tag->name]) }}
        </x-slot>
    </x-modals.confirm-restore>
</div>
