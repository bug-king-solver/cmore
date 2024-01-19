@extends('layouts.tenant', ['title' => __('Dashboard'), 'mainBgColor' => 'bg-esg4'])

@php
    $categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
    $genderIconUrl = global_asset('images/icons/genders/{id}.svg');
@endphp
@push('body')
    <style nonce="{{ csp_nonce() }}">
        @media print {
            .pagebreak {
                clear: both;
                page-break-after: always;
            }

            div {
                break-inside: avoid;
            }

            .nonavoid {
                break-inside: auto;
            }

            #launcher,
            #footer {
                visibility: hidden;
            }

            .print {
                page-break-after: avoid;
            }

            /* html, body {
                            border: 1px solid white;
                            height: 99%;
                            page-break-after: avoid;
                            page-break-before: avoid;
                            } */
        }

        @page {
            size: A4;
            /* DIN A4 standard, Europe */
            /* margin: 70pt 60pt 70pt; */
        }
    </style>
    <script nonce="{{ csp_nonce() }}">
        var color_male = "#058894",
            color_female = "#06A5B4",
            color_other = "#83D2DA";

        var social_male = "#F90",
            social_female = "#FBB040",
            social_other = "#FFDDAB";

        var enviroment_color1 = "#008131",
            enviroment_color2 = "#99CA3C",
            enviroment_color3 = "#6AD794",
            enviroment_color4 = "#98BDA6";

        document.addEventListener('DOMContentLoaded', () => {
            var color_code = twConfig.theme.colors.esg7;

            @php

                // contracted workers
                $male_contracted_workers = $charts['social']['categories']['workers_of_the_organisation']['categories']['percentage_for_contract_workers']['categories']['percentage_for_male_contract_workers']['indicators'][1]['value'];
                $female_contracted_workers = $charts['social']['categories']['workers_of_the_organisation']['categories']['percentage_for_contract_workers']['categories']['percentage_for_female_contract_workers']['indicators'][0]['value'];
                $other_contracted_workers = $charts['social']['categories']['workers_of_the_organisation']['categories']['percentage_for_contract_workers']['categories']['percentage_for_other_gender_contract_workers']['indicators'][2]['value'];

                //outsourced workers
                $male_outsourced_workers = $charts['social']['categories']['workers_of_the_organisation']['categories']['percentage_for_outsourced_workers']['categories']['percentage_for_male_outsourced_workers']['indicators'][1]['value'];
                $female_outsourced_workers = $charts['social']['categories']['workers_of_the_organisation']['categories']['percentage_for_outsourced_workers']['categories']['percentage_for_female_outsourced_workers']['indicators'][0]['value'];
                $other_outsourced_workers = $charts['social']['categories']['workers_of_the_organisation']['categories']['percentage_for_outsourced_workers']['categories']['percentage_for_other_gender_outsourced_workers']['indicators'][2]['value'];

                //hourly earnings
                $male_hourly_earnings = $charts['social']['categories']['workers']['categories']['hourly_earnings_variation']['indicators'][1]['value'];
                $female_hourly_earnings = $charts['social']['categories']['workers']['categories']['hourly_earnings_variation']['indicators'][0]['value'];
                $other_hourly_earnings = $charts['social']['categories']['workers']['categories']['hourly_earnings_variation']['indicators'][2]['value'];

                //governance gender distribution
                $male_governance_distribution = $charts['governance']['categories']['structure']['categories']['percentage_for_gender_distribution']['categories']['percentage_for_male_distribution']['indicators'][1]['value'];
                $female_governance_distribution = $charts['governance']['categories']['structure']['categories']['percentage_for_gender_distribution']['categories']['percentage_for_female_distribution']['indicators'][0]['value'];
                $other_governance_distribution = $charts['governance']['categories']['structure']['categories']['percentage_for_gender_distribution']['categories']['percentage_for_other_gender_distribution']['indicators'][2]['value'];

                //reported and confirmed cases of bribery and corruption
                $reported_corruption = $charts['governance']['categories']['corruption_and_bribery']['categories']['corruption_reported']['total'];
                $reported_bribery = $charts['governance']['categories']['corruption_and_bribery']['categories']['bribery_reported']['total'];
                $confirmed_corruption = $charts['governance']['categories']['corruption_and_bribery']['categories']['corruption_confirmed']['total'];
                $confirmed_bribery = $charts['governance']['categories']['corruption_and_bribery']['categories']['bribery_confirmed']['total'];

            @endphp

            pieCharts(
                {!! json_encode([__('male'), __('female'), __('other')]) !!},
                {!! json_encode([$male_contracted_workers, $female_contracted_workers, $other_contracted_workers]) !!},
                'contracted_workers',
                [social_female, social_male, social_other],
                '{{ __('workers') }}'
            );


            pieCharts(
                {!! json_encode([__('male'), __('female'), __('other')]) !!},
                {!! json_encode([
                    $male_governance_distribution,
                    $female_governance_distribution,
                    $other_governance_distribution,
                ]) !!},
                'gender_high_governance_body',
                [color_female, color_male, color_other],
                '{{ __('members') }}'
            );

            pieCharts(
                {!! json_encode([__('male'), __('female'), __('other')]) !!},
                {!! json_encode([$male_outsourced_workers, $female_outsourced_workers, $other_outsourced_workers]) !!},
                'outsourced_workers',
                [social_female, social_male, social_other],
                '{{ __('workers') }}'
            );
            /*
                        // NEW
                        pieCharts(
                            {!! json_encode([__('Recicled'), __('Sent for disposal'), __('Hazardous'), __('Radioactive')]) !!},
                            {!! json_encode(['3.750', '3.750', '3.750', '3.750']) !!},
                            'waste_produced',
                            [enviroment_color1, enviroment_color2, enviroment_color4, enviroment_color3],
                            '{{ __('t') }}'
                        );

                        */
            // Bar charts
            barCharts(
                {!! json_encode([__('Female'), __('Male'), __('Other')]) !!},
                {!! json_encode([$female_hourly_earnings, $male_hourly_earnings, $other_hourly_earnings]) !!},
                'hourly_earnings',
                ['{{ color(1) }}']
            );

            barCharts(
                {!! json_encode([__('Reported'), __('Confirmed')]) !!},
                {!! json_encode([
                    ['label' => __('Reported'), 'backgroundColor' => '#058894', 'data' => [$reported_corruption, $reported_bribery]],
                    ['label' => __('Confirmed'), 'backgroundColor' => '#83D2DA', 'data' => [$confirmed_corruption, $confirmed_bribery]],
                ]) !!},
                'cases_corruption',
                [],
                '',
                'multi'
            );
        });


        // Common function for bar charts
        function barCharts(labels, data, id, barColor, unit = '', type = "single") {
            if (type == 'single') {
                var data = {
                    labels: labels,
                    datasets: [{
                        data: data,
                        lineTension: 0.3,
                        fill: true,
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

                            return formatNumber(value) + unit;
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

        // Common function for pie charts
        function pieCharts(labels, data, id, barColor, centertext = '') {
            var extra = {
                id: 'centerText',
                afterDatasetsDraw(chart, args, options) {
                    const {
                        ctx,
                        chartArea: {
                            left,
                            right,
                            top,
                            bottom,
                            width,
                            height
                        }
                    } = chart;

                    ctx.save;

                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.font = "bolder 24px " + twConfig.theme.fontFamily.encodesans;
                    ctx.fillStyle = '{{ color(8) }}';

                    let total = data;
                    let text = Math.round(total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(
                        current)));
                    ctx.fillText(formatNumber(text), width / 2, height / 3 + top + 20);

                    ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                    let newtext = (centertext !== undefined ? centertext : '-');
                    ctx.fillText(newtext, width / 2, height / 3 + top + 45);
                },
                afterInit: function(chart, args, options) {
                    if (labels != null) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '';

                        chart.data.datasets[0].data.forEach((datavale, i) => {
                            let total = data;
                            let labelText = chart.data.labels[i];
                            let value = datavale;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];

                            switch (labelText.toLowerCase()) {
                                case '{{ __('male') }}':
                                    gender = '{{ __('Man') }}';
                                    break;
                                case '{{ __('female') }}':
                                    gender = '{{ __('Woman') }}';
                                    break;
                                case '{{ __('other') }}':
                                    gender = '{{ __('Other') }}';
                                    break;
                            }

                            const sum = total.reduce((accumulator, current) => parseFloat(accumulator) +
                                parseFloat(current));
                            let percentag = Math.round(value * 100 / sum) + '%';

                            if (id != 'energy_consumption_reporting' && id !=
                                'energy_consumption_baseline') {
                                html += `
                                        <div class="grid w-full grid-cols-3">
                                            <div class="col-span-2 flex items-center">
                                                <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}"></div>
                                                <div class="inline-block text-sm text-esg8">${labelText}</div>
                                            </div>
                                            <div class="flex justify-between gap-1">
                                                <div class="text-sm text-esg8 leading-10">
                                                    <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>
                                                </div>
                                                <div class="text-right text-sm text-esg8 leading-10">
                                                    (${formatNumber(value)})
                                                </div>
                                            </div>
                                        </div>
                                    `;
                            } else {
                                html += `
                                    <div class="">
                                        <div class="text-center text-sm text-esg8 leading-8"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${formatNumber(value)} ${centertext})</div>
                                    </div>
                                `;
                            }
                        })

                        document.getElementById(legendId).innerHTML = html;
                    }
                }
            };

            var options = {
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: false,
                        font: {
                            family: twConfig.theme.fontFamily.encodesans,
                            size: 22,
                            weight: twConfig.theme.fontWeight.bold,
                        },
                        padding: {
                            bottom: 30
                        },
                        color: twConfig.theme.colors.esg27
                    },
                    datalabels: {
                        color: twConfig.theme.colors.esg27,
                        formatter: function(value) {
                            var total = data;
                            const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(
                                current));
                            return Math.round(value * 100 / sum) + '%';
                        },
                        font: {
                            weight: 'bold',
                            size: 15,
                        }
                    }
                },
                cutout: '82%',
                animation: {
                    duration: 0
                }
            };

            var config = {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: barColor,
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                        hoverOffset: 1
                    }]
                },
                options: options,
                plugins: [extra]
            };

            return new Chart(document.getElementById(id), config);
        }

        function initializePagination() {
            let currentPage = 1;
            const rowsPerPage = 5;
            const rows = document.querySelectorAll('.action_plan_tr');
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            document.getElementById('totalPages').textContent = totalPages.toString().padStart(2, '0');

            function updateTable() {
                for (let i = 0; i < rows.length; i++) {
                    if (i < rowsPerPage * currentPage && i >= rowsPerPage * (currentPage - 1)) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }

                document.getElementById('currentPage').textContent = currentPage.toString().padStart(2, '0');

                document.getElementById('prevPage').disabled = currentPage === 1;
                document.getElementById('nextPage').disabled = currentPage === totalPages;
            }

            updateTable();

            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            document.getElementById('nextPage').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTable();
                }
            });
        }

        initializePagination();
    </script>
