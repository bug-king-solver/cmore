<div class="px-4 lg:px-0 question-block" data-question-id="{{ $question->id }}" id="question-{{ $question->id }}"
    data-parent-question-id="{{ $question->parent_id }}" data-enabled="{{ $question->enabled ? 1 : 0 }}"
    data-answered="{{ !is_null($answer->value) ? 1 : 0 }}">
    <div class="w-full pb-8 pt-6 px-4 :not border-t-[1px] border-t-esg6 text-esg8 question_div @if (isset($question->enabled) && !$question->enabled) hidden @endif @if ($questionHighlighted == $question->id) @elseif ($answer->users->contains('id', auth()->user()->id)) @endif "
        data-question-id="{{ $question->id }}" data-parent-question-id="{{ $question->parent_id }}">
        <div x-data="{ information: false }" class="w-100 row relative pb-5 question-description">
            <div>
                <div class="question_answered hidden" id="question_answered_{{ $question->id }}">
                    {{ $question->answer->value }}
                </div>
                <div class="question_validated hidden">{{ $question->answer->validation }}</div>
                <div class="question_title hidden">{{ $question->description }}</div>
                <p class="">
                    <span @if (showSupportFeatures()) title="ID: {{ $question->id }}" @endif>
                        {{ $questionRef }}
                    </span>
                    {{ $question->description }}
                    @if ($question->information)
                        <span @click="information = !information"
                            class="inline-block cursor-pointer">@include('icons.info')</span>
                    @endif
                </p>
                <p x-cloak x-show="information" class="information mt-2 text-sm text-esg16">
                    {!! $question->information !!}
                </p>

                @if ($question->allow_not_applicable || $question->allow_not_reportable)
                    <div class="flex items-center gap-7 mt-4 bg-gray-100 p-2 rounded">
                        @if ($question->allow_not_applicable)
                            <div>
                                <input type="checkbox" id="not_applicable_{{ $question->id }}"
                                    wire:model='not_applicable' wire:ignore
                                    class="mr-2 bg-gray-100 border-[1.5px] border-gray-400 rounded-sm checked:bg-esg6"
                                    wire:change="afterSave" />
                                <label for="not_applicable_{{ $question->id }}" class="mt-1"> {!! __('Question not applicable') !!}
                                </label>
                            </div>
                        @endif

                        @if ($question->allow_not_reportable)
                            <div>
                                <input type="checkbox" id="not_reported_{{ $question->id }}" wire:model='not_reported'
                                    wire:ignore
                                    class="mr-2 bg-gray-100 border-[1.5px] border-gray-400 rounded-sm checked:bg-esg6"
                                    wire:change="afterSave" />
                                <label for="not_reported_{{ $question->id }}" class="mt-1"> {!! __('Company does not report') !!}
                                </label>
                            </div>
                        @endif
                    </div>
                @endif


            </div>
        </div>
        <div class="w-100 row">
            @include('livewire.tenant.questionnaires.answer-types.' . $question->answer_type)
        </div>
        @if (!isset($questionnaire->type->bars['question_action']) || $questionnaire->type->bars['question_action'])
            <div class="relative mt-10 flex justify-between">
                <div class="flex space-x-3 justify-start items-center">
                    @php
                        $data = json_encode(['modelId' => $answer->id, 'modelType' => 'answer', 'questionnaireSubmitted' => $this->questionnaire->submitted_at]);
                    @endphp

                    <x-buttons.comment :counter="$commentsCount" :required="$this->commentIsRequired" />
                    <x-buttons.attachment :data="$data" :counter="$attachmentsCount" :required="$this->attachmentIsRequired" />
                    <x-buttons.user-assign :data="$data" :counter="$assigners" :answer="$answer" :isValidator="0" />
                    <x-buttons.user-assign :data="$data" :counter="$validators" :answer="$answer" :isValidator="1" />
                </div>

                <div class="flex space-x-3 justify-end">
                    @php
                        $can_validate = 0;
                        $can_answer = 0;
                        foreach ($answer->users as $answer_user) {
                            if ($answer_user->pivot && Auth::id() == $answer_user->pivot->user_id && $answer_user->pivot->assignment_type == App\Enums\Questionnaire\AssignmentType::CAN_VALIDATE->value) {
                                $can_validate = 1;
                            }
                            if ($answer_user->pivot && Auth::id() == $answer_user->pivot->user_id && $answer_user->pivot->assignment_type == App\Enums\Questionnaire\AssignmentType::CAN_ANSWER->value) {
                                $can_answer = 1;
                            }
                        }
                    @endphp
                    @if ($can_validate)
                        <button class="p-1 border-2 rounded border-gray-400 text-gray-600"
                            wire:click="toggleAnswerValidation">
                            @if ($answer->validation)
                                {{ 'Undo Validation' }}
                            @else
                                {{ 'Validate Answer' }}
                            @endif
                        </button>
                    @endif
                    <button class="hidden p-1 border-2 rounded border-gray-400 text-gray-600"
                        wire:click.prevent="clearAnswer">{{ __('Clear Answer') }}</button>
                </div>
            </div>

            @if ($showComments)
                <div x-cloak class="w-100 row">
                    <livewire:comments :model="$answer" class="text-esg8" />
                </div>
            @endif
        @endif
    </div>
