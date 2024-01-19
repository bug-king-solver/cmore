<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete result') }}
        </x-slot>

        <x-slot name="question">
            {{  __('Do you want to delete the result ":name"', ['name' => $result->getFirstMedia('library')->name ?? '', 'reported_at' => $result->created_at])  }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
