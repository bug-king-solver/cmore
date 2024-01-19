<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {!! __('Delete geography') !!}
        </x-slot>

        <x-slot name="question">
            {!! __('Do you want to delete this geography ":geography"?', ['geography' => $physicalRisk->name]) !!}
        </x-slot>

    </x-modals.confirm-delete>
</div>
