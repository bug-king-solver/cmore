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

@push('body')
    <script nonce="{{ csp_nonce() }}">
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

<div>
    <x-slot name="header">
        @php $top = tenant()->show_topbar ? 'top-[6.5rem]' : 'top-16'; @endphp
        <x-header class="w-full absolute {{ $top }} h-20 z-40" questionnaire="1">
            <x-slot name="left">
                <x-modules.questionnaires.topbar company="{{ $questionnaire->company->name }}" />
            </x-slot>
        </x-header>
    </x-slot>

    @cannot('questionnaires.answer')
        <div class="mt-48">
            <p class="text-normal text-red-600 font-bold">
                {{ __('Attention: You don\'t have privileges to complete the questionnaire.') }}</p>
        </div>
    @endcannot

    @php $top = tenant()->show_topbar ? 'mt-[14.5rem]' : 'mt-48'; @endphp
    <div class="{{ $top }} @if ($questionnaire->categories) grid grid-cols-1 sm:grid-cols-4 @endif">

        @isset($questionnaire->type->texts['title'])
            <div class="row mb-5 text-center text-4xl text-esg6">
                {{ __($questionnaire->type->texts['title']) }}
            </div>
        @endisset

        @isset($questionnaire->type->texts['introduction'])
            <div class="m-auto w-1/3 mb-10 text-center">
                {{ __($questionnaire->type->texts['introduction']) }}
            </div>
        @endisset

        @if ($questionnaire->categories)
            <div class="sm:col-span-1">

                @php $top = tenant()->show_topbar ? 'top-[14.5rem]' : 'top-48'; @endphp
                @php $data = json_encode(["questionnaire" => $questionnaire->id]); @endphp
                <div class="sticky {{ $top }} bg-gray-100 p-2">
                    @livewire('questionnaires.menu', ['questionnaire' => $questionnaire->id, 'category' => $categorySelected, 'data' => $data], key('menu'))
                </div>
            </div>
        @endif


        <div class="@if ($questionnaire->categories) md:ml-4 md:pl-20 sm:col-span-3 @else sm:col-span-1 @endif">
            @if (!isset($questionnaire->type->bars['status']) || $questionnaire->type->bars['status'])
                <div class="flex justify-between">
                    <h2 class="flex text-esg29 items-center">
                        @include('icons.building', [
                            'color' => color(5),
                            'height' => '30',
                            'width' => '30',
                            'class' => 'filipIcon px-1',
                        ])
                        {{ $questionnaire->company->name }}
                    </h2>
                    <p class="flex items-center text-esg29">
                        @include('icons.calender', [
                            'color' => color(5),
                            'height' => '30',
                            'width' => '30',
                            'class' => 'px-1',
                        ])
                        {{ $questionnaire->from->format('Y-m-d') }} {{ __('to') }}
                        {{ $questionnaire->to->format('Y-m-d') }}
                    </p>
                    <p class="flex items-center text-esg29 text-right">
                        {{ __('Overall Progress') }}
                        @include('icons.checkbox', [
                            'color' => color(5),
                            'height' => '30',
                            'width' => '30',
                            'class' => 'px-1',
                        ])
                        {{ round($questionnaire->progress) }}%
                    </p>
                </div>
            @endif

            @if (!isset($questionnaire->type->bars['filters']) || $questionnaire->type->bars['filters'])
                <div class="grid grid-cols-6 gap-2 m-2 p-2 border-2 border-gray-200">
                    <div class="col-span-3">
                        <input type="text" id="question-search-bar" placeholder="{{ __('Search') }}"
                            class="w-full px-4 py-2 rounded-lg border-gray-400 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div class="col-span-1 flex">
                        <select class="w-full p-2 rounded" id="status" x-on:change=applyFilter()>
                            <option value="">{{ __('Status') }}</option>
                            <option value="answered">{{ __('Answered') }}</option>
                            <option value="not_answered">{{ __('Not Answered') }}</option>
                            <option value="validated">{{ __('Validated') }}</option>
                            <option value="not_validated">{{ __('Not Validated') }}</option>
                        </select>
                    </div>
                    <div class="col-span-1 flex">
                        <select class="w-full p-2 rounded" id="assign" x-on:change=applyFilter()>
                            <option value="">{{ __('Assignee') }}</option>
                            @foreach ($questionnaire->users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1 flex">
                        <select class="w-full p-2 rounded" id="validator" x-on:change=applyFilter()>
                            <option value="">{{ __('Validator') }}</option>
                            @foreach ($questionnaire->users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif



            @if (!isset($questionnaire->type->bars['actions']) || $questionnaire->type->bars['actions'])
                <div class="grid grid-cols-2 gap-4 bg-gray-100 p-2 m-2">
                    <div>{{ __('Actions') }}</div>
                    @if (
                        $questionnaire->categories &&
                            $categorySelected &&
                            $this->filteredQuestionareCategory &&
                            count($this->filteredQuestionareCategory))
                        <div class="text-right">{{ $answered_questionsByCategory }}
                            / {{ $questionsByCategory }} {{ __('questions') }}
                        </div>
                    @endif
                </div>
            @endif

            @foreach ($questions as $question)
                @php
                    $validators = [];
                    $assigners = [];
                    if ($question->usersCanValidateAnswer->first()) {
                        $validators = $question->usersCanValidateAnswer
                            ->where('assignment_type', App\Enums\Questionnaire\AssignmentType::CAN_VALIDATE->value)
                            ->pluck('user_id')
                            ->toArray();
                        $assigners = $question->usersCanValidateAnswer
                            ->where('assignment_type', App\Enums\Questionnaire\AssignmentType::CAN_ANSWER->value)
                            ->pluck('user_id')
                            ->toArray();
                    }
                @endphp

                @livewire('questionnaires.answer-types.' . $question->answer_type, ['questionnaire' => $questionnaire->id, 'question' => $question, 'commentsCount' => $question->comments_count, 'attachmentsCount' => $question->attachments_count, 'questionHighlighted' => $questionHighlighted, 'validators' => $validators, 'assigners' => $assigners, 'answered_questionsByCategory' => $answered_questionsByCategory], key('q' . $question->id))
            @endforeach

            @if ($questionnaire->categories)
                <div class="border-t-esg6 text-esg29 mb-8 grid grid-cols-2 border-t-[1px] pt-7 px-4 lg:px-0">
                    <div class="">
                        @if (isset($firstSubCategory->id))
                            @if ($categoryPrevious && $firstSubCategory->id != $categoryFirstQuestion->category_id)
                                <a href="{{ route('tenant.questionnaires.show', ['questionnaire' => $questionnaire, 'category' => $categoryPrevious]) }}"
                                    class="border-esg5 font-inter font-lx bg-esg5 text-white rounded-lg border-2 p-3 font-bold uppercase"><span
                                        class="inline-block align-bottom"><svg data-accordion-icon
                                            class="h-6 w-6 shrink-0 rotate-90" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="https://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg></span> {{ $categoryPrevious->name ? __('pagination.previous') : '' }}</a>
                            @endif
                        @else
                            @if (isset($categoryPrevious))
                                <a href="{{ route('tenant.questionnaires.show', ['questionnaire' => $questionnaire, 'category' => $categoryPrevious]) }}"
                                    class="border-esg5 font-inter font-lx bg-esg5 text-white rounded-lg border-2 p-3 font-bold uppercase"><span
                                        class="inline-block align-bottom"><svg data-accordion-icon
                                            class="h-6 w-6 shrink-0 rotate-90" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="https://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg></span> {{ $categoryPrevious->name ? __('pagination.previous') : '' }}</a>
                            @endif
                        @endif
                    </div>
                    <div class="text-right">
                        @if ($categoryNext)
                            <a href="{{ route('tenant.questionnaires.show', [$questionnaire, $categoryNext]) }}"
                                class="border-esg5 font-inter font-lx bg-esg5 text-white rounded-lg border-2 p-3 font-bold uppercase">{{ $categoryNext->name ? __('pagination.next') : '' }}
                                <span class="inline-block align-bottom"><svg data-accordion-icon
                                        class="h-6 w-6 shrink-0 -rotate-90" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="https://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg></span></a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($questionnaire->type['progress'] ?? false)
        @livewire('questionnaires.ready-to-submit', ['questionnaire' => $questionnaire->id])
    @endif
</div>

@push('child-scripts')
    <script nonce="{{ csp_nonce() }}">
        var categoriesIds = categories();

        let searchInput;
        let filterSearch;
        let filterStatus;
        let filterAssigned;
        let filterValidator;

        let questions;

        @if ($questionnaire->type->bars['filters'] ?? true)
            document.addEventListener('DOMContentLoaded', () => {
                searchInput = document.querySelector('#question-search-bar');
                filterSearch = document.querySelector('#question-search-bar').value;
                filterStatus = document.getElementById('status').value;
                filterAssigned = document.getElementById('assign').value;
                filterValidator = document.getElementById('validator').value;

                questions = Array.from(document.querySelectorAll('.question-block'));

                searchInput.addEventListener('input', (event) => {
                    applyFilter();
                });
            });
        @endif

        function categories() {
            let categories = document.querySelectorAll('.question_div')
            let categoriesQuestionAttribute = [];
            categories.forEach((category) => {
                if (!category.classList.contains('hidden')) {
                    categoriesQuestionAttribute.push(category.getAttribute('data-question-id'));
                }

            });
            return categoriesQuestionAttribute;
        }

        function applyFilter(filter = '') {
            let statusAnswered = "{{ App\Enums\Questionnaire\AnswerStatus::ANSWERED->value }}";
            let statusNotAnswered = "{{ App\Enums\Questionnaire\AnswerStatus::NOT_ANSWERED->value }}";
            let statusValidated = "{{ App\Enums\Questionnaire\AnswerStatus::VALIDATED->value }}";
            let statusNotValidated = "{{ App\Enums\Questionnaire\AnswerStatus::NOT_VALIDATED->value }}";

            let filterStatusValue = document.getElementById('status').value;
            let filterAssignedValue = document.getElementById('assign').value;
            let filterValidatorValue = document.getElementById('validator').value;

            let searchInput = document.querySelector('#question-search-bar');
            let subCategoryProgress = "{{ $childCategoryProgress }}";

            questions.map((question) => {
                let shouldShow = true;

                let enabled = parseInt(question.getAttribute('data-enabled'));
                let answered = parseInt(question.getAttribute('data-answered'));
                let assigned = question.querySelector('.assign-users').textContent;
                let validated = question.querySelector('.validator-user').textContent;

                if (!enabled) {
                    shouldShow = false;
                }

                if (filterStatusValue && filterStatusValue === statusAnswered && !answered) {
                    shouldShow = false;
                }

                if (filterStatusValue && filterStatusValue === statusNotAnswered && answered) {
                    shouldShow = false;
                }

                if (filterAssignedValue && !assigned.includes(filterAssignedValue)) {
                    shouldShow = false;
                }

                if (filterValidatorValue && !validated.includes(filterValidatorValue)) {
                    shouldShow = false;
                }

                if (searchInput.value.trim()
                    && !question.querySelector('.question_title').textContent.toLowerCase().includes(searchInput.value)) {
                    shouldShow = false;
                }

                if (shouldShow) {
                    question.classList.remove('hidden');
                } else {
                    question.classList.add('hidden');
                }
            });

            if (searchInput.value) {
                categoriesIds = categories();
            }
        }

        window.addEventListener('co2-calculator', event => {
            // add the event.detail.emissionFactor to the input with wire:change='save(event.detail.questionOption)'
            let emissionFactor = event.detail.emissionFactor;
            let questionOption = event.detail.questionOption;
            const inputElement = document.getElementById(`questionOption_${questionOption}`);

            if (inputElement) {
                inputElement.value = emissionFactor;
                inputElement.dispatchEvent(new Event('change'));
            }
        })
    </script>
@endpush
