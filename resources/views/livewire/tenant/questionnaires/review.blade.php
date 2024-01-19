<div>
    <x-modals.confirm-review>
        <x-slot name="title">
            {{ __('Review questionnaire') }}
        </x-slot>

        <x-slot name="question">
            {!! __('Do you want to review the questionnaire of the company :strong":company":cstrong, referring to the period of :strong":from / :to":cstrong?', ['company' => $questionnaire->company->name, 'from' => $questionnaire->from->format('Y-m-d'), 'to' => $questionnaire->to->format('Y-m-d'), 'strong' => '<strong>', 'cstrong' => '</strong>']) !!}
        </x-slot>

        <x-slot name="extra">
        </x-slot>
    </x-modals.confirm-review>
</div>
