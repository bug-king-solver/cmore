<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete questionnaire') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the questionnaire of the company ":company", referring to the period of ":from / :to" ?', ['company' => $questionnaire->company->name, 'from' => $questionnaire->from->format('Y-m-d'), 'to' => $questionnaire->to->format('Y-m-d')]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
