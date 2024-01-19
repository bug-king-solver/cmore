<div>
    <x-modals.confirm click="save">
        <x-slot name="title">
            {{ __('Sharing consent') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to :action the sharing of your data to the bank ":bank"?', ['bank' => $bankName, 'action' => $action]) }}
        </x-slot>
    </x-modals.confirm>
</div>
