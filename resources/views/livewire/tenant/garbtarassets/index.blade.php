@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            barCharts(
                {!! json_encode([[__('Assets subject to taxonomic analysis with taxonomic info')], __('Assets subject to taxonomic analysis without taxonomic info'), __('Assets not subject to taxonomic analysis')]) !!},
                [
                    {!! json_encode(
                        [
                            'data' => [$assets_with_taxonomy, $assets_without_taxonomy, $excludedNumerator],
                            'backgroundColor' => color(5)
                        ]
                    ) !!}
                ],
                'assets_taxonomy_specific_purpose_new',
                [],
                '',
                'multiple',
                '{!! auth()->user()->locale !!}',
                '{!! tenant()->get_default_currency !!}',
                15
            );

            barCharts(
                {!! json_encode(['Covered by GAR', 'Covered by BTAR']) !!},
                [
                    {!! json_encode(
                        [
                            'data' => [$assetWithDataGAR, $assetWithDataBTAR], 'backgroundColor' => color(5)
                        ]
                    ) !!},
                    {!! json_encode(
                        [
                            'data' => [$assetWithoutDataGAR, $assetWithoutDataBTAR], 'backgroundColor' => color(13)
                        ]
                    ) !!}],
                'assets_relevance_distribution',
                [],
                '',
                'multiple',
                '{!! auth()->user()->locale !!}',
                '{!! tenant()->get_default_currency !!}',
            );

            barCharts(
                {!! json_encode([
                    __('Credit institutions'),
                    __('Investment companies'),
                    __('Management Companies'),
                    __('Insurance companies'),
                    __('Non-financial companies'),
                    __('Loans to households'),
                    __('Loans to the local public sector'),
                    __('Residential and commercial real estate obtained by acquiring ownership'),
                ]) !!},
                [
                    {!! json_encode([
                        'data' => [
                            $garType[0],
                            $garType[1],
                            $garType[2],
                            $garType[3],
                            $garType[4],
                            $garType[5],
                            $garType[6],
                            $garType[7],
                        ],
                        'backgroundColor' => color(5),
                    ]) !!},
                    {!! json_encode([
                        'data' => [
                            $garTypeNoData[0],
                            $garTypeNoData[1],
                            $garTypeNoData[2],
                            $garTypeNoData[3],
                            $garTypeNoData[4],
                            $garTypeNoData[5],
                            $garTypeNoData[6],
                            $garTypeNoData[7],
                        ],
                        'backgroundColor' => color(13),
                    ]) !!},
                ],
                'assets_covered_gar',
                [],
                '',
                'multiple',
                '{!! auth()->user()->locale !!}',
                '{!! tenant()->get_default_currency !!}',
                10
            );

            barCharts(
                {!! json_encode([
                    __('Credit institutions'),
                    __('Investment companies'),
                    __('Management Companies'),
                    __('Insurance companies'),
                    __('Non-financial companies'),
                    __('Loans to households'),
                    __('Loans to the local public sector'),
                    __('Residential and commercial real estate obtained by acquiring ownership'),
                ]) !!},
                [
                    {!! json_encode([
                        'data' => [
                            $btarType[0],
                            $btarType[1],
                            $btarType[2],
                            $btarType[3],
                            $btarType[4],
                            $btarType[5],
                            $btarType[6],
                            $btarType[7],
                        ],
                        'backgroundColor' => color(5),
                    ]) !!},
                    {!! json_encode([
                        'data' => [
                            $btarTypeNoData[0],
                            $btarTypeNoData[1],
                            $btarTypeNoData[2],
                            $btarTypeNoData[3],
                            $btarTypeNoData[4],
                            $btarTypeNoData[5],
                            $btarTypeNoData[6],
                            $btarTypeNoData[7],
                        ],
                        'backgroundColor' => color(13),
                    ]) !!},
                ],
                'assets_covered_btar',
                [],
                '',
                'multiple',
                '{!! auth()->user()->locale !!}',
                '{!! tenant()->get_default_currency !!}',
                10
            );

            barCharts(
                {!! json_encode([
                    __('Subject to NFRD'),
                    __('EU-based and non-subject to NFRD'),
                    __('External to the EU and non-subject to NFRD'),
                ]) !!},
                [
                    {!! json_encode(['data' => [$subjectNFRDWithData, $subjectNFRDEUWithData, $subjectNFRDNonEUWithData], 'backgroundColor' => color(5)]) !!},
                    {!! json_encode(['data' => [$subjectNFRDWithoutData, $subjectNFRDEUWithoutData, $subjectNFRDNonEUWithoutData], 'backgroundColor' => color(13)]) !!},
                ],
                'nonfinancial_assets_distribution',
                [],
                '',
                'multiple',
                '{!! auth()->user()->locale !!}',
                '{!! tenant()->get_default_currency !!}',
                15
            );

            // Common function for bar charts
            function barCharts(labels, data, id, barColor, unit = '', type = "single", locale = null, currency = null, splitLabelAt = null) {
                if (type == 'single') {
                    var data = {
                        labels: labels,
                        datasets: [{
                            data: data,
                            borderRadius: 0,
                            backgroundColor: barColor,
                            borderColor: '{{ color(6) }}'
                        }],
                    };
                } else {
                    var data = {
                        labels: labels,
                        datasets: data,
                    };
                }

                data['datasets'].map(function(dataset, index) {
                    dataset['lineTension'] = 0.3;
                    dataset['borderRadius'] = 0;
                    dataset['barThickness'] = labels.length <= 3 ? 45 : 'flex';
                    dataset['borderSkipped'] = false;
                    dataset['fill'] = true;
                });

                var chartOptions = {
                    layout: {
                        padding: {
                            top: 50
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                if (currency) {
                                    return new Intl.NumberFormat(locale, {
                                        style: 'currency',
                                        currency: currency,
                                        maximumFractionDigits: 2
                                    }).format(value);
                                } else {
                                    //return formatNumber(value) + unit;
                                    return formatNumber(value);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                color: '{{ color(8) }}',
                                callback: (value, index, values) => {
                                    if (currency != null) {
                                        return new Intl.NumberFormat(locale, {
                                            style: 'currency',
                                            currency: currency,
                                            maximumFractionDigits: 2
                                        }).format(value);
                                    } else {
                                        return value;
                                    }
                                }
                            },
                            grid: {
                                drawBorder: false,
                                display: true,
                                borderColor: '{{ color(8) }}',
                                borderDash: [10, 2],
                            },
                        },
                        x: {
                            display: true,
                            offset: true,
                            crossAlign: 'far',
                            ticks: {
                                display: true,
                                color: '{{ color(8) }}',
                                callback: (value, index, values) => {
                                    str = labels[index].toString();
                                    if (splitLabelAt) {
                                        let pattern = `.{${splitLabelAt}}\\S*\\s+`
                                        reg = new RegExp(pattern, 'g')
                                        return str.replace(reg, "$&@").split(/\s+@/);
                                    }
                                    return str;
                                },
                            },
                            grid: {
                                display: false,
                                borderColor: '{{ color(8) }}'
                            },
                        }
                    },

                };

                return new Chart(document.getElementById(id).getContext("2d"), {
                    type: 'bar',
                    data: data,
                    options: chartOptions,
                    plugins: [ChartDataLabels]
                });
            }
        });
    </script>
