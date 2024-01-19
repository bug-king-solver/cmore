<div class="text-esg27 text-sm font-encodesans w-full grid grid-cols-2 lg:grid-cols-8 items-center">
    <div class="col-span-1">
        <a href="{{ route('tenant.questionnaires.panel') }}">< {{ __('Back') }}</a>
    </div>

    <div class="col-span-3">
        <span class="font-bold">{{ __('Company') }}</span> {{ $company }}<br>
        <span class="font-bold">{{ __('Report period') }}</span> {{ $from }} {{ __('to') }} {{ $to }}
    </div>

    <div class="col-span-4">
        @if (! isset($ready) || $ready)
            @livewire('questionnaires.progress', ['questionnaire' => $questionnaire])
        @endif
    </div>
</div>