</div>

@push('body')
    <script nonce="{{ csp_nonce() }}">
        function glossary() {
            @if (isset($glossary))
                const glossary = {!! $glossary !!};
            @else
                const glossary = {};
            @endif

            const question = document.querySelector('.question-block[data-question-id="{{ $question->id }}"]');
            const questionDescription = question.querySelector('.question-description p:not(.information)');

            let questionDescriptionInnerHTMLOriginal = questionDescription.innerHTML;
            let questionDescriptionInnerHTML = questionDescription.innerHTML;

            Object.keys(glossary).forEach((word, index) => {
                let id = `${question.getAttribute('data-question-id')}-${index}`;
                // Determne if it exists on the original string to don't style words inside the tooltip
                let position = questionDescriptionInnerHTMLOriginal.indexOf(word);
                let nextChar = questionDescriptionInnerHTMLOriginal[position + word.length];

                if (position === -1 || (nextChar === undefined || nextChar.toLowerCase() !== nextChar
                        .toUpperCase())) {
                    return;
                }

                position = questionDescriptionInnerHTML.indexOf(word);

                questionDescriptionInnerHTML = `
                    ${questionDescriptionInnerHTML.substring(0, position)}
                    <div id="tooltip-trigger-${id}" data-tooltip-target="tooltip-target-${id}" data-tooltip-trigger="hover" data-tooltip-placement="bottom" class="inline cursor-help underline underline-offset-4 decoration-dotted decoration-esg5 font-bold">
                        ${questionDescriptionInnerHTML.substring(position, position + word.length)}
                    </div>
                    <div id="tooltip-target-${id}" role="tooltip" class="inline-block absolute invisible opacity-0 z-10 p-3 text-xs text-esg8 w-56 text-center bg-esg15 rounded shadow-sm transition-opacity duration-300 tooltip">
                        ${glossary[word].replace('\n', '<br /><br />')}
                    </div>
                    ${questionDescriptionInnerHTML.substring(position + word.length)}
                `;

                questionDescription.innerHTML = questionDescriptionInnerHTML;
            });

            document.querySelectorAll('.question-block[data-tooltip-target]').forEach(triggerEl => {
                const targetEl = document.getElementById(triggerEl.getAttribute('data-tooltip-target'))
                const triggerType = triggerEl.getAttribute('data-tooltip-trigger');

                new Tooltip(targetEl, triggerEl, {
                    triggerType
                });
            });
        }

        glossary();
    </script>
@endpush
