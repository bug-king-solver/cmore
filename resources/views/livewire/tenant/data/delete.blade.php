<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete data') }}
        </x-slot>

        <x-slot name="question">
            {{  __('Do you want to delete the data ":company » :indicator" reported at :reported_at?', ['company' => $data->company->name, 'indicator' => $data->indicator->name, 'reported_at' => $data->reported_at])  }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
