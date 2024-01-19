<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Taxonomy Questionnaire') }}" data-test="taxonomy-header"
            click="{{ route('tenant.questionnaires.panel') }}"
            class=" {{ is_null($taxonomy->started_at) ? 'hidden' : '' }}" iconcolor="text-esg5"
            textcolor="text-esg5">
            <x-slot name="left"></x-slot>
            <x-slot name="right"></x-slot>
        </x-header>
    </x-slot>

    @php
        $questionnaireData = json_encode(['questionnaire' => $questionnaire->id]);
        $taxonomyData = json_encode(['taxonomy' => $taxonomy->id]);
    @endphp

    <div class="text-esg8" x-data="{ showMore: false }" class="whitespace-normal">
        <p>
            {!! __(
                'The European Environmental Taxonomy is an ecological classification system for economic activities. Each company must carry out the exercise to individually classify each of the activities it carries out.',
            ) !!}
        </p>
        <div x-show="showMore" style="display:none" x-cloak>
            <p class="mt-2">
                {!! __(
                    'Companies that have previously carried out the taxonomy exercise can choose to only import the results they already have. Companies that have not yet carried out the exercise can do so through this portal.',
                ) !!}
            </p>
            <p class="mt-2">
                {!! __(
                    'Not all activities are currently covered by the classification system, so it may happen that a company does not have any activity covered by the Taxonomy. If you do not have any eligible activity, you should complete the exercise, without entering activities.',
                ) !!}
            </p>
        </div>
        <button class="text-blue-500 hover:text-blue-700 mt-5" x-on:click="showMore = !showMore">
            <template x-if="showMore">
                <x-buttons.btn-icon-text
                    class="!bg-esg4 text-esg8  duration-300 hover:!bg-[#E0F5E3] hover:border-esg6/2 hover:text-esg6">
                    {{ __('Show Less') }}
                </x-buttons.btn-icon-text>
            </template>
            <template x-if="!showMore">
                <x-buttons.btn-icon-text
                    class="!bg-esg4 text-esg8  duration-300 hover:!bg-[#E0F5E3] hover:border-esg6 hover:text-esg6">
                    {{ __('Show More') }}
                </x-buttons.btn-icon-text>
            </template>
        </button>
    </div>

    <div class="pb-20">
        <div class="" x-data="{ open: false }">
            <div class="flex justify-end" x-show="!open">
                <div class="flex items-center cursor-pointer" x-on:click="open = ! open">
                    <span class="p-2 border border-esg6/10 border-r-0 rounded-l-md">
                        @include('icons.according.left')
                    </span>
                    <div class="px-6 py-2 text-lg font-bold text-esg6 border border-esg6/10 rounded-md">
                        {!! __('Summary') !!}
                    </div>
                </div>
            </div>

            <div x-bind:class="{ '!translate-x-0 visitble opacity-100 h-full': open }"
                class="translate-x-full opacity-0 duration-500 h-0">
                <span class="p-2 border border-esg6/10 border-r-0 rounded-l-md absolute -ml-[25px] mt-5 cursor-pointer"
                    x-on:click="open = ! open">
                    @include('icons.according.right')
                </span>
                @php $text = json_encode([__('Resume')]); @endphp
                <x-cards.card-dashboard-version1 text="{{ $text }}"
                    class="!h-auto !border-[#E1E6EF] !mt-6 !p-3 " titleclass='!text-lg !font-bold !leading-9'
                    contentplacement='place-content-stretch'>
                    <x-tables.table class="text-base !min-w-full mt-8 rounded-sm">
                        <x-slot name="thead">
                            <x-tables.th :no_border="true"></x-tables.th>
                            <x-tables.th :no_border="true" class="font-normal text-center !p-0">
                                <div class="flex gap-3 items-center">
                                    @include('icons.up-trand')
                                    {{ __('Business volume') }}
                                </div>
                            </x-tables.th>
                            <x-tables.th :no_border="true" class="font-normal text-center !p-0">
                                <div class="flex gap-3 items-center">
                                    @include('icons.money')
                                    {{ __('CAPEX') }}
                                </div>
                            </x-tables.th>
                            <x-tables.th :no_border="true" class="font-normal text-center !p-0">
                                <div class="flex gap-3 items-center">
                                    @include('icons.money')
                                    {{ __('OPEX') }}
                                </div>
                            </x-tables.th>
                        </x-slot>
                        <x-tables.tr>
                            <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Total eligible and aligned') }}"
                                :data="$businessResume['eligibleAligned']" />
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-questionnaire.taxonomy.table-resume-volume
                                title="{{ __('Total eligible and not aligned') }}" :data="$businessResume['eligibleNotAligned']" color="#000" />
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Total eligible') }}"
                                :data="$businessResume['eligible']" color="#000" />
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Total not eligible') }}"
                                :data="$businessResume['notEligible']" color="#000" />
                        </x-tables.tr>
                        <x-tables.tr>
                            <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Grand total') }}"
                                :data="$businessResume['total']" bold="true" color="#000" />
                        </x-tables.tr>
                    </x-tables.table>
                </x-cards.card-dashboard-version1>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 mt-6 accordion-container">

            <x-accordian.taxonomie title="{!! __('1st step: KPIs') !!}" titleclass="text-esg16">
                <x-slot:icon>
                    @if (($this->safeguards['verified'] ?? true) === 0)
                        @include('icons.taxonomie.cancle')
                    @else
                        @if (
                            $businessResume['total']['volume']['value'] > 0 &&
                                $businessResume['total']['capex']['value'] > 0 &&
                                $businessResume['total']['opex']['value'] > 0)
                            @include('icons.taxonomie.checked', ['class' => 'text-esg5'])
                        @else
                            @include('icons.taxonomie.step1', ['class' => 'text-esg5'])
                        @endif
                    @endif
                </x-slot:icon>
                <x-slot:slot>
                    <div x-cloak>
                        <p class="text-base text-esg16">
                            {!! __(
                                'The first step is to inform the values of the main financial KPIs. This is base information that will be used to measure the impact of the next phases of the taxonomy exercise.',
                            ) !!}
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-5 mt-6">
                            <div>
                                <label class="text-esg8 text-base forn-normal"> {{ __('Year of reference:') }}
                                </label>
                                <span>
                                    {{ carbon()->parse($questionnaire['from'])->format('Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-5 mt-6">
                            <x-questionnaire.taxonomy.input-volumes title="{{ __('Business volume') }}"
                                placeholder="{{ __('Volume') }}" id="businessResume.total.volume.value"
                                readonly="{{ $taxonomy->completed ? 'true' : 'false' }}" type="number" />

                            <x-questionnaire.taxonomy.input-volumes title="{{ __('CAPEX') }}"
                                placeholder="{{ __('CAPEX') }}" id="businessResume.total.capex.value"
                                readonly="{{ $taxonomy->completed ? 'true' : 'false' }}" type="number" />

                            <x-questionnaire.taxonomy.input-volumes title="{{ __('OPEX') }}"
                                placeholder="{{ __('OPEX') }}" id="businessResume.total.opex.value"
                                readonly="{{ $taxonomy->completed ? 'true' : 'false' }}" type="number" />
                        </div>
                    </div>
                </x-slot:slot>
            </x-accordian.taxonomie>

            <x-accordian.taxonomie title="{!! __('2nd step: Minimum Safeguards') !!}" titleclass="text-esg16">
                <x-slot:icon>
                    @if (!isset($this->safeguards['verified']) || $this->safeguards['verified'] === null)
                        @include('icons.taxonomie.step2')
                    @elseif ($this->safeguards['verified'] === 0)
                        @include('icons.taxonomie.cancle')
                    @elseif ($this->safeguards['verified'] === 1)
                        @include('icons.taxonomie.checked')
                    @endif
                </x-slot:icon>
                <x-slot:slot>
                    <div x-cloak>

                        @if (!$taxonomy->safeguardIsImported)
                            @if (!isset($this->safeguards['verified']) || $this->safeguards['verified'] === null)
                                <p class="text-base text-esg16">
                                    {!! __(
                                        'Next, you should ensure that your company respects human rights as established by international standards and other social issues.',
                                    ) !!}
                                </p>

                                <p class="text-base text-esg16 mt-2">
                                    {!! __(
                                        'If you have already verified the Minimum Safeguards, it is possible to replace the exercise carried out on this portal with the statement of its result',
                                    ) !!}
                                </p>
                            @elseif ($this->safeguards['verified'] === 0)
                                <p class="text-base text-esg16">
                                    {!! __('According to the questionnaire you answered,') !!}
                                    <span class="text-[#F44336] font-bold">{!! __('not all of your company`s minimum safeguards topics have been verified.') !!}</span>
                                    {!! __(
                                        'You can add the activities for educational purposes, but it will not influence your company`s final result.',
                                    ) !!}
                                </p>

                                <p class="text-base text-esg16 mt-2">
                                    {!! __('If there are any errors or if you wish to redo the verification, select the "undo" option.') !!}
                                </p>
                            @elseif ($this->safeguards['verified'] === 1)
                                <p class="text-base text-esg16">
                                    {!! __('According to the questionnaire you have completed,') !!}
                                    <span class="text-esg5 font-bold">{!! __('your company`s compliance with all the minimum safeguards topics is verified.') !!}</span>
                                </p>

                                <p class="text-base text-esg16 mt-2">
                                    {!! __('If there are any errors or if you wish to redo the verification, select the "undo" option.') !!}
                                </p>
                            @endif
                        @else
                            <p class="text-base text-esg16">
                                {!! __('You have imported the minimum safeguards.') !!}
                            </p>

                            <p class="text-base text-esg16 mt-2">
                                {!! __('If there are any errors or if you wish to redo the verification, select the "undo" option') !!}
                            </p>
                        @endif

                        <div class="mt-4 flex items-center gap-5">

                            @if (!$taxonomy->completed)

                                @if ($taxonomy->safeguard['verified'] === null)
                                    <x-buttons.btn-icon-text
                                        class="!bg-esg4 !text-esg8 gap-2 !normal-case duration-300 hover:!bg-esg4/75 hover:!border-esg70"
                                        modal="questionnaires.taxonomy.modals.import-safeguard" :data="$taxonomyData">
                                        <x-slot name="buttonicon">
                                            @include('icons.upload', ['color' => color(8)])
                                        </x-slot>
                                        {{ __('Declare') }}
                                    </x-buttons.btn-icon-text>
                                @else
                                    <x-buttons.btn-icon-text
                                        class="!bg-esg4 !text-esg8 gap-2 !normal-case duration-300 hover:!bg-esg4/75 hover:!border-esg70"
                                        wire:click="resetSafeguards">
                                        <x-slot name="buttonicon">
                                            @include('icons.taxonomie.restore', [
                                                'color' => color(8),
                                            ])
                                        </x-slot>
                                        {{ __('Undo') }}
                                    </x-buttons.btn-icon-text>
                                @endif

                                @php
                                    $action = $taxonomy->safeguardIsImported ? 'javascript:void(0)' : route('tenant.taxonomy.safeguards', ['questionnaire' => $questionnaire->id]);
                                    $color = $taxonomy->safeguardIsImported ? 'bg-esg7 cursor-not-allowed' : 'bg-esg6 duration-300 hover:!bg-esg6/75';
                                @endphp
                                <a href="{{ $action }}">
                                    <x-buttons.btn-icon-text
                                        class="!text-esg4 text-center !normal-case {{ $color }}">
                                        <x-slot:buttonicon>
                                            <span class="mr-2">{{ __('Verify') }}</span>
                                        </x-slot:buttonicon>
                                        <x-slot:slot>
                                            @include('icons.arrow_right_round', ['color' => color(4)])
                                        </x-slot:slot>
                                    </x-buttons.btn-icon-text>
                                </a>
                            @endif
                        </div>
                    </div>
                </x-slot:slot>
            </x-accordian.taxonomie>

            <x-accordian.taxonomie title="{!! __('3rd step: Activities') !!}" titleclass="text-esg16">
                <x-slot:icon>
                    @if ($canSubmit)
                        @include('icons.taxonomie.checked')
                    @else
                        @include('icons.taxonomie.step3')
                    @endif
                </x-slot:icon>
                <x-slot:slot>
                    <div x-cloak>
                        <p class="text-base text-esg16">
                            {!! __(
                                'At this stage it is necessary to identify the activities carried out by the organisation and answer the questions that allow us to verify its alignment with the European taxonomy',
                            ) !!}
                        </p>

                        <p class="text-base text-esg16 mt-2">
                            {!! __(
                                'If you have already carried out the taxonomy exercise before, you can choose to import the results you already have.',
                            ) !!}
                        </p>

                        <p class="text-base text-esg16 mt-2">
                            {!! __('For each activity introduced, you will have to do:') !!}
                        </p>

                        <ol class="list-decimal mt-2 list-inside text-esg16">
                            <li>{!! __('Verification of substantial contribution to one of the environmental objectives') !!}</li>
                            <li>{!! __('Confirmation of no significant harm to the remaining objectives by the same activity.') !!}</li>
                        </ol>
                    </div>

                    @if (!$taxonomy->completed)
                        <div class="mt-6">

                            <div x-data="{ showCreateForm: false, showImportForm: false }" @taxonomy-imported.window="showImportForm = false"
                                @taxonomy-created.window="showCreateForm = false">
                                <div class="flex mt-5 gap-8" x-cloak>
                                    <button
                                        class="flex items-center gap-2 bg-white border-none rounded-md p-2 loadAccordion"
                                        @click="showCreateForm = !showCreateForm">
                                        @include('icons.plus', ['color' => color(5)])
                                        <span class="text-esg16 font-bold">{!! __('Add activity') !!}</span>
                                    </button>
                                    <button
                                        class="flex items-center gap-2 bg-white border-none rounded-md p-2 loadAccordion"
                                        @click="showImportForm = !showImportForm">
                                        @include('icons.import', ['color' => color(5)])
                                        <span class="text-esg16 font-bold">{!! __('Import activity') !!}</span>
                                    </button>
                                </div>

                                <div>
                                    <div x-cloak x-show="showCreateForm" class="transition duration-500 mt-2"
                                        x-transition:enter.duration.500ms x-transition:leave.duration.400ms>
                                        @livewire('questionnaires.taxonomy.modals.activity', ['questionnaire' => $questionnaire->id])
                                    </div>
                                </div>

                                <div>
                                    <div x-cloak x-show="showImportForm" class="transition duration-500 mt-2"
                                        x-transition:enter.duration.500ms x-transition:leave.duration.400ms>
                                        @livewire('questionnaires.taxonomy.modals.import-activity', ['questionnaire' => $questionnaire->id])
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif

                    @forelse($this->taxonomy->activities as $activity)
                        <div class="mt-6 border border-esg61 rounded" x-cloak>
                            <div class="bg-esg7/10 p-3">
                                <div class="flex gap-3 items-center justify-between">
                                    <div class="flex flex-row gap-2">
                                        <label class="font-bold">
                                            {{ $this->questionnaire->id }} - {{ $activity->identifier }}
                                        </label>
                                        <label class="font-bold">
                                            {{ $activity->name }}
                                        </label>
                                        @if ($questionnaire->completed_at == null)
                                            @php $data = json_encode(["activity" => $activity->id]); @endphp
                                            <x-buttons.trash modal="questionnaires.taxonomy.modals.delete-activity"
                                                :data="$data" class="pl-10 md:pl-0"
                                                data-test="delete-taxonomy-activity-btn" />
                                        @endif
                                    </div>
                                    <label
                                        class="font-bold uppercase text-xs p-[5px] {{ $activitiesNameLabels[$activity->id]['color'] }} rounded">{{ $activitiesNameLabels[$activity->id]['text'] }}
                                    </label>
                                </div>
                                @if (isset($activity->dnsh['imported']) && $activity->dnsh['imported'] === 1)
                                    <label class="text-xs font-normal">
                                        {{ __('Activity imported') }}
                                    </label>
                                @endif
                            </div>
                            <div class="px-4">

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-5 mt-6  pb-5">
                                    <div class="col-span-3">
                                        <p class="forn-normal text-esg8"> {{ __('Code and activity') }} </p>
                                        <div
                                            class="font-normal w-full inline-block align-middle mt-2 bg-esg7/10 pt-1.5 px-2 rounded-md text-esg8">
                                            <div>{{ $activity->sector->name }}</div>
                                        </div>
                                    </div>

                                    <x-questionnaire.taxonomy.input-volumes title="{{ __('Business volume') }}"
                                        placeholder="{{ __('Volume') }}"
                                        id="activitiesResume.{{ $activity->id }}.volume"
                                        readonly="{{ $taxonomy->completed ? 'true' : 'false' }}" type="number" />

                                    <x-questionnaire.taxonomy.input-volumes title="{{ __('CAPEX') }}"
                                        placeholder="{{ __('CAPEX') }}"
                                        id="activitiesResume.{{ $activity->id }}.capex"
                                        readonly="{{ $taxonomy->completed ? 'true' : 'false' }}" type="number" />

                                    <x-questionnaire.taxonomy.input-volumes title="{{ __('OPEX') }}"
                                        placeholder="{{ __('OPEX') }}"
                                        id="activitiesResume.{{ $activity->id }}.opex"
                                        readonly="{{ $taxonomy->completed ? 'true' : 'false' }}" type="number" />
                                </div>

                                <div class="border-b border-b-esg7/50">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6 pb-5">
                                    {{-- 1. Elegibilidade --}}
                                    <div
                                        class="flex items-center gap-3 p-3 border border-esg6/10 rounded-md justify-center ">
                                        @if (($this->safeguards['verified'] ?? true) === 0)
                                            @include('icons.taxonomie.cancle')
                                        @else
                                            @include('icons.taxonomie.checked')
                                        @endif
                                        <span class="text-lg text-esg16">1. {{ __('Eligibility') }}</span>
                                    </div>

                                    {{-- 2. Substantial contribute --}}
                                    <div>
                                        @if ($this->substancialContribute[$activity->id]['verified'] === null)
                                            <div
                                                class="flex items-center gap-3 p-4 border border-esg6/10 rounded-md justify-center ">
                                                @include('icons.taxonomie.activity2')
                                                <span class="text-lg text-esg16">2.
                                                    {{ __('Substantial contribute') }}
                                                </span>
                                                <a class="cursor-pointer"
                                                    href="{{ !$taxonomy->completed
                                                        ? route('tenant.taxonomy.substantial', [
                                                            'questionnaire' => $questionnaire->id,
                                                            'code' => $activity->id,
                                                        ])
                                                        : 'javascript:void(0)' }}">@include('icons.arrow_right_round', [
                                                        'color' => color(16),
                                                        'width' => 25,
                                                        'height' => 24,
                                                    ])
                                                </a>
                                            </div>
                                        @else
                                            <div class="accordion-child">
                                                <x-accordian.taxonomie title="{!! __('2. Substantial contribute') !!}"
                                                    titleclass="text-esg16">
                                                    <x-slot:icon>
                                                        @if (($this->safeguards['verified'] ?? true) === 0)
                                                            @include('icons.taxonomie.cancle')
                                                        @else
                                                            @if ($this->substancialContribute[$activity->id]['verified'] === null)
                                                                <label class="text-lg pt-4"> - </label>
                                                            @elseif ($this->substancialContribute[$activity->id]['verified'] === 0)
                                                                @include('icons.taxonomie.cancle')
                                                            @elseif ($this->substancialContribute[$activity->id]['verified'] === 1)
                                                                @include('icons.taxonomie.checked')
                                                            @endif
                                                        @endif
                                                    </x-slot:icon>
                                                    <x-slot:slot>
                                                        <div class="rounded-md min-h-[135px] h-auto">
                                                            <div class="grid grid-cols-1">
                                                                <div
                                                                    class="p-4 flex flex-col items-center min-h-[92px] ">
                                                                    @foreach ($this->substancialContribute[$activity->id]['data'] as $data)
                                                                        @if ($data['verified'] !== null && $data['percentage'] > 0)
                                                                            <label
                                                                                class="text-4xl font-bold {{ $data['verified'] === 1 ? 'text-[#1ECD51]' : 'text-[#fd4120]' }} mr-2">
                                                                                {{ $data['percentage'] }} %
                                                                            </label>
                                                                            <label class="text-base text-esg16">
                                                                                {{ translateJson($data['name']) }}
                                                                            </label>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if (!isset($activity->dnsh['imported']) || $activity->dnsh['imported'] === 0)
                                                        <div
                                                            class="grid grid-cols-{{ $taxonomy->completed ? 1 : 2 }} gap-5 place-content-center items-center">
                                                                @if(!$taxonomy->completed)
                                                                <x-buttons.btn-icon-text
                                                                    class="!bg-esg4 !text-esg8 !flex !justify-center gap-2 !w-full  duration-300 hover:!bg-[#E0F5E3] hover:!border-[#6DA979]"
                                                                    wire:click="resetContribute({{ $activity->id }})">
                                                                    <x-slot name="buttonicon">
                                                                        @include(
                                                                            'icons.taxonomie.restore',
                                                                            [
                                                                                'color' => color(8),
                                                                            ]
                                                                        )
                                                                    </x-slot>
                                                                    {{ __('Undo') }}
                                                                </x-buttons.btn-icon-text>
                                                                @endif

                                                                <x-buttons.a-icon
                                                                    class="bg-esg6 text-center !rounded-md !p-0 h-8 disabled:cursor-not-allowed !inline-block !w-full duration-300 hover:!bg-opacity-80"
                                                                    text="{{ $this->substancialContribute[$activity->id]['verified'] !== null ? 'Ver' : __('Enter') }}"
                                                                    href="{{ !$taxonomy->completed
                                                                        ? route('tenant.taxonomy.substantial', [
                                                                            'questionnaire' => $questionnaire->id,
                                                                            'code' => $activity->id,
                                                                        ])
                                                                        : 'javascript:void(0)' }}"
                                                                    disabled="{{ $taxonomy->completed ? 'true' : 'false' }}">
                                                                    <div
                                                                        class="flex items-center gap-2 w-full place-content-center py-1.5 text-sm font-medium text-esg4">
                                                                        {{ __('View') }}

                                                                        @include(
                                                                            'icons.arrow_right_round',
                                                                            [
                                                                                'color' => color(4),
                                                                            ]
                                                                        )
                                                                    </div>
                                                                </x-buttons.a-icon>
                                                            </div>
                                                        @endif
                                                    </x-slot:slot>
                                                </x-accordian.taxonomie>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- 3. No significant harm --}}
                                    @php
                                        $npsIsReadyToAnswer = false;
                                        if (isset($this->substancialContribute[$activity->id]['verified']) && $this->substancialContribute[$activity->id]['verified'] === 1 && $activity->hasNps) {
                                            $npsIsReadyToAnswer = true;
                                        }
                                    @endphp
                                    <div>
                                        @if ($this->npsObjectives[$activity->id]['verified'] === null)
                                            <div
                                                class="flex items-center gap-3 p-4 border border-esg6/10 rounded-md justify-center ">
                                                @include('icons.taxonomie.glob')
                                                <span class="text-lg text-esg16">3.
                                                    {{ __('NPS') }}</span>
                                                <a class="cursor-pointer"
                                                    href="{{ $npsIsReadyToAnswer
                                                        ? route('tenant.taxonomy.dnsh', [
                                                            'questionnaire' => $questionnaire->id,
                                                            'code' => $activity->id,
                                                        ])
                                                        : 'javascript:void(0)' }}">
                                                    @include('icons.arrow_right_round', [
                                                        'color' => $npsIsReadyToAnswer ? color(16) : '#E1E6EF',
                                                        'width' => 25,
                                                        'height' => 24,
                                                    ])
                                                </a>
                                            </div>
                                        @else
                                            <div class="accordion-child">
                                                <x-accordian.taxonomie title="{!! __('NPS') !!}"
                                                    titleclass="text-esg16" defaulthide="false">
                                                    <x-slot:icon>
                                                        @if (($this->safeguards['verified'] ?? true) === 0)
                                                            @include('icons.taxonomie.cancle')
                                                        @else
                                                            @if ($this->npsObjectives[$activity->id]['verified'] === null)
                                                                <label class="text-lg pt-4"> - </label>
                                                            @elseif ($this->npsObjectives[$activity->id]['verified'] === 0)
                                                                @include('icons.taxonomie.cancle')
                                                            @elseif ($this->npsObjectives[$activity->id]['verified'] === 1)
                                                                @include('icons.taxonomie.checked')
                                                            @endif
                                                        @endif
                                                    </x-slot:icon>
                                                    <x-slot:slot>
                                                        <div class="">
                                                            <div class="grid grid-cols-1">
                                                                <div>
                                                                    @if ($this->npsObjectives[$activity->id]['verified'] === 0)
                                                                        <p class="text-base text-esg16">
                                                                            {{ __('According to the completed questionnaire, we found that the activity') }}
                                                                            <span
                                                                                class="font-extrabold text-[#F44336]">{{ __('significantly harms') }}</span>
                                                                            {{ __('one or more environmental objectives.') }}
                                                                        </p>
                                                                    @elseif ($this->npsObjectives[$activity->id]['verified'] === 1)
                                                                        <p class="text-base text-esg16">
                                                                            {{ __('According to the completed questionnaire, we found that the activity') }}
                                                                            <span
                                                                                class="font-extrabold text-[#4BAE4F]">{{ __('does not significantly harm') }}</span>
                                                                            {{ __('other environmental objectives.') }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                                @if (!isset($activity->dnsh['imported']) || $activity->dnsh['imported'] === 0)
                                                                    <div
                                                                        class="grid grid-cols-{{ $taxonomy->completed ? 1 : 2 }} gap-5 place-content-center items-center mt-4">
                                                                        @if(!$taxonomy->completed)
                                                                        <div class="">
                                                                            <x-buttons.btn-icon-text
                                                                                class="!bg-esg4 !text-esg8 !flex !justify-center !normal-case gap-2 !w-full"
                                                                                wire:click="resetNps({{ $activity->id }})">
                                                                                <x-slot name="buttonicon">
                                                                                    @include(
                                                                                        'icons.taxonomie.restore',
                                                                                        ['color' => color(8)]
                                                                                    )
                                                                                </x-slot>
                                                                                {{ __('Undo') }}
                                                                            </x-buttons.btn-icon-text>
                                                                        </div>
                                                                        @endif
                                                                        <div class="">
                                                                            <x-buttons.a-icon
                                                                                class="bg-esg5 text-center !rounded-md !p-0 h-8 disabled:cursor-not-allowed !inline-block !w-full duration-300 hover:!bg-opacity-80"
                                                                                text="{{ $this->substancialContribute[$activity->id]['verified'] !== null ? 'Ver' : __('Enter') }}"
                                                                                href="{{ !$taxonomy->completed
                                                                                    ? route('tenant.taxonomy.dnsh', [
                                                                                        'questionnaire' => $questionnaire->id,
                                                                                        'code' => $activity->id,
                                                                                    ])
                                                                                    : 'javascript:void(0)' }}"
                                                                                disabled="{{ $taxonomy->completed ? 'true' : 'false' }}">
                                                                                <div
                                                                                    class="flex items-center gap-2 w-full place-content-center py-1.5 text-sm font-medium text-esg4">
                                                                                    {{ __('View') }}

                                                                                    @include(
                                                                                        'icons.arrow_right_round',
                                                                                        [
                                                                                            'color' => color(4),
                                                                                        ]
                                                                                    )
                                                                                </div>
                                                                            </x-buttons.a-icon>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </x-slot:slot>
                                                </x-accordian.taxonomie>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mt-7">
                            <p class="text-center text-sm text-esg8 tracking-wide">
                                {{ __('Add an activity to view the information') }}
                            </p>
                        </div>
                    @endforelse
                </x-slot:slot>
            </x-accordian.taxonomie>

        </div>

        <div class="flex place-content-center mt-14">
            @php $data = json_encode(["taxonomy" => $taxonomy->id]); @endphp
            @if ($taxonomy->completed)
                <x-buttons.a
                    class="bg-esg6 !rounded-md !p-2 h-8 w-72 !block text-center disabled:cursor-not-allowed duration-300 hover:bg-esg6/75"
                    text="{{ __('Back') }}" href="{{ route('tenant.questionnaires.panel') }}" />
            @elseif ($canSubmit)
                <x-buttons.btn
                    class="bg-esg6 !rounded-md !p-2 h-8 w-72 !block text-center disabled:cursor-not-allowed duration-300 hover:bg-esg6/75"
                    text="{{ __('Submit taxonomy') }}" modal="questionnaires.taxonomy.modals.submit"
                    :data="$data" />
            @endif
        </div>
    </div>

    <label class="hidden bg-esg6/10"></label>
    <label class="hidden bg-esg7/10"></label>
</div>


@push('body')
    <script nonce="{{ csp_nonce() }}">
        window.livewire.on('resetInputField', () => {
            var select = document.getElementsByClassName('tomselected');
            // loop through all elements with class "tomselected"
            for (var i = 0; i < select.length; i++) {
                // get the instance of Tom Select
                var instance = select[i].tomselect;
                // clear the selected items
                instance.clear();
            }
        });
    </script>
@endpush
