@push('head')
    <x-comments::styles />
    <style nonce="{{ csp_nonce() }}">
        .filipIcon {
            transform: scaleX(-1);
        }

        .hide {
            display: none;
        }
    </style>
@endpush

<div class="px-4 md:px-0">
    <x-slot name="header">
        <x-header title="{{ __('Double Materiality') }}" data-test="co2calculator-header"
            click="{{ route('tenant.questionnaires.panel') }}" class="!bg-esg4   print:hidden"
            iconcolor="{{ color(5) }}" textcolor="text-esg5">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>


    <div class="">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-9">
            <div class="col-span-2">

                <x-generic.progress-bar progress="{{$this->questionnaire->progress}}" current="{{ $this->questionnaire->answeredQuestionsCount() }}"
                    total="{{ count($this->questionnaire->questions) }}" />

                <div class="flex flex-col items-center mt-8 justify-between md:flex-row md:gap-5 mb-8">
                    @foreach ($categories as $category)
                        <div class="w-full h-12 p-4 {{ $category['active'] ? 'bg-esg5/10 border-esg5/70' : 'bg-zinc-400 border-zinc-400' }} bg-opacity-5 rounded shadow border  justify-center items-center gap-2.5 inline-flex cursor-pointer"
                            wire:click='goToCategory({{ $category['id'] }})'>
                            <div
                                class="{{ $category['active'] ? 'text-esg6' : 'text-neutral-500' }} text-base font-bold font-['Lato']">
                                {{ $category['name'] }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="w-full h-0 border border-gray-300"></div>

                <div class="flex flex-col items-center mt-8 justify-between md:flex-row md:gap-5 mb-8">
                    @foreach ($categoryFirstLevel['childrens'] as $category)
                        <div class="w-24 h-14 px-4 {{ $category['active'] == 1 ? 'bg-esg5/10 border-esg5/70' : 'bg-zinc-400 border-zinc-400' }} bg-opacity-5 rounded shadow border justify-center items-center gap-2.5 inline-flex cursor-pointer"
                            wire:click="goToSubCategory({{ $category['id'] }})">
                            <div class="">
                                @includeIf(
                                    'icons.doublemateriality.' . str_replace('icon-', '', $category['note']),
                                    [
                                        'color' => $category['active'] == 1 ? color(6) : '#757575',
                                    ]
                                )
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (isset($categorySecondLevel['active']))
                    <div
                        class="w-full text-center text-neutral-500 text-lg font-bold font-['Lato'] uppercase leading-relaxed mb-8">
                        {{ $categorySecondLevel['name'] }}
                    </div>
                @endif

                @if ($this->categoryThirtyLevel)
                    <div class="flex flex-col items-center mt-8 justify-center md:flex-row md:gap-5 mb-8">
                        @foreach ($categorySecondLevel['childrens'] as $category)
                            <div class="w-72 h-14 px-4 {{ $category['active'] == 1 ? ' border-b border-esg5/60' : '' }} flex justify-center items-center gap-5 mb-8 cursor-pointer"
                                wire:click="goToThirtyCategory({{ $category['id'] }})">
                                <div class="w-8 h-8 relative">
                                    <div class="w-8 h-8 left-[0.50px] top-[0.50px] absolute">
                                        @includeIf(
                                            'icons.doublemateriality.' .
                                                str_replace('icon-', '', $category['note']),
                                            [
                                                'color' => $category['active'] == 1 ? color(6) : '#757575',
                                            ]
                                        )
                                    </div>
                                </div>
                                <div class="text-base font-normal font-['Lato'] leading-normal">
                                    {{ $category['name'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @foreach ($questions as $question)
                    @livewire('questionnaires.answer-types.' . $question['answer_type'], ['questionnaire' => $questionnaire['id'], 'question' => $question, 'commentsCount' => 0, 'attachmentsCount' => 0, 'questionHighlighted' => 1, 'validators' => [], 'assigners' => [], 'answered_questionsByCategory' => 1], key('q' . $question['id']))
                @endforeach
            </div>

            <div>
                <x-questionnaire.doublematurality.help :category="$categorySecondLevel" />
                <div class="border-b-2 border-b-esg7 mt-2"></div>

                <div class="mt-5">
                    @livewire('questionnaires.ready-to-submit', ['questionnaire' => $questionnaire->id])
                </div>
            </div>

        </div>

        <div class="flex flex-row justify-between">
            <div>
                <x-buttons.btn-icon-text wire:click="prevFirstLevel({{ $categoryFirstLevel['id'] }})"
                    class="!flex !gap-2">
                    <x-slot name="buttonicon">
                        @include('icons.arrow', [
                            'color' => color(5),
                            'width' => '12',
                            'height' => '12',
                            'class' => '-rotate-180',
                        ])
                    </x-slot>
                    {{ __('pagination.previous') }}
                </x-buttons.btn-icon-text>
            </div>
            <div>
                <x-buttons.btn-icon-text wire:click="nextFirstLevel({{ $categoryFirstLevel['id'] }})"
                    class="!flex !gap-2">
                    @include('icons.arrow', [
                        'color' => color(5),
                        'width' => '12',
                        'height' => '12',
                    ])
                    <x-slot name="buttonicon">
                        {{ __('pagination.next') }}
                    </x-slot>
                </x-buttons.btn-icon-text>
            </div>
        </div>
    </div>
</div>


@push('body')
    <script nonce="{{ csp_nonce() }}">
        window.livewire.on('show::answered_count_changed', () => {
            window.livewire.emit('doublemateriality::refresh');
        });

        function enableOrDisable(action, questionId) {
            document.querySelectorAll('[data-parent-question-id="' + questionId + '"]').forEach(element => {
                if (action === 'enable') {
                    element.classList.remove('hidden');
                } else if (action === 'disable') {
                    element.classList.add('hidden');
                    element.querySelectorAll('input').forEach(element => {
                        if (element.type === 'radio' || element.type === 'checkbox') {
                            element.checked = false;
                        } else {
                            element.value = '';
                        }
                    });
                    enableOrDisable(action, element.getAttribute('data-question-id'));
                }
            });
        }
    </script>
@endpush