@endpush

@section('content')

    <div class="px-4 lg:px-0" x-data="{ main: true, environment: false, social: false, governance: false }">

        <div class="mt-10">
            <div class="w-full flex justify-between">
                <div class="">
                    <a href="{{ route('tenant.questionnaires.panel') }}"
                        class="text-esg5 w-fit text-2xl font-bold flex flex-row gap-2 items-center">
                        @include('icons.back', [
                            'color' => color(5),
                            'width' => '20',
                            'height' => '16',
                        ])
                        {{ __(' Dashboard') }}
                    </a>
                </div>
                <div class="">
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 !rounded-md" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]).'?report_vertical=true' }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium py-0.5">{{ __('View Report') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>
        </div>

        <div class="">
            <div class="max-w-2xl mx-auto mt-10 text-center">
                <label class="text-xl font-bold text-esg5"> {{ __('Welcome to the questionnaire’s dashboard!') }} </label>
                <p class="mt-4 text-lg text-esg16">
                    {{ __('This is the data visualization of the answers given on the questionnaire.Select a category to control what is being showed.') }}
                </p>
            </div>

            <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer"
                    x-on:click="main = true, environment = false, social = false, governance = false"
                    :class="main ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Main') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main = false, environment = true, social = false, governance = false"
                    :class="environment ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Environment') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main = false, environment = false, social = true, governance = false"
                    :class="social ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Social') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main = false, environment = false, social = false, governance = true"
                    :class="governance ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Governance') }}</label>
                    </div>
                </div>
            </div>

            {{-- Main --}}
            <div x-show="main">
                <div class="mt-5 pagebreak">
                    <div class="grid grid-cols-1 gap-5 mt-5">
                        @php $text = json_encode([__('Action Plans - Prority Matrix')]); @endphp

                        @if ($action_plan_table)
                            @php $text = json_encode([__('Action Plans')]); @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ $text }}"
                                pagination="{{ true }}" class="!py-4" contentplacement="none">
                                <div id="action_list" class="md:col-span-1 lg:mt-0 h-[450px] overflow-x-auto">
                                    <table class="text-esg8/70 font-encodesans w-full table-auto">
                                        <thead class="">
                                            <tr class="text-left text-sm font-medium uppercase">
                                                <th class="p-2">#</th>
                                                <th class="p-2">@include('icons.category', [
                                                    'width' => 12,
                                                    'height' => 12,
                                                    'color' => '#757575',
                                                ])</th>
                                                <th class="p-2">{{ __('Actions') }}</th>
                                                <th class="p-2">{{ __('Toolkit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-medium">
                                            @foreach ($action_plan_table as $initiative)
                                                <tr class="text-xs action_plan_tr action_plan_{{ $loop->index + 1 }}">
                                                    <td class="p-2">{{ $loop->index + 1 }}</td>
                                                    <td class="p-2 text-center" data-t="{{ $initiative->id }}">
                                                        @if ($initiative->category_id)
                                                            @include(
                                                                'icons.categories.' .
                                                                    ($initiative->category->parent_id ??
                                                                        $initiative->category_id))
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="p-2">{{ $initiative->name }}</td>
                                                    <td class="p-2">
                                                        @if (Storage::disk('toolkits')->exists($initiative->id . '.pdf'))
                                                            <div class="pl-4">
                                                                @include('icons.a-download', [
                                                                    'href' => tenantPrivateAsset(
                                                                        $initiative->id . '.pdf',
                                                                        'toolkits'),
                                                                ])
                                                            </div>
                                                        @else
                                                            <div class="pl-4">
                                                                @include('icons.a-download', [
                                                                    'href' => tenantPrivateAsset(
                                                                        'toolkit-desenvolvimento.pdf',
                                                                        'toolkits'),
                                                                ])
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        @endif

                    </div>
                </div>

                <div class="mt-10 pagebreak">
                    @php $text = json_encode([__('Documentation and policies')]); @endphp
                    <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto"
                        contentplacement="none">
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 mt-5">
                            @php $chart  = $charts['main']['categories']['document_and_policies']; @endphp
                            @foreach ($chart['indicators'] as $document)
                                <div class="flex items-center justify-between mr-4 mb-5 pb-2 border-b border-b-esg7/40">
                                    <label for="checkbox-website"
                                        class="font-encodesans text-base font-normal text-esg8 w-9/12">{{ $document['indicator_name'] }}</label>
                                    <div class="">
                                        @include(
                                            'icons.' . ($document['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($document['value'] === 'yes' ? 5 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            {{-- Envviroment --}}
            <div class="mt-10 pagebreak print:mt-20" x-show="environment" x-init="setTimeout(() => { show = true }, 1000)">
                <div class="px-8 bg-esg2/10 rounded-xl py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Energy intensity')]) }}"
                                class="!h-auto" contentplacement="none">
                                @php $chart = $charts['environment']['categories']['energy_intensity']; @endphp
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$chart['total']" :unit="$chart['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                    </div>
                                    <div class="-mt-3">
                                        @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            @php
                                $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Renewable')], ['color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable')]]);
                            @endphp
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('energy consumption')]) }}" type="none" class="!h-auto"
                                subpoint="{{ $subpoint }}" contentplacement="none">
                                @php $chart = $charts['environment']['categories']['energy_consumption']['categories']; @endphp
                                <div class="mb-8">
                                    <x-charts.donut id="energy_consumption_baseline"
                                        class="m-auto !h-[180px] !w-[180px] mt-5" legendes="true" x-init="$nextTick(() => {
                                            pieCharts([
                                                    '{{ __('Renewable') }}', '{{ __('Non-renewable') }}'
                                                ],
                                                [
                                                    {{ $chart['renewable_sources']['indicators'][1]['value'] }},
                                                    {{ $chart['non_renewable_sources']['indicators'][0]['value'] }}
                                                ],
                                                'energy_consumption_baseline',
                                                ['#008131', '#99CA3C'],
                                                '{{ __($chart['renewable_sources']['unit'] ?? 'MWh') }}'
                                            );
                                        })" />
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            @php
                                $subpoint = json_encode([['color' => 'bg-[' . color(2) . ']', 'text' => __('Reporting period: 2020')]]);
                                $chart = $charts['environment']['categories']['ghg_emissions_chart']['categories'];
                                $subinfo = json_encode([['value' => formatNumber(array_sum(array_column($chart, 'total'))), 'unit' => 't CO2 eq', 'color' => 'text-[' . color(2) . ']']]);

                                $unit = $charts['environment']['categories']['ghg_emissions_chart']['categories']['scope_1']['indicators'][0]['unit_default'] ?? '';
                            @endphp
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('GHG emissions by category')]) }}" subpoint="{{ $subpoint }}"
                                class="!h-full">
                                <x-charts.bar id="co2_emissions" class="m-auto relative !h-full !w-full"
                                    unit="{{ $unit }}" subinfo="{{ $subinfo }}" x-init="$nextTick(() => {
                                        barCharts(
                                            {{ json_encode(array_column($chart, 'name')) }},
                                            {{ json_encode(array_column($chart, 'total')) }},
                                            'co2_emissions',
                                            ['{{ color(2) }}']
                                        );
                                    })" />

                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        @php $chart = $charts['environment']['categories']['main_sources_of_scope']['categories']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Main sources of scope 1 emissions')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="w-8/12">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ __('Present in biodiversity sensitive areas') }}</p>
                                </div>
                                <div class="">
                                    <span class="text-lg font-medium text-esg8">
                                        <x-number :value="$chart['scope_1']['total']" />
                                        <span class="text-xs font-normal text-esg16">
                                            {{ $chart['scope_1']['indicators'][0]['unit_default'] ?? '' }} </span>
                                    </span>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Main sources of scope 3 emissions')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="w-8/12">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ __('Biogenic CO2 emissions derived from biomass burning or biodegradation throughout the value chain') }}
                                    </p>
                                </div>
                                <div class="">
                                    <span class="text-lg font-medium text-esg8">
                                        <x-number :value="$chart['scope_3']['total']" />
                                        <span class="text-xs font-normal text-esg16">
                                            {{ $chart['scope_3']['indicators'][0]['unit_default'] ?? '' }} </span>
                                    </span>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG emissions removed/stored: Natural removal (forest)')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['ghg_natural_removal']['total']" :unit="$chart['ghg_natural_removal']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.18.forest', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG emissions removed/stored: Storage through technology')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['ghg_storage_through']['total']" :unit="$chart['ghg_storage_through']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />

                                </div>
                                <div class="-mt-3">
                                    @include('icons.dashboard.18.cpu', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        @php $chart = $charts['environment']['categories']['water_and_marine_resources']['categories']; @endphp

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water consumed')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['water_consumed']['total']" :unit="$chart['water_consumed']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.18.tap', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water discharged')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['water_discharged']['total']" :unit="$chart['water_discharged']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.18.water', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water treated')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['water_treated']['total']" :unit="$chart['water_treated']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.18.save-water', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Hazardous waste')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['hazardous_waste']['total']" :unit="$chart['hazardous_waste']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.trash', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        @php $chart = $charts['environment']['categories']['biodiversity_impact']['categories']; @endphp

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Biodiversity Impact')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($chart['list']['indicators'] as $indicator)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="w-10/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $indicator['indicator_name'] }}
                                        </p>
                                    </div>
                                    <div class="">
                                        @include(
                                            'icons.' . ($indicator['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($indicator['value'] === 'yes' ? 2 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $shouldShowValues = array_filter($chart['list']['indicators'], function ($indicator) {
                                return $indicator['value'] === 'yes' && $indicator['indicator_id'] == 5638;
                            });
                        @endphp

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('operations in or near protected areas')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            @foreach ($chart['values']['indicators'] as $indicator)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="w-9/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ __('Number of operations of the organisation') }}</p>
                                    </div>
                                    <div class="">
                                        <span class="text-lg font-medium text-esg8">
                                            <x-number :value="$shouldShowValues ? $indicator['value'] : 0" />
                                            <span class="text-xs font-normal text-esg16"> {{ __('operations') }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Activities Impact')]) }}"
                        class="!h-auto !mt-5" type="none" contentplacement="none">
                        @php $chart = $charts['environment']['categories']['organisation_activities_impact']; @endphp

                        @foreach ($chart['indicators'] as $key => $indicator)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full py-3">
                                <div class="w-10/12">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $indicator['indicator_name'] }}</p>
                                </div>
                                <div class="">
                                    @include(
                                        'icons.' . ($indicator['value'] === 'yes' ? 'checkbox' : 'no'),
                                        ['color' => color($indicator['value'] === 'yes' ? 2 : 7)]
                                    )
                                </div>
                            </div>
                        @endforeach

                    </x-cards.card-dashboard-version1-withshadow>
                    @php
                        function getUnit($key)
                        {
                            $units = [
                                'average_tons_CO2_per_passenger_km' => 't CO2',
                                'average_gCO2_per_mj' => 'gCO2/MJ',
                                'average_percentage_high_carbon_technologies' => '%',
                                'average_tons_CO2_per_gj' => 't CO2/GJ',
                                'average_tons_CO2_per_ton' => 't CO2/t',
                                'average_tons_CO2_per_mwh' => 't CO2/MWh',
                                'average_percentage_of_sustainable_jet_fuels' => '%',
                            ];

                            return $units[$key] ?? '';
                        }
                    @endphp

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Specific sectors')]) }}"
                        class="!h-auto !mt-5" type="none" contentplacement="none">

                        @foreach ($charts['environment']['categories']['specific_sectors']['categories'] as $categoryKey => $category)
                            <div class="" x-data="{ show: false }">
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full py-3">
                                    <div class="w-9/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ __($category['name']) }}</p>
                                    </div>
                                    <div class="flex items-center gap-10">
                                        <span class="text-sm text-esg16 underline cursor-pointer" x-show="!show"
                                            x-on:click="show = ! show"> {{ __('Show') }} </span>
                                        <span class="text-sm text-esg16 underline cursor-pointer" x-show="show"
                                            x-on:click="show = ! show"> {{ __('hide') }} </span>
                                        @include(
                                            'icons.' .
                                                ($category['indicators'][0]['value'] === 'yes'
                                                    ? 'checkbox'
                                                    : 'no'),
                                            [
                                                'color' => color(
                                                    $category['indicators'][0]['value'] === 'yes' ? 2 : 7),
                                            ]
                                        )


                                    </div>
                                </div>

                                <div x-show="show">
                                    @foreach ($category['categories'] as $subCategoryKey => $subCategory)
                                        <div
                                            class="flex items-center justify-between border-b border-b-esg7/40 w-full py-3 px-10 bg-esg7/10">
                                            <div class="w-9/12">
                                                <p for="checkbox-website"
                                                    class="font-encodesans text-sm font-normal text-esg8">
                                                    {{ __($subCategory['name']) }}</p>
                                            </div>
                                            <div class="flex items-center gap-10">
                                                <span class="text-lg font-medium text-esg8">
                                                    {{ __($subCategory['total']) }} <span
                                                        class="text-xs font-normal text-esg16">
                                                        {{ getUnit($subCategoryKey) }} </span></span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </x-cards.card-dashboard-version1-withshadow>

                </div>
            </div>

            {{-- Social --}}
            <div class="mt-10 pagebreak print:mt-20" x-show="social">
                <div class="px-8 bg-esg1/10 rounded-3xl py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted workers')]) }}"
                            type="flex" class="!h-[270px]" contentplacement="justify-center">
                            <x-charts.donut id="contracted_workers" class="m-auto !h-[180px] !w-[180px]"
                                legendes="true" />
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('subcontracted workers')]) }}" type="flex" class="!h-[270px]"
                            contentplacement="justify-center">
                            <x-charts.donut id="outsourced_workers" class="m-auto !h-[180px] !w-[180px]"
                                legendes="true" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="py-8">
                        @php $chart = $charts['social']['categories']['workers_of_the_organisation']['categories']['migrant_workers']['indicators']; @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Migrant workers')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                                <label for="checkbox-website"
                                    class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{ __('Migrant workers in its contracted workforce or value chain') }}</label>
                                <div class="">
                                    @include(
                                        'icons.' . ($chart[0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                        ['color' => color($chart[0]['value'] === 'yes' ? 1 : 7)]
                                    )
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @php $chart = $charts['social']['categories']['workers']['categories']['turnover']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('contracted Workers Turnover')]) }}" class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['total']" :unit="$chart['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.workers', [
                                        'color' => $chart['total'] != null ? color(1) : color(7),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        @php $chart = $charts['social']['categories']['workers']['categories']['gender_pay_gap']; @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender pay gap')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['total']" :unit="$chart['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.money-hand', [
                                        'color' => $chart['total'] != null ? color(1) : color(7),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Hourly Earnings Variation (Total)')]) }}" class="!h-full">
                            @php
                                $unit = $charts['social']['categories']['workers']['categories']['hourly_earnings_variation']['indicators'][0]['unit_default'] ?? '';
                            @endphp
                            <x-charts.bar id="hourly_earnings" class="m-auto relative !h-full !w-full"
                                unit="{{ $unit }}" />
                        </x-cards.card-dashboard-version1-withshadow>

                        @php $chart = $charts['social']['categories']['workers']['categories']['workers_satisfaction_and_conditions']['categories']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Workers satisfaction and conditions')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            @foreach ($chart as $key => $item)
                                <div
                                    class="flex items-center justify-between {{ $loop->first ? 'mt-10' : 'mt-5' }} border-b border-b-esg7/40 pb-3">
                                    <div class="">
                                        <p for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8 w-9/12">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>
                                    <div class="-mt-3">
                                        @include(
                                            'icons.' .
                                                ($item['indicators'][0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($item['indicators'][0]['value'] === 'yes' ? 1 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                        @php
                            $chart = $charts['social']['categories']['workers']['categories']['workers_policies_topics']['categories'];
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Topics covered in the policies to manage impacts, risks and opportunities')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($chart as $categoryKey => $category)
                                <div class="flex items-center gap-2 w-full mt-3">
                                    <div
                                        class="w-3 h-3 {{ $category['total'] == 1 || ($category['name'] === 'Other' && $category['indicators'][0]['value'] != null) ? 'bg-esg1' : 'bg-esg7' }}  rounded-full">
                                    </div>
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            @if ($category['name'] === 'Other')
                                                {{ __('Other') }}
                                                @if ($category['indicators'][0]['value'] != 0)
                                                    : {{ $category['indicators'][0]['value'] }}
                                                @endif
                                            @else
                                                {{ __($category['name']) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php $chart = $charts['social']['categories']['safety_and_health_at_work']['categories']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Safety and health at Work')]) }}" class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($chart as $key => $item)
                                <div
                                    class="flex items-center justify-between {{ $loop->first ? 'mt-10' : 'mt-5' }} border-b border-b-esg7/40 pb-3">
                                    <div class="">
                                        <p for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8 w-9/12">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>
                                    <div class="-mt-3">
                                        @include(
                                            'icons.' .
                                                ($item['indicators'][0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($item['indicators'][0]['value'] === 'yes' ? 1 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php $chart = $charts['social']['categories']['training_for_the_workers']['categories']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Training for the Workers')]) }}" class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($chart as $key => $item)
                                <div
                                    class="flex items-center justify-between {{ $loop->first ? 'mt-10' : 'mt-5' }} border-b border-b-esg7/40 pb-3">
                                    <div class="">
                                        <p for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8 w-9/12">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>
                                    <div class="-mt-3">
                                        @include(
                                            'icons.' .
                                                ($item['indicators'][0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($item['indicators'][0]['value'] === 'yes' ? 1 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php $chart = $charts['social']['categories']['communities']['categories']['affected_communities']['categories']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Affected communities')]) }}" class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($chart as $key => $item)
                                <div
                                    class="flex items-center justify-between {{ $loop->first ? 'mt-10' : 'mt-5' }} border-b border-b-esg7/40 pb-3">
                                    <div class="">
                                        <p for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8 w-9/12">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>
                                    <div class="-mt-3">
                                        @include(
                                            'icons.' .
                                                ($item['indicators'][0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($item['indicators'][0]['value'] === 'yes' ? 1 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $chart = $charts['social']['categories']['communities']['categories']['community_investments']['categories'];
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Type of investments made in the community')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            @foreach ($chart as $categoryKey => $category)
                                <div class="flex items-center gap-2 w-full mt-3">
                                    <div
                                        class="w-3 h-3 {{ $category['total'] == 1 || ($category['name'] === 'Other' && $category['indicators'][0]['value'] != null) ? 'bg-esg1' : 'bg-esg7' }}  rounded-full">
                                    </div>
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            @if ($category['name'] === 'Other')
                                                {{ __('Other') }}
                                                @if ($category['indicators'][0]['value'] != 0)
                                                    : {{ $category['indicators'][0]['value'] }}
                                                @endif
                                            @else
                                                {{ __($category['name']) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php $chart = $charts['social']['categories']['consumers_and_end_users']['categories']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Consumers and End-Users')]) }}" class="!h-min" type="none"
                            contentplacement="none">
                            @foreach ($chart as $key => $item)
                                <div
                                    class="flex items-center justify-between {{ $loop->first ? 'mt-10' : 'mt-5' }} border-b border-b-esg7/40 pb-3">
                                    <div class="">
                                        <p for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8 w-9/12">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>
                                    <div class="-mt-3">
                                        @include(
                                            'icons.' .
                                                ($item['indicators'][0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($item['indicators'][0]['value'] === 'yes' ? 1 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            {{-- Governance --}}
            <div class="mt-10 print:mt-20">
                <div class="px-8 bg-esg3/10 rounded-3xl py-8" x-show="governance">
                    @php
                        $chart = $charts['governance']['categories']['structure']['categories']['governance_body_structure']['indicators'];
                        $isConstitutedAndStructured = strtolower($chart[0]['value']) == 'yes';
                    @endphp

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__($chart[0]['indicator_name'])]) }}" class="!h-auto "
                        contentplacement="none">
                        @if ($isConstitutedAndStructured)
                            @foreach (array_slice($chart, 1) as $indicator)
                                @if (!is_null($indicator['value']))
                                    <div class="flex items-center justify-between w-full">
                                        <label for="checkbox-website"
                                            class="font-encodesans text-lg font-medium text-esg8">
                                            @switch($indicator['value'])
                                                @case('mixed')
                                                    {{ __('Mixed') }}
                                                @break

                                                @case('management-board')
                                                    {{ __('Management Board') }}
                                                @break

                                                @case('board-of-directors-with-no-executive-members')
                                                    {{ __('Board of Directors with no Executive Members') }}
                                                @break

                                                @case('board-of-directors-with-executive-members')
                                                    {{ __('Board of Directors with Executive Members') }}
                                                @break
                                            @endswitch
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="flex items-center justify-between w-full">
                                <label for="checkbox-website"
                                    class="font-encodesans text-lg font-medium text-esg8">{{ __('Not Constituted and Structured') }}</label>
                            </div>
                        @endif
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Highest governance bo distribution by gender')]) }}"
                            type="flex" contentplacement="justify-center" class="!h-auto">
                            <x-charts.donut id="gender_high_governance_body" class=" !h-[180px] !w-[180px]"
                                legendes="true" />
                        </x-cards.card-dashboard-version1-withshadow>

                        <div class="grid grid-cols-1 gap-5">
                            @php $chart = $charts['governance']['categories']['significant_incidents_risks']['categories']['discrimination_incidents']['categories']; @endphp
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Discrimination incidents')]) }}" class="!h-min"
                                type="none" contentplacement="none">
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="">
                                        <p for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8 w-9/12">
                                            {{ $chart['incidents_of_discrimination']['name'] }}
                                        </p>
                                    </div>
                                    <div>
                                        @include(
                                            'icons.' .
                                                ($chart['incidents_of_discrimination']['indicators'][0][
                                                    'value'
                                                ] === 'yes'
                                                    ? 'checkbox'
                                                    : 'no'),
                                            [
                                                'color' => color(
                                                    $chart['incidents_of_discrimination']['indicators'][0][
                                                        'value'
                                                    ] === 'yes'
                                                        ? 3
                                                        : 7),
                                            ]
                                        )
                                    </div>
                                </div>

                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('incidents of discrimination')]) }}" class="!h-min"
                                contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$chart['number_of_incidents_of_discrimination']['total']" :unit="__('incidents')" :isNumber=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.discrimination', [
                                            'color' => color(3),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        @php
                            $chart = $charts['governance']['categories']['significant_incidents_risks']['categories']['corruption_and_bribery_assessment_and_risks']['categories'];
                        @endphp

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Risks and Risk Management Practices')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            @foreach ($chart as $key => $item)
                                <div
                                    class="flex items-center justify-between {{ $loop->first ? 'mt-10' : 'mt-5' }} border-b border-b-esg7/40 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>
                                    <div class="">
                                        @include(
                                            'icons.' .
                                                ($item['indicators'][0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($item['indicators'][0]['value'] === 'yes' ? 3 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $chart = $charts['governance']['categories']['corruption_bribery_risks']['categories'];
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Types of risks identified in the corruption and/or bribery assessment')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($chart as $categoryKey => $category)
                                <div class="flex items-center gap-2 w-full mt-3">
                                    <div
                                        class="w-3 h-3 {{ $category['total'] == 1 || ($category['name'] === 'Other' && $category['indicators'][0]['value'] != null) ? 'bg-esg3' : 'bg-esg7' }}  rounded-full">
                                    </div>
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            @if ($category['name'] === 'Other')
                                                {{ __('Other') }}
                                                @if ($category['indicators'][0]['value'] != 0)
                                                    : {{ $category['indicators'][0]['value'] }}
                                                @endif
                                            @else
                                                {{ __($category['name']) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php $chart = $charts['governance']['categories']['corruption_and_bribery']['categories']['corruption_and_bribery_prevention']['categories']; @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Corruption and Bribery Prevention')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            @foreach ($chart as $key => $item)
                                <div
                                    class="flex items-center justify-between {{ $loop->first ? 'mt-10' : 'mt-5' }} border-b border-b-esg7/40 pb-3">
                                    <div class="">
                                        <p for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8 w-10/12">
                                            {{ $item['name'] }}
                                        </p>
                                    </div>
                                    <div class="">
                                        @include(
                                            'icons.' .
                                                ($item['indicators'][0]['value'] === 'yes' ? 'checkbox' : 'no'),
                                            ['color' => color($item['indicators'][0]['value'] === 'yes' ? 3 : 7)]
                                        )
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $subpoint = json_encode([['color' => 'bg-[#058894]', 'text' => __('Corruption')], ['color' => 'bg-[#83D2DA]', 'text' => __('Bribery')]]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('cases of corruption and/or bribery')]) }}"
                            subpoint="{{ $subpoint }}" class="!h-full">
                            <x-charts.bar id="cases_corruption" class="m-auto relative !h-full !w-full" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    @php
                        $chart = $charts['governance']['categories']['corruption_and_bribery']['categories'];
                    @endphp
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('confirmed cases of corruption and/or bribery in which contracts with business partners were terminated or not renewed as a result of violations related to corruption and/or bribery')]) }}"
                        class="!h-auto !mt-5" type="none" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                            <div class="w-10/12">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Corruption') }}</p>
                            </div>
                            <div class="">
                                <span class="text-lg font-medium text-esg8">
                                    <x-number :value="$chart['corruption_terminated_contracts']['total']" />
                                    <span class="text-xs font-normal text-esg16"> {{ __('cases') }} </span></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-1 pb-3">
                            <div class="w-10/12">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Bribery') }}</p>
                            </div>
                            <div class="">
                                <span class="text-lg font-medium text-esg8">
                                    <x-number :value="$chart['bribery_terminated_contracts']['total']" />
                                    <span class="text-xs font-normal text-esg16"> {{ __('cases') }} </span></span>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    @php
                        $chart = $charts['governance']['categories']['corruption_and_bribery']['categories'];
                    @endphp
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Number of legal proceedings related to corruption and/or bribery have been initiated against the organisation or its workers')]) }}"
                        class="!h-auto !mt-5" type="none" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                            <div class="w-10/12">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Corruption') }}</p>
                            </div>
                            <div class="">
                                <span class="text-lg font-medium text-esg8">
                                    <x-number :value="$chart['corruption_legal_proceedings']['total']" />
                                    <span class="text-xs font-normal text-esg16"> {{ __('cases') }} </span></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-1 pb-3">
                            <div class="w-10/12">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Bribery') }}</p>
                            </div>
                            <div class="">
                                <span class="text-lg font-medium text-esg8">
                                    <x-number :value="$chart['bribery_legal_proceedings']['total']" />
                                    <span class="text-xs font-normal text-esg16"> {{ __('cases') }} </span></span>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        @php
                            $chart = $charts['governance']['categories']['financial_information']['categories'];
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Annual revenue')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['annual_revenue']['total']" :unit="__($chart['annual_revenue']['unit'] ?? '€')" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.18.increase', ['color' => color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Annual net revenue')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['annual_net_revenue']['total']" :unit="__('€')" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.18.increase', ['color' => color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