@endpush


<div>
    <x-slot name="header">
        <x-header title="{{ __('Asset listing') }}" dataTest="data-header" class="!bg-esg4" textcolor="text-esg5"
            iconcolor="{{ color(5) }}" click="#">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                <x-buttons.a-icon modal="gar-btar-assets.upload-document" :data="''" data-test="add-data-btn"
                    class="flex place-content-end uppercase">
                    <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                        @include('icons/library/upload', ['color' => color(4)])
                        <label>{{ __('Upload') }}</label>
                    </div>
                </x-buttons.a-icon>
            </x-slot>
        </x-header>
    </x-slot>

    @php
        $buttons = [
            [
                'route' => 'tenant.garbtar.assets',
                'label' => __('Panel'),
                'icon' => 'panel',
            ],
            [
                'route' => 'tenant.garbtar.asset',
                'label' => __('All'),
                'icon' => 'asset',
            ],
        ];
    @endphp

    <x-menus.panel :buttons="$buttons" activated='tenant.garbtar.assets' />

    <x-filters.filter-bar :filters="$availableFilters" :isSearchable="$isSearchable" />

    @php
        $colors = [color(1), color(5), color(13), color(20), color(7)];
    @endphp
    <div class="">
        <h2 class="font-bold text-2xl text-esg16">{{ __('') }}</h2>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        <x-cards.card-split class="flex-col !p-3">
            <x-cards.card class="flex-col !p-3 h-[169px]">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2 align-self-start">
                    {{ __('Total number of assets') }}
                    <x-information
                        id="{{ Str::slug('Total number of assets') }}">{{ __('Total number of assets owned by the bank') }}</x-information>
                </h2>
                <div class="flex-col h-full justify-center items-center inline-flex">
                    <h1 class="text-center text-esg5 text-6xl font-bold">
                        {{ $assets->count() }}
                    </h1>
                </div>
            </x-cards.card>
            <x-cards.card class="flex-col !p-3 h-[169px]">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2 align-self-start">
                    {{ __('Assets volume') }}
                    <x-information
                        id="{{ Str::slug('Total asset volume owned by the bank') }}">{{ __('Total asset volume owned by the bank') }}</x-information>
                </h2>
                <div class="flex-col h-full justify-center items-center inline-flex">
                    <h1 class="text-center text-esg5 text-5xl font-bold">
                        {{ formatToCurrency($total) }}
                    </h1>
                </div>
            </x-cards.card>
        </x-cards.card-split>

        <x-cards.card class="flex-col !p-3">
            <div class="flex justify-between">
                <div>
                    <h2 class="text-lg text-esg8 font-medium pl-2 align-self-start">
                        {{ __('Taxonomy coverage of balance sheet assets') }}
                        <x-information
                            id="{{ Str::slug('Taxonomy coverage of balance sheet assets (in percentage)') }}">{{ __('Taxonomy coverage of balance sheet assets (in percentage)') }}</x-information>
                    </h2>
                </div>
                <div>
                    <x-inputs.select id="period" :extra="['options' => []]" default="last 12 months"
                        class="!h-7 !p-0 !py-1 !pl-4 !pr-10 !border-esg7/40 text-sm" />
                </div>
            </div>

            <div class="flex-grow flex items-center">
                <x-charts.chartjs id="taxonomy_coverage_assets" class="max-h-[200px]" x-init="$nextTick(() => {
                    tenantDoughnutChart(
                        {{ json_encode(['Covered', 'Not covered'], true) }},
                        {{ json_encode([$assetBtar, $assetExcluded], true) }},
                        'taxonomy_coverage_assets',
                        {{ json_encode([color(5), color(13)], true) }}, {
                            legend: {
                                display: true,
                            },
                        }
                    );
                });" />
            </div>
        </x-cards.card>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">
                {{ __('Assets covered by regulatory taxonomy-related ratios') }}
                <x-information
                    id="{{ Str::slug('Assets covered by regulatory taxonomy-related ratios, by type of asset') }}">{{ __('Assets covered by regulatory taxonomy-related ratios, by type of asset') }}</x-information>
            </h2>
            <div class="flex-grow flex items-center">
                <x-charts.chartjs id="assets_covered_regulatory_taxonomy" class="max-h-[200px]"
                    x-init="$nextTick(() => {
                        tenantDoughnutChart(
                            {{ json_encode(
                                [
                                    __('Assets excluded from GAR and BTAR numerators'),
                                    __('Own real estate'),
                                    __('Households'),
                                    __('Local public sector'),
                                    __('Companies'),
                                ],
                                true,
                            ) }},
                            {{ json_encode([$assetExcluded, $realEstate, $families, $publicSector, $companies], true) }},
                            'assets_covered_regulatory_taxonomy',
                            {{ json_encode($colors, true) }}, {
                                legend: {
                                    position: 'right',
                                },
                                locale: '{!! auth()->user()->locale !!}',
                                currency: '{!! tenant()->get_default_currency !!}'
                            }
                        );
                    });" />
            </div>
        </x-cards.card>

        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium pl-2 align-self-start">
                {{ __('Assets with a taxonomy-related specific purpose') }}
                <x-information
                    id="{{ Str::slug('Assets with a taxonomy-related specific purpose') }}">{{ __('Assets with a taxonomy-related specific purpose') }}</x-information>
            </h2>
            <div class="flex-grow flex items-center">
                <x-charts.chartjs id="assets_taxonomy_specific_purpose" class="max-h-[200px]" x-init="$nextTick(() => {
                    tenantDoughnutChart(
                        {{ json_encode(['Specific purpose', 'No Specific purpose'], true) }},
                        {{ json_encode([$specializedCredit, $nonSpecializedCredit], true) }},
                        'assets_taxonomy_specific_purpose',
                        {{ json_encode([color(5), color(13)], true) }}, {
                            legend: {
                                display: true,
                            },
                        }
                    );
                });" />
            </div>
        </x-cards.card>

    </div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium pl-2 align-self-start">
                {{ __('Assets relevance distribution') }}
            </h2>
            <div class="flex-grow flex items-center">
                <x-charts.chartjs id="assets_taxonomy_specific_purpose_new" class="max-h-[200px]"/>
            </div>
        </x-cards.card>

        <x-cards.card-panel-legend-unit title="{{ __('Assets relevance distribution') }}"
            description="{{ __('Asset distribution by relevance to ratios, covered by GAR and covered by BTAR') }}"
            legend="{{ json_encode([
                ['color' => color(5), 'text' => __('With Taxonomy Information')],
                ['color' => color(13), 'text' => __('Without Taxonomy Information')],
            ]) }}">
            <x-charts.bar id="assets_relevance_distribution" class="m-auto relative !h-full !w-full" unit="%" />
        </x-cards.card-panel-legend-unit>
    </div>

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card-panel-legend-unit title="{{ __('Assets covered by GAR per entity type') }}"
            description="{{ __('Distribution of assets included in the calculation of the GAR, by type of entity and by availability of information/taxonomy exercise') }}"
            legend="{{ json_encode([
                ['color' => color(5), 'text' => __('With Taxonomy Information')],
                ['color' => color(13), 'text' => __('Without Taxonomy Information')],
            ]) }}">
            <x-charts.bar id="assets_covered_gar" class="m-auto relative !h-full !w-full" unit="%" />
        </x-cards.card-panel-legend-unit>
    </div>

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card-panel-legend-unit title="{{ __('Assets covered by BTAR per entity type') }}"
            description="{{ __('Distribution of assets included in the BTAR calculation, by type of entity and by availability of information/taxonomy exercise') }}"
            legend="{{ json_encode([
                ['color' => color(5), 'text' => __('With Taxonomy Information')],
                ['color' => color(13), 'text' => __('Without Taxonomy Information')],
            ]) }}">
            <x-charts.bar id="assets_covered_btar" class="m-auto relative !h-full !w-full" unit="%" />
        </x-cards.card-panel-legend-unit>
    </div>

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card-panel-legend-unit title="{{ __('Non-financial corporations assets distribution') }}"
            description="{{ __('Distribution of assets relating to non-financial entities, European and foreign, subject and not subject to NFRD and by availability of information/exercise of taxonomy') }}"
            legend="{{ json_encode([
                ['color' => color(5), 'text' => __('With Taxonomy Information')],
                ['color' => color(13), 'text' => __('Without Taxonomy Information')],
            ]) }}">
            <x-charts.bar id="nonfinancial_assets_distribution" class="m-auto relative !h-full !w-full"
                unit="%" />
        </x-cards.card-panel-legend-unit>
    </div>

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium pb-6 align-self-start">
                {{ __('Assets relevant for ratio calculations') }}
                <x-information
                    id="{{ Str::slug('Detailed information on the relevant assets for calculating both ratios.') }}">{{ __('Detailed information on the relevant assets for calculating both ratios.') }}</x-information>
            </h2>

            <div class="flex justify-evenly flex-row items-center">
                <x-charts.chartjs id="assets_relevant_ratio_calculation" class=" max-h-[200px]"
                    x-init="$nextTick(() => {
                        tenantDoughnutChart(
                            {{ json_encode(
                                [
                                    __('Balance sheet assets excluded from the numerator'),
                                    __('Corporate assets covered by GAR (numerator)'),
                                    __('Corporate assets covered by BTAR (numerator)'),
                                    __('Household assets'),
                                    __('Other assets covered'),
                                ],
                                true,
                            ) }},
                            {{ json_encode([$excludedNumerator, $companies, $companies + $subjectNFRDEU + $subjectNFRDNonEU, $families, $publicSector + $realEstate], true) }},
                            'assets_relevant_ratio_calculation',
                            {{ json_encode($colors, true) }}, {
                                legend: {
                                    display: true,
                                },
                                locale: '{!! auth()->user()->locale !!}',
                                currency: '{!! tenant()->get_default_currency !!}'
                            }
                        );
                    });" />
            </div>

        </x-cards.card>
    </div>

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card class="flex-col ">
            <div class="flex flex-row items-center">
                <h2 class="text-lg text-esg8 font-medium align-self-start ml-1 mr-2">{{ __('FULL ANALYSIS') }}
                </h2>
            </div>

            <x-tables.table>
                <x-slot name="thead">
                    <th class="text-esg8 text-sm font-normal text-left uppercase">{{ __('Asset') }}</th>
                    <th class="w-20 text-esg8 text-sm font-normal text-left uppercase">{{ __('Value') }}</th>
                    <th class="w-3 text-esg8 text-sm text-normal text-left"></th>
                </x-slot>
                @php
                    $data = [
                        [
                            [
                                'name' => __('Balance sheet assets excluded from the numerator'),
                                'subtitle' => true,
                            ],
                            [
                                'name' => __('Derivatives'),
                                'total' => $excludedNumeratorDetailed[10][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE],
                            ],
                            [
                                'name' => __('Interbank demand loans'),
                                'total' => $excludedNumeratorDetailed[11][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE],
                            ],
                            [
                                'name' => __('Cash and cash equivalent assets'),
                                'total' => $excludedNumeratorDetailed[12][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE],
                            ],
                            [
                                'name' => __('Other assets (e.g. goodwill, commodities, etc.)'),
                                'total' => $excludedNumeratorDetailed[13][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE],
                            ],
                        ],
                        [
                            [
                                'name' => __('Corporate assets covered by GAR (numerator)'),
                                'subtitle' => true,
                            ],
                            [
                                'name' => __('Loans and prepayments to companies'),
                                'total' => $companiesDetailed[App\Models\Tenant\GarBtar\BankAssets::GAR][0],
                            ],
                            [
                                'name' => __('Debt securities, including participation units'),
                                'total' => $companiesDetailed[App\Models\Tenant\GarBtar\BankAssets::GAR][1],
                            ],
                            [
                                'name' => __('Equity instruments'),
                                'total' => $companiesDetailed[App\Models\Tenant\GarBtar\BankAssets::GAR][2],
                            ],
                        ],
                        [
                            [
                                'name' => __('Corporate assets covered by BTAR (numerator)'),
                                'subtitle' => true,
                            ],
                            [
                                'name' => __('Loans and prepayments to companies'),
                                'total' => $companiesDetailed[App\Models\Tenant\GarBtar\BankAssets::BTAR][0],
                            ],
                            [
                                'name' => __('Debt securities, including participation units'),
                                'total' => $companiesDetailed[App\Models\Tenant\GarBtar\BankAssets::BTAR][1],
                            ],
                            [
                                'name' => __('Equity instruments'),
                                'total' => $companiesDetailed[App\Models\Tenant\GarBtar\BankAssets::BTAR][2],
                            ],
                        ],
                        [
                            [
                                'name' => __('Household assets'),
                                'subtitle' => true,
                            ],
                            [
                                'name' => __('Loans to households secured by residential property'),
                                'total' => $assetType[4],
                            ],
                            [
                                'name' => __('Loans to households for the renovation of buildings'),
                                'total' => $assetType[5],
                            ],
                            [
                                'name' => __('Loans to households for the purchase of cars'),
                                'total' => $assetType[6],
                            ],
                        ],
                        [
                            [
                                'name' => __('Other assets covered'),
                                'subtitle' => true,
                            ],
                            [
                                'name' => __('Loans to the public sector for housing construction'),
                                'total' => $assetType[7],
                            ],
                            [
                                'name' => __('Other Loans to the local public sector'),
                                'total' => $assetType[8],
                            ],
                            [
                                'name' => __('Residential and commercial real estate obtained by acquiring ownership'),
                                'total' => $assetType[9],
                            ],
                        ],
                    ];
                    $total = array_sum(array_column($data[0], 'total')) + array_sum(array_column($data[1], 'total')) + array_sum(array_column($data[2], 'total')) + array_sum(array_column($data[3], 'total'));
                @endphp
                @foreach ($data as $index => $section)
                    @foreach ($section as $row)
                        @if (isset($row['subtitle']))
                            <tr
                                class="h-10 py-2 border-b border-esg10 text-left last:border-b-0 bg-[{{ $colors[$index] }}]/20">
                                <td class="grow shrink basis-0 text-[{{ $colors[$index] }}] text-md font-bold">
                                    {{ $row['name'] }}
                                </td>
                                <td class=""></td>
                                <td class="w-5"></td>
                            </tr>
                        @else
                            <tr class="h-10 py-2 border-b border-esg10 text-left last:border-b-0">
                                <td class=" text-esg8 text-sm font-normal">
                                    {{ $row['name'] }}
                                </td>
                                <td>
                                    <div class="whitespace-nowrap text-right flex flex-row items-center gap-1">
                                        <span class="text-xl font-bold text-[{{ $colors[$index] }}] text-right">
                                            {{ formatToCurrency($row['total']) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="w-[100px]">
                                    <div class="w-full h-full flex flex-row items-center gap-3 ml-2">
                                        <div
                                            class="px-1 py-0.5 bg-[{{ $colors[$index] }}]/20 rounded flex-col justify-center
                                                items-center gap-2.5 inline-flex text-xs text-[{{ $colors[$index] }}] font-bold whitespace-nowrap">
                                            {{ calculatePercentage($row['total'], $total) }} %
                                        </div>
                                        @include('icons.list')
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach

            </x-tables.table>


        </x-cards.card>
    </div>
</div>
