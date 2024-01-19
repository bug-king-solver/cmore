<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete Analysis') }}
        </x-slot>

        <x-slot name="question">
            {{  __('Do you want to delete the analysis ":name"', ['name' => $analysisInfo->name ?? ''])  }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
