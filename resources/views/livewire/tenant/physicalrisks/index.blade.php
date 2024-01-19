@push('body')
    <style nonce="{{ csp_nonce() }}">
        @media print {

            #launcher,
            #footer {
                visibility: hidden;
            }

            .print\:hidden {
                display: none;
            }

            button.submit {
                display: none;
            }

            body {
            margin: 0;
            padding: 0;
            color: black;
            background: white;
            -webkit-filter: grayscale(100%);
            filter: grayscale(100%);
        }

            @page {
                size: A4;
                margin: 0pt;
                padding: 0;
            }
        }

        @page {
            size: A4;
            margin: 0pt;
            padding: 0;
        }
    </style>
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener("closeForm", event => {
            document.getElementById('addForm').click();
        });
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

<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Physical Risks') }}" data-test="physicalrisk-header"
            click="{{ route('tenant.questionnaires.panel') }}" class="!bg-esg4 print:hidden" iconcolor="text-esg5"
            textcolor="text-esg5">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="{{ !$isReport ? 'pb-20 ' : '' }}" x-data="{ open: false }">
        <div class="whitespace-normal print:hidden text-esg8">
            <p>
                {!! __(
                    "The company must add and fill in the data for a geography for each geographical area in which it has a significant part of its activity. So, for example, if a company has warehouses in Ourém and an industrial unit in Aveiro, it must fill in these two geographies. Because the importance of each of these geographies can be different, the relevance of each geography to the company's overall value or activity should be indicated using the following scale: Critical; Very Relevant, Relevant, Not Very Relevant.",
                ) !!}
            </p>
            <p class="mt-3">
                {!! __(
                    'The sectors to be indicated are the sectors of operation in each of the geographies if the company operates in more than one sector. If the company only operates in one sector, that is the one to indicate.',
                ) !!}
            </p>
            <p class="mt-3">
                {!! __(
                    'The system automatically indicates the risks associated with each geography, however, since the risks are determined automatically by the municipality and the specific situation within the same municipality can be different, the company can change the physical risks that are determined if in its opinion they are different, leaving a short explanation for the change.',
                ) !!}
            </p>
        </div>

        <div class="mt-10 flex gap-5 items-center justify-between print:hidden">
            <div class="flex gap-5 items-center">

                @if ($isReport)
                    <a href="{{ $questionnaire->questionnaireScreen() }}">
                        @include('icons.back', [
                            'color' => '#44724D',
                            'width' => '20',
                            'height' => '16',
                        ])
                    </a>
                @endif

                <div class="text-2xl font-bold text-esg6"> {!! __('Geographies') !!} </div>

                @if (!$isReport && $questionnaire->completed_at == null)
                    <div class="">
                        <x-buttons.btn-icon-text id="addForm" class="!bg-esg4 !text-esg8" x-on:click="open = ! open">
                            <x-slot name="buttonicon">
                                @include('icons.tables.plus', [
                                    'color' => color(8),
                                    'width' => 12,
                                    'height' => 12,
                                ])
                            </x-slot>
                            <span class="ml-2 text-esg8">{!! __('Add') !!}</span>
                        </x-buttons.btn-icon-text>
                    </div>
                @endif
            </div>

            @if ($questionnaire->completed_at != null)
                <div class="flex gap-5 items-center">
                    @if (!$isReport)
                        <x-buttons.a class="!bg-esg4 !text-esg8 !border !border-esg7 !rounded print:hidden"
                            href="{{ $reportUrl }}" text="{{ __('Report') }}" />
                    @else
                        <x-buttons.a class="!bg-esg4 !text-esg8 !border !border-esg7 !rounded print:hidden"
                            href="#" x-on:click="window.print()" text="{{ __('Print') }}" />
                    @endif
                </div>
            @endif
        </div>

        <div class="border border-esg7/30 p-4 mt-5 rounded-md " x-show="open" @close.window="open = false" x-cloak>
            @livewire('questionnaires.physicalrisks.modals.form', ['questionnaire' => $questionnaire->id])
        </div>

        <div class="border-t border-t-esg7/20 my-5"></div>

        <div class="print:pt-5">
            @if ($isReport)
                <div class="border border-esg16/20 rounded-md mt-10 py-4 px-6">
                    <p class="text-esg6 text-lg font-bold">{!! __('Summary') !!}</p>
                    <div class="mt-4">
                        <x-tables.table class="!w-full !min-w-full">
                            <x-slot name="thead">
                                <x-tables.th
                                    class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center ">&nbsp;</x-tables.th>
                                <x-tables.th
                                    class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center ">{!! __('Activity') !!}</x-tables.th>
                                <x-tables.th
                                    class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center ">{!! __('Risk summary') !!}</x-tables.th>
                                <x-tables.th
                                    class="!border-y-esg7/40 text-esg6 text-sm pb-2 font-normal text-center">{!! __('Relevance') !!}</x-tables.th>
                            </x-slot>

                            @foreach ($physicalRisks as $physicalrisk)
                                <x-tables.tr>
                                    <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                        <p class="text-base font-bold text-esg8">{{ $physicalrisk->name }}</p>
                                        <div class="flex items-center gap-2">
                                            @include('icons.location-v2')
                                            <label class="text-sm text-esg8">
                                                {{ $physicalrisk->fullAddress }}
                                            </label>
                                        </div>
                                    </x-tables.td>
                                    <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-esg8 items-center ">
                                        {{ $physicalrisk->businessActivities->name }}
                                    </x-tables.td>
                                    <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                        <div class="flex items-center gap-2 print:grid print:grid-cols-6 text-esg8">
                                            @foreach ($physicalrisk->hazards as $hazard)
                                                <div class="w-8">
                                                    @include(
                                                        'icons.physical_risks.' . strtolower($hazard['name_slug']),
                                                        [
                                                            'fill' => $hazard['enabled']
                                                                ? getRiskLevelColor($hazard['risk_slug'], 'none')
                                                                : '#B1B1B1',
                                                        ]
                                                    )
                                                </div>
                                            @endforeach
                                        </div>
                                    </x-tables.td>
                                    <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                        <p class="text-base font-extrabold text-black text-center">
                                            {{ str_replace('_', ' ', $physicalrisk->relevant) }}</p>
                                    </x-tables.td>
                                </x-tables.tr>
                            @endforeach
                        </x-tables.table>
                    </div>
                </div>
            @endif
        </div>

        @forelse ($physicalRisks as $physicalrisk)
            <div class="mt-6 print:mt-0 print:p-10">
                <div class="">
                    <div class="flex gap-3 items-center">
                        <label class="font-bold text-esg8">
                            {{ $physicalrisk->name }}
                        </label>

                        <div class="text-xs capitalize bg-esg7/40 rounded px-2 py-0.5 text-esg8">
                            {!! __('Relevance') !!} : {{ $physicalrisk->relevantLabel }}
                        </div>

                        @if (!$isReport && $questionnaire->completed_at == null)
                            <div>
                                @php $editdata = json_encode(["questionnaire" => $questionnaire->id, "physicalRisks" => $physicalrisk]); @endphp
                                <x-buttons.edit modal="questionnaires.physicalrisks.modals.form" class="cursor-pointer"
                                    :param="json_encode(['color' => color(5), 'width' => 14, 'height' => 14])" :data="$editdata" />

                                @php $data = json_encode(["physicalRisks" => $physicalrisk]); @endphp
                                <x-buttons.trash modal="questionnaires.physicalrisks.modals.delete-geography"
                                    class="pl-5 md:pl-0" :param="json_encode(['stroke' => color(16)])" data-test="delete-phisicalrisks-btn"
                                    :data="$data" />
                            </div>
                        @endif
                    </div>
                    @if ($physicalrisk->note != '')
                        <div
                            class="text-xs capitalize bg-esg7/10 border-esg7/20 rounded px-2 py-1 w-auto mt-2 text-esg8">
                            {{ __('Note') }}: {{ $physicalrisk->note }}
                        </div>
                    @endif
                </div>
                <div class="my-6">
                    <div class="flex gap-2 items-center">
                        <div class="flex gap-3 items-center">
                            @include('icons.location-v2', ['color' => color(5), 'width' => 16, 'height' => 18])
                            <label class="text-base text-esg8">
                                {{ $physicalrisk->address->name ?? '' }}
                            </label>
                        </div>
                        @if ($physicalrisk->location)
                            <div class="flex flex-row gap-1 items-center">
                                <div class="border-l-2 border-gray-200 px-2">
                                    <label class="text-base text-esg8">{{ $physicalrisk->location->country_name }}</label>
                                </div>
                                <div class="border-l-2 border-gray-200 px-2">
                                    <label class="text-base text-esg8">{{ $physicalrisk->location->region_name }}</label>
                                </div>
                                <div class="border-l-2 border-gray-200 px-2">
                                    <label class="text-base text-esg8">{{ $physicalrisk->location->city_name }}</label>
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-2 items-center pl-3">
                            @include('icons.briefcase', [
                                'color' => color(5),
                                'width' => 17,
                                'height' => 14,
                            ])
                            <label class="text-base text-esg8">
                                {{ $physicalrisk->businessActivities->name ?? '' }}
                            </label>
                        </div>
                    </div>
                </div>

                <x-cards.physical_risks.list :hazards="$physicalrisk->hazards" :physicalrisk="$physicalrisk" :audits="$audits" :isReport="$isReport"
                    :questionnaire="$questionnaire" />
            </div>
        @empty
            <div class="flex justify-center items-center p-6">
                <h3 class="w-fit text-md">
                    {{ __('No geographies available yet. Click the “Add” button to create a new one.') }}</h3>
            </div>
        @endforelse

        @if (!$isReport && count($physicalRisks) > 0 && $questionnaire->completed_at == null)
            <div class="mb-10 mt-6 grid place-content-center">
                <x-buttons.btn class="!px-6 !py-2" text="{!! __('Submit') !!}" wire:click="submit()" />
            </div>
        @endif

    </div>
</div>
