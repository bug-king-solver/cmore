@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            barCharts(
                {!! json_encode([__('Micro'), __('Small'), __('Medium'), __('Large')]) !!},
                [{!! json_encode(['data' => [20, 20, 20, 80], 'backgroundColor' => color(5)]) !!}, {!! json_encode(['data' => [58, 30, 58, 20], 'backgroundColor' => color(13)]) !!}],
                'bank_exposure_company_size',
                [],
                '',
                'multiple'
            );
            barCharts(
                {!! json_encode([__('Micro'), __('Small'), __('Medium'), __('Large')]) !!},
                [{!! json_encode(['data' => [20, 20, 20, 80], 'backgroundColor' => color(5)]) !!}, {!! json_encode(['data' => [58, 30, 58, 20], 'backgroundColor' => color(13)]) !!}],
                'number_companies_size',
                [],
                '',
                'multiple'
            );
            barCharts(
                {!! json_encode([
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H',
                    'I',
                    'J',
                    'K',
                    'L',
                    'M',
                    'N',
                    'O',
                    'P',
                    'Q',
                    'R',
                    'S',
                    'T',
                    'U',
                ]) !!},
                [{!! json_encode([
                    'data' => [20, 20, 20, 80, 20, 20, 20, 80, 20, 20, 20, 80, 20, 20, 20, 80, 58, 30, 58, 20, 55],
                    'backgroundColor' => color(5),
                ]) !!},
                    {!! json_encode([
                        'data' => [58, 30, 58, 20, 58, 30, 58, 20, 58, 30, 58, 20, 58, 30, 58, 20, 58, 30, 58, 20, 45],
                        'backgroundColor' => color(13),
                    ]) !!}
                ],
                'number_companies',
                [],
                '',
                'multiple'
            );
            barCharts(
                {!! json_encode([
                    'A',
                    'B',
                    'C',
                    'D',
                    'E',
                    'F',
                    'G',
                    'H',
                    'I',
                    'J',
                    'K',
                    'L',
                    'M',
                    'N',
                    'O',
                    'P',
                    'Q',
                    'R',
                    'S',
                    'T',
                    'U',
                ]) !!},
                [{!! json_encode([
                    'data' => [20, 20, 20, 80, 20, 20, 20, 80, 20, 20, 20, 80, 20, 20, 20, 80, 58, 30, 58, 20, 55],
                    'backgroundColor' => color(5),
                ]) !!},
                    {!! json_encode([
                        'data' => [58, 30, 58, 20, 58, 30, 58, 20, 58, 30, 58, 20, 58, 30, 58, 20, 58, 30, 58, 20, 45],
                        'backgroundColor' => color(13),
                    ]) !!}
                ],
                'bank_exposure_esg_info_nace_code',
                [],
                '',
                'multiple'
            );
            barCharts(
                {!! json_encode([__('With taxonomy data'), __('Without taxonomy data')]) !!},
                {!! json_encode([75, 48]) !!},
                'bank_exposure',
                ['{{ color(5) }}', '{{ color(13) }}']
            );
            barCharts(
                {!! json_encode(['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC']) !!},
                [{!! json_encode([
                    'data' => [20, 20, 20, 80, 20, 20, 20, 80, 20, 20, 20, 80],
                    'backgroundColor' => color(5),
                ]) !!},
                    {!! json_encode([
                        'data' => [58, 30, 58, 20, 58, 30, 58, 20, 58, 30, 58, 20],
                        'backgroundColor' => color(13),
                    ]) !!}
                ],
                'number_companies_questionnaire_type_months',
                [],
                '',
                'multiple'
            );
        });


        // Common function for bar charts
        function barCharts(labels, data, id, barColor, unit = '', type = "single") {
            if (type == 'single') {
                var data = {
                    labels: labels,
                    datasets: [{
                        data: data,
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
                        //color: barColor,
                        //backgroundColor : hexToRgbA(barColor),
                        formatter: function(value) {
                            //return formatNumber(value) + unit;
                            return formatNumber(value);
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: '{{ color(8) }}'
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
                        ticks: {
                            display: true,
                            color: '{{ color(8) }}'
                        },
                        grid: {
                            display: false,
                            borderColor: '{{ color(8) }}'
                        },
                    }
                },
                // animation: {
                //     duration: 0
                // }

            };

            return new Chart(document.getElementById(id).getContext("2d"), {
                type: 'bar',
                data: data,
                options: chartOptions,
                plugins: [ChartDataLabels]
            });
        }
    </script>
@endpush
<div class="px-4 md:px-0">
    <x-slot name="header">
        <x-header title="{{ __('Companies') }}" data-test="companies-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('companies.create')
                    <x-buttons.a-icon href="{{ route('tenant.companies.form') }}" data-test="add-data-btn"
                        class="flex place-content-end uppercase">
                        <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                            @include('icons.add', ['color' => color(4)])
                            <label>{{ __('Add') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>

    @php
        // TODO :: Re-use the code below
        $buttons = [
            [
                'route' => 'tenant.companies.index',
                'label' => __('Panel'),
                'icon' => 'panel',
            ],
            [
                'route' => 'tenant.companies.list',
                'label' => __('All'),
                'icon' => 'building-v2',
                'params' => ['s[]' => ''],
                'reference' => null,
            ],
        ];

        if (tenant()->companiesTypeAvailableInMenu) {
            $typeInternal = \App\Models\Enums\Companies\Type::INTERNAL;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $typeInternal->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_type_filter][0]' => $typeInternal->value,
                ],
                'reference' => $typeInternal->value,
            ];

            $typeExternal = \App\Models\Enums\Companies\Type::EXTERNAL;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $typeExternal->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_type_filter][0]' => $typeExternal->value,
                ],
                'reference' => $typeExternal->value,
            ];
        }

        if (tenant()->companiesRelationAvailableInMenu) {
            $relationClient = \App\Models\Enums\Companies\Relation::CLIENT;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $relationClient->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_relation_filter][0]' => $relationClient->value,
                ],
                'reference' => $relationClient->value,
            ];

            $relationSuppplier = \App\Models\Enums\Companies\Relation::SUPPLIER;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $relationSuppplier->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_relation_filter][0]' => $relationSuppplier->value,
                ],
                'reference' => $relationSuppplier->value,
            ];
        }

    @endphp

    @php
        $firstArrayKey = array_key_first($this->activeFilters ?? []);
        $reference = $this->activeFilters[$firstArrayKey][0] ?? null;
    @endphp

    <x-menus.panel :buttons="$buttons" activated='tenant.companies.index' :reference="$reference" />


    @if (tenant()->has_gar_btar_feature)
        <div class="">
            <h2 class="font-bold text-2xl text-esg16">{{ __('Bank exposure') }}</h2>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <x-cards.card class="flex-col !p-3">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2 align-self-start">{{ __('Total bank exposure') }}
                </h2>
                <div class="flex-col h-full justify-center items-center inline-flex">
                    <h1 class="text-center text-esg5 text-6xl font-bold">
                        30.000 €
                    </h1>
                    <h2 class="text-esg8">{{ __('bank exposure to client companies') }}</h2>
                </div>
            </x-cards.card>

            <x-cards.card-panel-legend-unit title="{{ __('Bank exposure by company size') }}"
                legend="{{ json_encode([
                    ['color' => color(5), 'text' => __('With ESG Information')],
                    ['color' => color(13), 'text' => __('Without ESG Information')],
                ]) }}">
                <x-charts.bar id="bank_exposure_company_size" class="m-auto relative !h-full !w-full" unit="%" />
            </x-cards.card-panel-legend-unit>
        </div>

        <div class="grid grid-cols-1 gap-4 mt-6">
            <x-cards.card-panel-legend-unit title="{{ __('Bank Exposure with ESG information per NACE code') }}"
                legend="{{ json_encode([
                    ['color' => color(5), 'text' => __('With ESG Information')],
                    ['color' => color(13), 'text' => __('Without ESG Information')],
                ]) }}">
                <x-charts.bar id="bank_exposure_esg_info_nace_code" class="m-auto relative !h-full !w-full"
                    unit="%" />
            </x-cards.card-panel-legend-unit>
        </div>

        <div class="mt-20">
            <h2 class="font-bold text-2xl text-esg16">{{ __('Companies with ESG information') }}</h2>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <x-cards.card class="flex-col !p-3">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">{{ __('Companies with ESG information') }}</h2>
                <div class="flex-grow flex items-center">

                    @php
                        $labels = [__('With ESG Information'), __('Without ESG Information')];
                    @endphp
                    <x-charts.chartjs id="companies_updated_info" class="max-h-[200px]" x-init="$nextTick(() => {
                        tenantDoughnutChart(
                            {{ json_encode($labels) }},
                            {{ json_encode([15000, 7500]) }},
                            'companies_updated_info',
                            {{ json_encode($colors) }}, {
                                legend: {
                                    position: 'right',
                                },
                            }
                        );
                    });" />
                </div>
            </x-cards.card>

            <x-cards.card-panel-legend-unit title="{{ __('Companies with ESG information by company size') }}"
                legend="{{ json_encode([
                    ['color' => color(5), 'text' => __('With ESG Information')],
                    ['color' => color(13), 'text' => __('Without ESG Information')],
                ]) }}">
                <x-charts.bar id="number_companies_size" class="m-auto relative !h-full !w-full" />
            </x-cards.card-panel-legend-unit>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <x-cards.card class="flex-col !p-3">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">{{ __('Number of companies obliged to Taxonomy') }}
                </h2>
                <div class="w-full p-2 text-center">
                    <x-charts.chartjs id="number_companies_obligated_taxonomy" class="" x-init="$nextTick(() => {
                        tenantBarChart(
                            {{ json_encode([__('With taxonomy data'), __('Without taxonomy data')], true) }},
                            {{ json_encode([17000, 13000], true) }},
                            'number_companies_obligated_taxonomy',
                            ['{{ color(5) }}', '{{ color(13) }}'],
                            '', {
                                simplifiedGrid: true
                            }
                        );
                    });" />
                </div>
            </x-cards.card>

            <x-cards.card-panel-legend-unit title="{{ __('Bank exposure') }}">
                <x-charts.bar id="bank_exposure" class="m-auto relative !h-full !w-full" unit="%" />
            </x-cards.card-panel-legend-unit>
        </div>

        <div class="grid grid-cols-1 gap-4 mt-6">
            <x-cards.card-panel-legend-unit title="{{ __('Companies with ESG information by company NACE') }}"
                legend="{{ json_encode([
                    ['color' => color(5), 'text' => __('With ESG Information')],
                    ['color' => color(13), 'text' => __('Without ESG Information')],
                ]) }}">
                <x-charts.bar id="number_companies" class="m-auto relative !h-full !w-full" />
            </x-cards.card-panel-legend-unit>
        </div>
    @endif

    <div class="@if (tenant()->has_gar_btar_feature) mt-20 @endif">
        <h2 class="font-bold text-2xl text-esg16">{{ __('General information') }}</h2>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium mb-3 pl-2 align-self-start">
                {{ __('Number of total companies') }}
                <x-information
                    id="{{ Str::slug('Total number of companies registered in the platform') }}">{{ __('Total number of companies registered in the platform') }}</x-information>
            </h2>
            <div class="flex-col h-full justify-center items-center inline-flex">
                <h1 class="text-center text-esg5 text-6xl font-bold">
                    {{ $companies_total }}
                </h1>
                <h2 class="text-esg8">{{ __('companies') }}</h2>
            </div>
        </x-cards.card>

        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">
                {{ __('Number of companies per size') }}
                <x-information
                    id="data_doughnut_chart_info">
                    {{ __('Micro: less than 10 people and have an annual turnover of not more than €2 million; Small: less than 50 people and have an annual turnover of not more than €10 million; Medium: less than 250 people and have an annual turnover of not more than €50 million; Large: 250 people or more and have an annual turnover of over €50 million;') }}
                </x-information>
            </h2>
            <div class="flex-grow max-h-[200px]">
                <x-charts.chartjs id="data_doughnut_chart" x-init="$nextTick(() => {
                    tenantDoughnutChart(
                        {{ json_encode(array_column($companies_size, 'label'), true) }},
                        {{ json_encode(array_column($companies_size, 'value'), true) }},
                        'data_doughnut_chart',
                        {{ json_encode($colors, true) }}, {
                            legend: {
                                position: 'right',
                            },
                            action: {
                                'onclick': ' {{ route('tenant.companies.list') }}',
                                'filter': 'company_size_filter',
                                'typeAction': 'redirect'
                            }
                        }
                    );
                });" />
            </div>
        </x-cards.card>
    </div>

    @if (tenant()->has_gar_btar_feature)
        <div class="grid grid-cols-2 gap-4 mt-6">
            <x-cards.card class="flex-col !p-3">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">
                    {{ __('Number of companies: internal vs external') }}
                    <x-information
                        id="{{ Str::slug('Internal companies are the ones that belongs to the organization that owns the tenant. External companies are the opposite, for example: clients and suppliers.') }}">{{ __('Internal companies are the ones that belongs to the organization that owns the tenant. External companies are the opposite, for example: clients and suppliers.') }}</x-information>
                </h2>
                <div class="w-full p-2 text-center">
                    <x-charts.chartjs id="total_clients_companies_internal_vs_external" class=""
                        x-init="$nextTick(() => {
                            tenantBarChart(
                                {{ json_encode([__('Internal'), __('External')], true) }},
                                {{ json_encode([\App\Models\Tenant\Company::IsInternalCompany()->count(), \App\Models\Tenant\Company::IsExternalCompany()->count()], true) }},
                                'total_clients_companies_internal_vs_external',
                                ['{{ color(5) }}', '{{ color(13) }}'],
                                '', {
                                    simplifiedGrid: true
                                }
                            );
                        });" />
                </div>
            </x-cards.card>

            <x-cards.card class="flex-col !p-3">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">
                    {{ __('Number of companies: clients vs suppliers') }}
                    <x-information
                        id="{{ Str::slug('External companies can be classified as: client or/and suppliers.') }}">{{ __('External companies can be classified as: client or/and suppliers.') }}</x-information>
                </h2>
                <div class="w-full p-2 text-center">
                    <x-charts.chartjs id="total_clients_companies_clients_vs_suppliers" class=""
                        x-init="$nextTick(() => {
                            tenantBarChart(
                                {{ json_encode([__('Clients'), __('Suppliers')], true) }},
                                {{ json_encode([\App\Models\Tenant\Company::IsClientCompany()->count(), \App\Models\Tenant\Company::IsSupplierCompany()->count()], true) }},
                                'total_clients_companies_clients_vs_suppliers',
                                ['{{ color(5) }}', '{{ color(13) }}'],
                                '', {
                                    simplifiedGrid: true
                                }
                            );
                        });" />
                </div>
            </x-cards.card>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">{{ __('Companies per NACE sector') }}</h2>
            <div class="w-full p-2 text-center">
                <x-charts.chartjs id="data_bar_chart" class="" x-init="$nextTick(() => {
                    tenantBarChart(
                        {{ json_encode(array_column($companies_nace_sector, 'label'), true) }},
                        {{ json_encode($companies_nace_sector, true) }},
                        'data_bar_chart',
                        ['{{ color(5) }}'],
                    );
                });" />
            </div>
        </x-cards.card>
    </div>

    @if (tenant()->has_gar_btar_feature)
        <div class="mt-20">
            <h2 class="font-bold text-2xl text-esg16">{{ __('Companies vs Questionnaires') }}</h2>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <x-cards.card class="flex-col !p-3">
                <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">
                    {{ __('Number of companies per questionnaire type') }}
                </h2>
                <div class="w-full p-2 text-center">
                    <x-charts.chartjs id="number_companies_questionnaire_type" class=""
                        x-init="$nextTick(() => {
                            tenantBarChart(
                                {{ json_encode(['Taxonomy', 'ESG Complete', 'Physical Risks', 'CO2 Calculator'], true) }},
                                {{ json_encode([17000, 13000, 17000, 13000], true) }},
                                'number_companies_questionnaire_type',
                                ['{{ color(5) }}'],
                                '', {
                                    simplifiedGrid: true
                                }
                            );
                        });" />
                </div>
            </x-cards.card>

            <x-cards.card-panel-legend-unit title="{{ __('Number of companies per questionnaire type') }}"
                legend="{{ json_encode([
                    ['color' => color(5), 'text' => __('Taxonomy')],
                    ['color' => color(13), 'text' => __('ESG Complete')],
                ]) }}">
                <x-charts.bar id="number_companies_questionnaire_type_months" class="m-auto relative !h-full !w-full"
                    unit="%" />
            </x-cards.card-panel-legend-unit>
        </div>

        <div class="grid grid-cols-1 gap-4 mt-6">
            <x-cards.card class="flex-col !p-3">
                <div class="flex justify-between">
                    <div>
                        <h2 class="text-lg text-esg8 font-medium pl-2 align-self-start">{{ __('Highlights') }}</h2>
                        <h4 class="text-sm text-esg16 font-medium mb-3 pl-2 align-self-start">
                            {{ __('Top 10 clients by bank exposure (%)') }}</h4>
                    </div>
                    <div>
                        <x-inputs.select id="period" :extra="['options' => []]" default="Top 10"
                            class="!h-7 !p-0 !py-1 !pl-4 !pr-10 !border-esg7/40" />
                    </div>
                </div>
                @php
                    $highlights = [['name' => 'First place client', 'percentage' => '00'], ['name' => 'Second place client', 'percentage' => '00'], ['name' => 'Third place client', 'percentage' => '00'], ['name' => 'Fourth place client', 'percentage' => '00'], ['name' => 'Fifth place client', 'percentage' => '00'], ['name' => 'Sixth place client', 'percentage' => '00'], ['name' => 'Seventh place client', 'percentage' => '00'], ['name' => 'Eighth place client', 'percentage' => '00'], ['name' => 'Ninth place client', 'percentage' => '00'], ['name' => 'Tenth place client', 'percentage' => '00']];
                @endphp
                <x-panel.highlights-table :highlights="$highlights" />
            </x-cards.card>
        </div>
    @endif
</div>
