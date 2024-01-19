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
        <x-header title="{{ __('CO2 Calculator') }}" data-test="co2calculator-header"
            click="{{ route('tenant.questionnaires.panel') }}" class="!bg-esg4   print:hidden"
            iconcolor="{{ color(5) }}" textcolor="text-esg5">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div x-data="{ showMore: false }" class="mt-10 whitespace-normal text-sm text-esg8">
        <p>
            {!! __(
                "C-MORE's greenhouse gas (GHG) emissions calculator uses the categories, definitions, and methodological guidelines of the Greenhouse Gas Protocol and the Intergovernmental Panel on Climate Change (IPCC), and is built in such a way as to request the data that is most closely related to emissions, simplifying, from the user's point of view, the process of identifying emission sources and defining the calculation system.",
            ) !!}
        </p>
        <div x-show="showMore" style="display:none" class="text-sm">
            <p class="mt-2">
                {!! __(
                    'This rationale is fed by the information (quantitative and qualitative) that is collected through the questions, which operation is based on the decision tree model (i.e., it goes into greater detail depending on the answer given).',
                ) !!}
            </p>
            <p class="mt-2">
                {!! __(
                    'To obtain the most realistic estimates possible, the calculator asks for activity data (i.e., consumed quantities of fuel, electricity, etc.) and, if the organization has this information, specific emission factors.',
                ) !!}
            </p>
            <p class="mt-2">
                {!! __(
                    'If the reporting organization does not have the specific emission factors, will be used those that feed the calculator:',
                ) !!}
            </p>
            <div>
                <ul class="list-disc list-inside m-2 ml-6 text-sm">
                    <li>
                        {!! __('Portuguese Environment Agency') !!}
                    </li>
                    <li>
                        {!! __('Defra - UK Government, Department for Environment Food & Rural Affairs') !!}
                    </li>
                    <li>
                        {!! __(
                            'Our World in Data, a project by the Global Change Data Lab (a non-profit organization based in the UK) and produced through collaborative work between researchers at the University of Oxford',
                        ) !!}
                    </li>
                    <li>
                        {!! __('World Input-Output Database (WIOD), from the University of Groningen') !!}
                    </li>
                </ul>
            </div>
        </div>

        <button class="text-blue-500 hover:text-blue-700 mt-5" x-on:click="showMore = !showMore">
            <template x-if="showMore">
                <x-buttons.btn-icon-text
                    class="!bg-esg4 text-esg8 duration-300 hover:!bg-[#E0F5E3] hover:!border-[#44724D] hover:!text-esg6">
                    {{ __('Show Less') }}
                </x-buttons.btn-icon-text>
            </template>
            <template x-if="!showMore">
                <x-buttons.btn-icon-text
                    class="!bg-esg4 text-esg8 duration-300 hover:!bg-[#E0F5E3] hover:!border-[#44724D] hover:!text-esg6">
                    {{ __('Show More') }}
                </x-buttons.btn-icon-text>
            </template>
        </button>
    </div>

    <hr class="my-10">

    <div class="">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-9">
            <div class="col-span-2">
                <div class="text-2xl font-bold text-esg5 text-center"> {{ __('Welcome to the CO2 Calculator') }} </div>
                <div class="text-lg text-esg16 text-center mt-4">
                    {!! __('We are here to help you find what your company’s :strongGHG emissions:cstrong are!', [
                        'strong' => '<span class="font-bold">',
                        'cstrong' => '</span>',
                    ]) !!}
                </div>

                <x-cards.co2calculator.progress :categories="$categories" :categoryActive="$categoryActive" />

                @if ($categoryActive['is_first'])
                    <div class="text-center mt-8 text-base leading-6 text-esg8">
                        {{ __('First, we need to get a sense of the company’s relation to the items below:') }}
                    </div>
                @endif

                @foreach ($categories as $category)
                    @if (!$category['active'])
                        @continue
                    @endif

                    @if (count($category['childrens']) > 0)
                        <x-cards.co2calculator.questionmenu :category="$category" />

                        @foreach ($category['childrens'] as $children)
                            @if (!$children['active'])
                                @continue
                            @endif

                            @foreach ($questions as $question)
                                @livewire('questionnaires.answer-types.' . $question['answer_type'], ['questionnaire' => $questionnaire['id'], 'question' => $question, 'commentsCount' => 0, 'attachmentsCount' => 0, 'questionHighlighted' => 1, 'validators' => [], 'assigners' => [], 'answered_questionsByCategory' => 1], key('q' . $question['id']))
                            @endforeach
                        @endforeach
                    @else
                        @foreach ($questions as $question)
                            @livewire('questionnaires.answer-types.' . $question['answer_type'], ['questionnaire' => $questionnaire['id'], 'question' => $question, 'commentsCount' => 0, 'attachmentsCount' => 0, 'questionHighlighted' => 0, 'validators' => [], 'assigners' => [], 'answered_questionsByCategory' => []], key('q' . $question['id']))
                        @endforeach
                    @endif
                @endforeach
                <div class="flex flex-row justify-between">
                    <div>
                        @if (!empty($subCategorieActive) && !$subCategorieActive['is_first'])
                            <x-buttons.btn-icon-text wire:click="previousCategoryActive()" class="!flex !gap-2">
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
                        @if (!empty($subCategorieActive) && !$subCategorieActive['is_last'])
                            <x-buttons.btn-icon-text wire:click="nextCategoryActive()" class="!flex !gap-2">
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
                    </div>
                </div>
            </div>

            <div>
                @if (count($subCategorieActive) > 0)
                    <x-cards.co2calculator.help :subCategorieActive="$subCategorieActive" />
                    <div class="border-b-2 border-b-esg7 mt-2"></div>
                @endif
                <div class="mt-5">
                    @livewire('questionnaires.ready-to-submit', ['questionnaire' => $questionnaire->id])
                </div>
            </div>

        </div>


    </div>
</div>


@push('body')
    <script nonce="{{ csp_nonce() }}">
        window.livewire.on('show::answered_count_changed', () => {
            window.livewire.emit('co2Calculator::refresh');
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
