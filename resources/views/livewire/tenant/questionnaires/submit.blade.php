<div>
    <x-modals.confirm click="save">
        <x-slot name="title">
            {{ __('Submit questionnaire') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Are you sure you want to submit the questionnaire? After submitting the questionnaire, if you want to change any information, you will need to change the status of the questionnaire and resubmit.') }}
        </x-slot>
    </x-modals.confirm>
</div>
