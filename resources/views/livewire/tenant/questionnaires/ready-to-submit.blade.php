<div>
    @if ($isCompleted && !$isSubmitted)
        <p class="text-center text-3xl text-esg6">{{ __('Congratulations!') }}</p>
        <p class="mt-5 text-center text-xl text-esg8">{{ __('Your questionnaire is ready to be submitted!') }}</p>

        @can('questionnaires.submit')
            <p class="mt-5 text-center">
                <x-buttons.btn
                    modal="questionnaires.modals.submit"
                    :data="json_encode(['questionnaire' => $questionnaire])"
                    text="{{ __('Submit') }}"
                    data-test="submit-questionnaire-btn"/>
            </p>
        @endcan
    @endif
</div>
