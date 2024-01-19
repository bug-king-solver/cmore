<div class="px-4 lg:px-0 question-block" data-question-id="{{ $question->id }}" id="question-{{ $question->id }}"
    data-parent-question-id="{{ $question->parent_id }}">
    <div class="w-full pb-8 pt-6 px-4 :not border-t-[1px] border-t-esg6 text-esg8 question_div @if (isset($question->enabled) && !$question->enabled) hidden @endif @if ($questionHighlighted == $question->id) @elseif ($answer->users->contains('id', auth()->user()->id)) @endif "
        data-question-id="{{ $question->id }}" data-parent-question-id="{{ $question->parent_id }}">
        <div x-data="{ information: false }" class="w-100 row relative pb-5 question-description">
            <div>
                <div class="question_answered hide" id="question_answered_{{ $question->id }}">
                    {{ $question->answer->value }}</div>
                <div class="question_validated hide">{{ $question->answer->validation }}</div>
                <div class="question_title hidden">{{ $question->description }}</div>
                <p class="">
                    <span
                        @if (showSupportFeatures()) title="ID: {{ $question->id }}" @endif>{{ $questionRef }}</span>
                    {{ $question->description }}
                    @if ($question->information)
                        <span @click="information = !information"
                            class="inline-block cursor-pointer">@include('icons.info')</span>
                    @endif
                </p>
                <p x-cloak x-show="information" class="information mt-2 text-sm text-esg16">
                    {!! $question->information !!}
                </p>
            </div>
        </div>
        <div class="w-100 row">
            @include('livewire.tenant.questionnaires.answer-types.' . $question->answer_type)
        </div>

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
        {{-- @livewire('questionnaires.question-comment' , ['answer' => $answer]) --}}

        @if ($showComments)
            <div x-cloak class="w-100 row">
                <livewire:comments :model="$answer" class="text-esg8" />
            </div>
        @endif
    </div>
</div>
