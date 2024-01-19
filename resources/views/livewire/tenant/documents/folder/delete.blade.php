<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete folder') }}
        </x-slot>

        <x-slot name="question">
            {!! __('Do you want to delete the folder :strong":company :cstrong" and it\'s files ?', ['company' => $documentFolder->name, 'indicator' => $documentFolder->name, 'reported_at' => $documentFolder->created_at, 'strong' => '<strong>', 'cstrong' => '</strong>']) !!}
        </x-slot>
    </x-modals.confirm-delete>
</div>
