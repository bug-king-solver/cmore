<div class="grid grid-cols-1 sm:grid-cols-3 text-2xl font-bold flex flex-row gap-2 items-center text-esg5">
    <div class="sm:col-span-1">
        <a href="{{ route('tenant.questionnaires.panel') }}">
            <div class="flex items-center gap-2">
                @include('icons.back', [
                    'color' => color(5),
                    'width' => '20',
                    'height' => '16',
                ])
                <div style="overflow-wrap: break-word"><span class="font-bold">{{ __('Questionnaire') }}</span> {{ $company }}</div>
            </div>
        </a>
    </div>
</div>
