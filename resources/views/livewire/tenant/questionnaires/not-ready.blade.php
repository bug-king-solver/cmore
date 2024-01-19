@push('body')
    <script nonce="{{ csp_nonce() }}">
        window.setTimeout(() => {
            window.location = window.location;
        }, 3000);
    </script>
@endpush

<div>
    <x-slot name="header">
        <x-header>
            <x-slot name="left">
                <x-modules.questionnaires.resume questionnaire="{{ $questionnaire->id }}" :ready="$questionnaire->is_ready"
                    company="{{ $questionnaire->company->name }}" from="{{ $questionnaire->from->format('Y-m-d') }}"
                    to="{{ $questionnaire->to->format('Y-m-d') }}" />
            </x-slot>
        </x-header>
    </x-slot>

    <div class="p-20">
        <p class="text-esg28 text-center text-xl font-bold">
            {{ __('We are just preparing your questionnaire.') }}<br>
            {{ __('Please wait a few seconds...') }}
        </p>
        <p class="mt-4 text-esg28 text-center">
            {{ __('(the page will automatically refresh)') }}
        </p>
    </div>
</div>
