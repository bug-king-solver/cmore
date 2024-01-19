<div class="px-4 md:px-0 company">
    <x-slot name="header">
        <x-header title="{{ __('Company') }}" data-test="data-header" click="{{ route('tenant.companies.index') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>


    @if (
        $companyFlow['steps'][$currentStep]['type'] == 'form' &&
            view()->exists(customInclude('tenant.companies.introduction')))
        <div class="w-full">
            <div class="row py-10 w-1/2 m-auto">
                {!! tenantView('tenant.companies.introduction') !!}
            </div>
        </div>
    @endif

    <div class="w-full">
        <ol class="flex flex-row py-10 w-full m-auto">
            @foreach ($companyFlow['steps'] ?? [] as $i => $flow)
                @php
                    $isFilled = $i <= $currentStep;
                    $class = '';
                    if (!$loop->last) {
                        $class = "after:content-[''] after:w-full after:h-1 after:border-b after:rounded-md after:border-4 after:inline-block";
                        if ($isFilled) {
                            $class .= ' after:border-[#008131]';
                        } else {
                            $class .= ' after:border-esg6/20';
                        }
                    }
                @endphp
                <li
                    class="flex flex-row {{ !$loop->last ? 'w-full' : '' }} items-center text-esg27 {{ $class }} relative">
                    <div
                        class="relative z-10 flex items-center justify-center w-10 h-10 {{ $currentStep > $i ? 'bg-[#4c9f38] text-esg6' : 'bg-esg6/20 text-esg6' }} rounded-full lg:h-12 lg:w-12 shrink-0">
                        @if ($loop->last)
                            ✓
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </div>


    @foreach ($companyFlow['steps'] ?? [] as $i => $flow)
        @if ($currentStep != $i)
            @continue
        @endif
        @if ($flow['type'] === 'form')
            <div class="w-full">
                @include('livewire.tenant.companies.form-inputs', ['showButtons' => false])
            </div>
        @elseif($flow['type'] === 'questionnaire')
            <div class="w-full">
                @if ($questionnaire)
                    @if ($questionnaire->is_ready)
                        @foreach ($questionnaire->questions() as $question)
                            @if (isset($question['answer_type']))
                                @livewire('questionnaires.answer-types.' . $question['answer_type'], ['questionnaire' => $questionnaire['id'], 'question' => $question, 'commentsCount' => 0, 'attachmentsCount' => 0, 'questionHighlighted' => 0, 'validators' => [], 'assigners' => [], 'answered_questionsByCategory' => []], key('q' . $question['id']))
                            @endif
                        @endforeach
                    @else
                        <div class="p-10">
                            <p class="text-esg28 text-center text-xl font-bold">
                                {{ __('We are just preparing your questionnaire.') }}<br>
                                {{ __('Please wait a few seconds...') }}
                            </p>
                            <p class="mt-4 text-esg28 text-center">
                                {{ __('(the page will automatically refresh)') }}
                            </p>
                        </div>
                        @push('body')
                            <script nonce="{{ csp_nonce() }}">
                                window.setTimeout(() => {
                                    window.location = window.location;
                                }, 3000);
                            </script>
                        @endpush
                    @endif
                @endif
            </div>
        @elseif($flow['type'] === 'dashboard')
            <div class="w-full ">
                @if ($questionnaire)
                    @if ($questionnaire->result_at === null)
                        {!! view()->make('tenant.dashboards.not_ready', [
                                'questionnaire' => $questionnaire,
                                'useExtends' => false,
                            ])->render() !!}
                    @else
                        {!! view()->make('tenant.dashboards.' . $flow['questionnaire_type_id'], [
                                'questionnaire' => $questionnaire,
                                'useExtends' => false,
                            ])->render() !!}
                    @endif
                @endif
            </div>
        @endif
    @endforeach

    <div class="w-full">
        <div class="flex flex-row justify-between mt-10">
            <div>
                @if ($currentStep > 0)
                    <x-buttons.btn-icon-text wire:click="previousStep({{ $currentStep }})" class="!flex !gap-2">
                        <x-slot name="buttonicon">
                            @include('icons.arrow', [
                                'color' => color(5),
                                'width' => '12',
                                'height' => '12',
                                'class' => '-rotate-180',
                            ])
                        </x-slot>
                        {{ __('Previous') }}
                    </x-buttons.btn-icon-text>
                @endif
            </div>
            <div>
                @if ($currentStep + 1 < $companyFlow['total'])
                    @if ($questionnaire)
                        @livewire('companies.flow.next-button', [
                            'questionnaire' => $questionnaire->id,
                            'currentStep' => $currentStep,
                        ])
                    @else
                        <x-buttons.btn-icon-text wire:click="nextStep({{ $currentStep }})" class="!flex !gap-2">
                            @include('icons.arrow', [
                                'color' => color(5),
                                'width' => '12',
                                'height' => '12',
                            ])
                            <x-slot name="buttonicon">
                                {{ __('pagination.next') }}
                            </x-slot>
                        </x-buttons.btn-icon-text>
                    @endif
                @elseif ($currentStep + 1 == $companyFlow['total'])
                    <x-buttons.btn-icon-text wire:click="nextStep({{ $currentStep }})" class="!flex !gap-2">
                        @include('icons.arrow', [
                            'color' => color(5),
                            'width' => '12',
                            'height' => '12',
                        ])
                        <x-slot name="buttonicon">
                            {{ __('Finish') }}
                        </x-slot>
                    </x-buttons.btn-icon-text>
                @endif
            </div>
        </div>
    </div>

</div>
