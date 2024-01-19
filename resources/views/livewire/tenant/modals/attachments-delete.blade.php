<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete file') }}
        </x-slot>

        <x-slot name="question">
            {!! __('Do you want to delete the file :strong":file":cstrong?', ['file' => $attachment->name, 'strong' => '<strong>', 'cstrong' => '</strong>']) !!}
        </x-slot>

        <x-slot name="extra">
            {!! __('(This action is irreversible and the document will be detached from all data.)') !!}
        </x-slot>
    </x-modals.confirm-delete>
</div>
