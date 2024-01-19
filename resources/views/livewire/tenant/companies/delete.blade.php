<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete company') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the company ":company"?', ['company' => $company->name]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
