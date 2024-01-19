@push('head')
    <style nonce="{{ csp_nonce() }}">
        @media print {

            * {
                background-color: transparent !important;
                color: black !important;
                font-size: 98% !important;
            }

            p {
                font-size: 98% !important;
            }

            #launcher,
            #footer {
                visibility: hidden;
            }

            .page {
                margin: 1cm;
            }

            .no-print {
                display: none !important;
            }

            div {
                page-break-after: avoid;
                break-after: avoid !important;
            }

            .avoid-page-break {
                page-break-after: avoid;
                break-after: avoid !important;
            }

            .break-after-page {
                page-break-after: always !important;
                break-after: always !important;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
@endpush

<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Taxonomy - ' . $questionnaire->company->name) }}" data-test="taxonomy-header"
            click="{{ route('tenant.questionnaires.panel') }}" class="!bg-esg4 no-print" textcolor="text-esg5">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="pb-20 mt-10 print:mt-10">
        <div class="avoid-page-break">
            @php $text = json_encode([__('Summary')]); @endphp

            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!p-6 !h-auto !border-[#E1E6EF]"
                titleclass='!text-lg !font-bold !leading-9 text-esg6' contentplacement='place-content-stretch'>
                <x-tables.table class="text-base !min-w-full mt-10">
                    <x-tables.thead.tr>
                        <x-tables.th :no_border="true"></x-tables.th>
                        <x-tables.th :no_border="true" class="font-normal text-center">
                            <div class="flex gap-3 items-center">
                                @include('icons.up-trand')
                                {{ __('Business volume') }}
                            </div>
                        </x-tables.th>
                        <x-tables.th :no_border="true" class="font-normal text-center">
                            <div class="flex gap-3 items-center">
                                @include('icons.money')
                                {{ __('CAPEX') }}
                            </div>
                        </x-tables.th>
                        <x-tables.th :no_border="true" class="font-normal text-center">
                            <div class="flex gap-3 items-center">
                                @include('icons.money')
                                {{ __('OPEX') }}
                            </div>
                        </x-tables.th>
                    </x-tables.thead.tr>

                    <x-tables.tr class="bg-esg59">
                        <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Total eligible and aligned') }}"
                            :data="$this->taxonomy->summary['eligibleAligned']" />
                    </x-tables.tr>

                    <x-tables.tr>
                        <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Total eligible and unaligned') }}"
                            :data="$this->taxonomy->summary['eligibleNotAligned']" color="#000" />
                    </x-tables.tr>

                    <x-tables.tr class="bg-esg59">
                        <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Total eligible') }}"
                            :data="$this->taxonomy->summary['eligible']" color="#000" />
                    </x-tables.tr>


                    <x-tables.tr>
                        <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Total ineligible') }}"
                            :data="$this->taxonomy->summary['notEligible']" color="#000" />
                    </x-tables.tr>

                    <x-tables.tr>
                        <x-questionnaire.taxonomy.table-resume-volume title="{{ __('Grand total') }}" :data="$this->taxonomy->summary['total']"
                            bold="true" color="#000" />
                    </x-tables.tr>

                </x-tables.table>
            </x-cards.card-dashboard-version1>
        </div>

        <div>
            @foreach ($activities as $activity)
                <div class="!bg-[#FAF9FB] rounded-sm mt-6 pb-8">
                    <div class="p-4 flex gap-5 items-center border-b border-b-esg7/30 px-6 py-4">
                        <label class="text-esg6 text-lg font-bold"> {{ $activity->name }}</label>
                        <label
                            class="text-esg4 {{ $activity->activity_report_status_color }} py-1 px-4 rounded-md text-sm">
                            {{ $activity->activity_report_status }}
                        </label>
                    </div>

                    <div class="px-6 py-3 text-sm">
                        <div class="">
                            <div class="flex gap-3 items-center">
                                <label class="font-bold"> {{ __('Sector:') }} </label>
                                <label class="font-normal"> {{ $activity->sector->parent->name }} </label>

                            </div>
                            <div class="flex gap-3 items-center mt-2">
                                <label class="font-bold"> {{ __('Activity:') }} </label>
                                <label class="font-normal"> {{ $activity->sector->name }} </label>
                            </div>
                            <div class="flex gap-3 items-center mt-2">
                                <label class="font-bold"> {{ __('Code:') }} </label>
                                <label class="font-normal"> {{ $activity->code }} </label>
                            </div>
                            <div class="flex gap-3 items-start mt-2">
                                <label class="font-normal"> <label class="font-bold"> {{ __('Description:') }} </label>
                                    {{ $activity->description }}
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-5 items-center mt-6 ">
                            <div class="flex gap-1 items-center place-content-center text-base text-esg8">
                                @include('icons.up-trand')
                                {{ __('Business volume') }}
                                : <x-currency :value="$activity->summary['volume']['value']" currency="€" />
                            </div>

                            <div class="flex gap-1 items-center place-content-center text-base text-esg8">
                                @include('icons.money')
                                {{ __('CAPEX') }}
                                : <x-currency :value="$activity->summary['capex']['value']" currency="€" />
                            </div>

                            <div class="flex gap-1 items-center place-content-center text-base text-esg8">
                                @include('icons.money')
                                {{ __('OPEX') }}
                                : <x-currency :value="$activity->summary['opex']['value']" currency="€" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 print:grid-cols-3 gap-5 mt-6">

                            <x-cards.card-chart title="{!! __('Proportion of business volume') !!}">
                                <x-charts.chartjs id="chart_volume_{{ $activity->id }}" class="" width="350"
                                    height="150" x-init="$nextTick(() => {
                                        tenantDoughnutChart(
                                            ['Volume', 'Total'],
                                            [{{ $activity->summary['volume']['percentage'] }}, {{ max(0, 100 - $activity->summary['volume']['percentage']) }}],
                                            'chart_volume_{{ $activity->id }}',
                                            ['{{ color(5) }}', '#F5F5F5'], {
                                                showTooltips: false,
                                                legend: {
                                                    display: false,
                                                },
                                                plugins: {
                                                    legendOnCenter: true,
                                                }
                                            }
                                        );
                                    });" />
                            </x-cards.card-chart>

                            <x-cards.card-chart title="{!! __('Proportion of CAPEX') !!}">
                                <x-charts.chartjs id="chart_capex{{ $activity->id }}" class="" width="350"
                                    height="150" x-init="$nextTick(() => {
                                        tenantDoughnutChart(
                                            ['Capex', 'Total'],
                                            [{{ $activity->summary['capex']['percentage'] }}, {{ max(0, 100 - $activity->summary['capex']['percentage']) }}],
                                            'chart_capex{{ $activity->id }}',
                                            ['{{ color(5) }}', '#F5F5F5'], {
                                                showTooltips: false,
                                                legend: {
                                                    display: false,
                                                },
                                                plugins: {
                                                    legendOnCenter: true,
                                                }
                                            }
                                        );
                                    });" />
                            </x-cards.card-chart>

                            <x-cards.card-chart title="{!! __('Proportion of OPEX') !!}">
                                <x-charts.chartjs id="chart_opex{{ $activity->id }}" class="" width="350"
                                    height="150" x-init="$nextTick(() => {
                                        tenantDoughnutChart(
                                            ['OPEX', 'Total'],
                                            [{{ $activity->summary['opex']['percentage'] }}, {{ max(0, 100 - $activity->summary['opex']['percentage']) }}],
                                            'chart_opex{{ $activity->id }}',
                                            ['{{ color(5) }}', '#F5F5F5'], {
                                                showTooltips: false,
                                                legend: {
                                                    display: false,
                                                },
                                                plugins: {
                                                    legendOnCenter: true,
                                                }
                                            }
                                        );
                                    });" />
                            </x-cards.card-chart>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 print:grid-cols-2 gap-5 mt-6 print:pt-10">

                            <x-cards.card-chart title="{!! __('Alignment of activity with Taxonomy') !!}">
                                <x-charts.chartjs id="chart_aligned_{{ $activity->id }}" class="" width="350"
                                    height="150" x-init="$nextTick(() => {
                                        tenantDoughnutChart(
                                            ['Aligned', 'Total'],
                                            [{{ $activity->AlignedTaxonomyPercentage }}, {{ 100 - $activity->AlignedTaxonomyPercentage }}],
                                            'chart_aligned_{{ $activity->id }}',
                                            ['{{ color(5) }}', '#F5F5F5'], {
                                                showTooltips: false,
                                                legend: {
                                                    display: false,
                                                },
                                                plugins: {
                                                    legendOnCenter: true,
                                                }
                                            }
                                        );
                                    });" />
                            </x-cards.card-chart>

                            <div class="py-4 px-5 bg-esg4 rounded-sm border border-[#E1E6EF]">
                                <label class=""> {{ __('Alignment of activity with Taxonomy') }} </label>

                                <div class="mt-4">
                                    @foreach ($substancialContribute[$activity->id] as $data)
                                        <div class="flex justify-between mt-2">
                                            @if ($data['verified'] === 1)
                                                <label class="text-xs">
                                                    {{ translateJson($data['name']) }}
                                                </label>
                                                <label
                                                    class="text-[#1ECD51] text-xs font-extrabold">{{ $data['percentage'] }}%</label>
                                            @elseif ($data['verified'] === 0)
                                                <label class="text-xs">
                                                    {{ translateJson($data['name']) }}
                                                </label>
                                                <label
                                                    class="text-red-500 text-xs font-extrabold">{{ $data['percentage'] }}%</label>
                                            @endif
                                        </div>
                                    @endforeach

                                    <div>
                                        @foreach ($npsObjectives[$activity->id] ?? [] as $objective)
                                            <div class="flex justify-between mt-2">
                                                <label class="text-xs">
                                                    {{ translateJson($objective['name']) }}
                                                </label>
                                                @if ($objective['verified'] === 1)
                                                    <label class="text-[#1ECD51] text-xs font-extrabold">
                                                        {{ __('Y') }}
                                                    </label>
                                                @elseif($objective['verified'] === 0)
                                                    <label class="text-red-500 text-xs font-extrabold">

                                                        {{ __('N') }}
                                                    </label>
                                                @else
                                                    <label class="text-[#1ECD51] text-xs font-extrabold">
                                                        -
                                                    </label>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="flex justify-between mt-2">
                                        <label class="text-xs"> {{ __('Minimum Safeguards') }} </label>

                                        @if (!isset($this->safeguards['verified']))
                                            <label class="text-[#1ECD51] text-xs font-extrabold"> - </label>
                                        @else
                                            @if (!isset($this->safeguards['verified']) && $this->safeguards['verified'] === null)
                                                <label class="text-[#1ECD51] text-xs font-extrabold"> - </label>
                                            @elseif ($this->safeguards['verified'] === 0)
                                                <label
                                                    class="text-red-500 text-xs font-extrabold">{{ __('N') }}</label>
                                            @elseif ($this->safeguards['verified'] === 1)
                                                <label
                                                    class="text-[#1ECD51] text-xs font-extrabold">{{ __('Y') }}</label>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex gap-5 items-center place-content-center mt-8 no-print">
            <x-buttons.a-icon
                class="!bg-esg4 !text-esg8 !border w-80 !rounded-md !p-2 !text-center !pl-5 cursor-pointer"
                href="{{ route('tenant.taxonomy.report.table', ['questionnaire' => $questionnaire]) }}">
                <div class="flex gap-3 items-center place-content-center">
                    @include('icons.download')
                    <label class="ml-4 cursor-pointer">{{ __('Download report table') }}</label>
                </div>
            </x-buttons.a-icon>

            <x-buttons.a-icon class="bg-esg6 !text-esg4 !border w-80 !rounded-md !p-2 !text-center !pl-5"
                @click="window.print()">
                <div class="flex gap-3 items-center place-content-center">
                    @include('icons.download', ['color' => color(4)])
                    <label class="cursor-pointer">{{ __('Download full report') }}</label>
                </div>
            </x-buttons.a-icon>
        </div>

    </div>
</div>
