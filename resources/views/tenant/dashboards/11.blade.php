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
        var color_green = '#008131',
            color_gray = '#f6f6f6';

        var color_male = "#058894",
            color_female = "#06A5B4",
            color_other = "#83D2DA";

        var social_male = "#21A6E8",
            social_female = "#C5A8FF",
            social_other = "#02C6A1";

        var enviroment_color1 = "#008131",
            enviroment_color2 = "#99CA3C",
            enviroment_color3 = "#6AD794",
            enviroment_color4 = "#98BDA6";

        var governance_color = '#06A5B4';

        document.addEventListener('DOMContentLoaded', () => {
            var color_code = twConfig.theme.colors.esg7;

            // Pie charts
            @if ($energy_consumption != null)
                pieCharts(
                    {!! json_encode($energy_consumption['labels']) !!},
                    {!! json_encode($energy_consumption['data']) !!},
                    'energy_consumption',
                    [enviroment_color1, enviroment_color2],
                    '{{ $energy_consumption['unit'] ?? 'MWh' }}'
                );
            @endif

            @if ($high_governance_body != null)
                pieCharts(
                    {!! json_encode($high_governance_body['labels']) !!},
                    {!! json_encode($high_governance_body['data']) !!},
                    'gender_high_governance_body',
                    ['#058894', '#06A5B4', '#83D2DA'],
                    '{{ __($high_governance_body['unit'] ?? 'members') }}'
                );
            @endif

            @if ($waste_produced != null)
                pieCharts(
                    {!! json_encode($waste_produced['labels']) !!},
                    {!! json_encode($waste_produced['data']) !!},
                    'waste_produced',
                    ['#008131', '#99CA3C'],
                    '{{ $waste_produced['unit'] ?? 't' }}'
                );
            @endif


            // BAR CHARTS
            @if ($ghg != null)
                barCharts(
                    {!! json_encode($ghg['labels']) !!},
                    {!! json_encode($ghg['data']) !!},
                    'ghg_emissin',
                    ["#008131", "#6AD794", "#98BDA6"]
                );
            @endif

            @if ($gender_distribution != null)
                barCharts(
                    {!! json_encode($gender_distribution['labels']) !!},
                    {!! json_encode($gender_distribution['data']) !!},
                    'gender_distribution',
                    [social_male, social_female, social_other],
                    'x',
                    'multi'
                );
            @endif

            @if ($industry_sector != null)
                barCharts(
                    {!! json_encode($industry_sector['labels']) !!},
                    {!! json_encode($industry_sector['data']) !!},
                    'suppliers_industry',
                    ["#06A5B4"],
                    'y'
                );
            @endif
        });

        // Common function for bar charts
        function barCharts(labels, data, id, barColor, view = 'x', type = "single", stack = null) {

            var extra = {
                id: 'centerText',
                afterInit: function(chart, args, options) {
                    if (stack != null) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '';

                        chart.data.datasets[0].data.forEach((datavale, i) => {
                            let total = data;
                            let labelText = chart.data.labels[i];
                            let value = datavale;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];

                            // const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                            // let percentag = Math.round(value * 100 / sum) + '%';

                            html += `
                                    <div class="grid w-full grid-cols-3">
                                        <div class="col-span-2 flex items-center">
                                            <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}"></div>
                                            <div class="inline-block text-sm text-esg8">${labelText}</div>
                                        </div>
                                        <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${backgroundColor}" class="text-sm font-bold">xxx</span>  (${value})</div>
                                    </div>
                                `;

                        })

                        document.getElementById(legendId).innerHTML = html;
                    }
                }
            };

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
                responsive: true,
                indexAxis: view,
                layout: {
                    padding: {
                        top: 50,
                        right: (view == 'y' ? 50 : 0),
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        color: barColor,
                        backgroundColor: hexToRgbA(barColor),
                        anchor: 'end',
                        align: (view == 'y' ? 'right' : 'top'),
                        formatter: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                scales: {
                    y: {
                        stacked: (stack != null ? true : false),
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
                        stacked: (stack != null ? true : false),
                        display: true,
                        offset: view == 'y' ? false : true,
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
            };

            return new Chart(document.getElementById(id).getContext("2d"), {
                type: 'bar',
                data: data,
                options: chartOptions,
                plugins: [ChartDataLabels, extra]
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
                    let text = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                    ''
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
                                            <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${formatNumber(value)})</div>
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

                        if (document.getElementById(legendId) !=
                            undefined) // if this legendDiv not found means that chart don't show legends.
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
    </script>
@endpush

@section('content')
    <div class="px-4 lg:px-0">
        <div class="mt-10">
            <div class="w-full flex justify-between">
                <div class="">
                    <a href="{{ route('tenant.questionnaires.index') }}"
                        class="text-esg5 w-fit text-2xl font-bold flex flex-row gap-2 items-center">
                        @include('icons.back', [
                            'color' => color(5),
                            'width' => '20',
                            'height' => '16',
                        ])
                        {{ __('Dashboard') }}
                    </a>
                </div>
                <div class="">
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 !rounded-md" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]).'?print_vertical=true' }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium py-0.5">{{ __('View Report') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>
        </div>

        <div class="" x-data="{ main: true, environment: false, social: false, governance: false }">
            <div class="max-w-2xl mx-auto mt-10 text-center">
                <label class="text-xl font-bold text-esg5"> {{ __('Welcome to the questionnaire’s dashboard!') }} </label>
                <p class="mt-4 text-lg text-esg16">
                    {{ __('This is the data visualization of the answers given on the questionnaire.
                                                                                                                        Select a tab and a category to control what is being showed.') }}
                </p>
            </div>

            <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer"
                    x-on:click="main= true, environment= false, social=false, governance=false"
                    :class="main ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Main') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main= false, environment= true, social=false, governance=false"
                    :class="environment ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer ">{{ __('Environment') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer"
                    x-on:click="main= false, environment= false, social=true, governance=false"
                    :class="social ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer ">{{ __('Social') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer"
                    x-on:click="main= false, environment= false, social=false, governance=true"
                    :class="governance ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Governance') }}</label>
                    </div>
                </div>
            </div>

            <div class="my-4 border border-esg7/30 rounded-md"></div>

            {{-- Main --}}
            <div x-show="main">
                <div class="bg-esg5/10 rounded-3xl p-8 mt-8">

                    <div class="mt-5">
                            @livewire('dashboard.charts.radar-filter', [
                                $questionnaire,
                                __('ALIGNMENT WITH SUSTAINABILITY PRINCIPLES'),
                                'alignment_principles',
                                [],
                                [
                                    'type' => 'simple',
                                    'chart' => [
                                        'legend' => [
                                            'display' => false,
                                        ],
                                    ],
                                ],
                            ])
                    </div>

                    <div class="grid grid-cols-1 gap-5 mt-5">
                        @if ($action_plan_table)
                            @php $text = json_encode([__('Action Plans')]); @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto w-full">
                                <div id="action_list"
                                    class="md:col-span-1 lg:p-5 xl:p-10 lg:mt-0 h-[500px] overflow-x-auto">

                                    <table class="text-esg8/70 font-encodesans w-full table-auto">
                                        <thead class="">
                                            <tr class="text-left text-sm font-medium uppercase">
                                                <th class="p-2">#</th>
                                                <th class="p-2">@include('icons.category', ['color' => '#444444b3'])</th>
                                                <th class="p-2">{{ __('Action') }}</th>
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
                                                            <x-buttons.a-alt
                                                                href="{{ tenantPrivateAsset($initiative->id . '.pdf', 'toolkits') }}"
                                                                text="{{ __('Download') }}" />
                                                        @else
                                                            <x-buttons.a-alt
                                                                href="{{ tenantPrivateAsset('toolkit-desenvolvimento.pdf', 'toolkits') }}"
                                                                text="{{ __('Download') }}" />
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
            </div>

            {{-- Envviroment --}}
            <div x-show="environment" x-data="{ climate: true, water: false, ecosystems: false, waste: false }">
                <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="climate ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = true, water=false, ecosystems=false, waste=false">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="climate">
                                @include('icons.dashboard.11.globalwarming', ['color' => color(2)])
                            </template>
                            <template x-if="!climate">
                                @include('icons.dashboard.11.globalwarming', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Climate impact') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="water ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = false, water=true, ecosystems=false, waste=false">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="water">
                                @include('icons.dashboard.11.water', ['color' => color(2)])
                            </template>
                            <template x-if="!water">
                                @include('icons.dashboard.11.water', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Water resources impact') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="ecosystems ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = false, water=false, ecosystems=true, waste=false">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="ecosystems">
                                @include('icons.dashboard.11.ecosystem', ['color' => color(2)])
                            </template>
                            <template x-if="!ecosystems">
                                @include('icons.dashboard.11.ecosystem', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Biodiversity and ecosystems') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="waste ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = false, water=false, ecosystems=false, waste=true">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="waste">
                                @include('icons.dashboard.11.waste', ['color' => color(2)])
                            </template>
                            <template x-if="!waste">
                                @include('icons.dashboard.11.waste', ['color' => color(16)])
                            </template>
                            <label for="main"
                                class="text-base">{{ __('Use of resources and circular economy') }}</label>
                        </div>
                    </div>
                </div>

                {{-- SECTION: climate --}}
                <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="climate">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Carbon intensity')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$carbon_intensity['value']" :unit="$carbon_intensity['unit'] ?? 't CO2 eq / €'" :isNumber=true />
                                </div>
                                <div class="-mt-7">
                                    @include('icons.dashboard.emission', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Energy intensity')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$energy_intensity['value']" :unit="$energy_intensity['unit'] ?? 'MWh / €'" :isNumber=true />
                                </div>
                                <div class="-mt-7">
                                    @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Renewable')], ['color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable')]]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Energy consumption')]) }}"
                            type="flex" class="!h-auto" contentplacement="justify-center"
                            subpoint="{{ $subpoint }}">
                            @if ($energy_consumption != null)
                                <x-charts.donut id="energy_consumption" class="m-auto !h-[180px] !w-[180px] mt-5"
                                    legendes="true" />
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Scope 1')], ['color' => 'bg-[#6AD794]', 'text' => __('Scope 2')], ['color' => 'bg-[#98BDA6]', 'text' => __('Scope 3')]]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG emission by category')]) }}" subpoint="{{ $subpoint }}"
                            class="!h-auto" titleclass="!uppercase !font-normal !text-esg16 !text-base"
                            contentplacement="none">
                            @if ($ghg != null)
                                <div class="pt-10">
                                    <x-charts.bar id="ghg_emissin" class="m-auto relative !h-full !w-full" />
                                </div>
                                <div class="flex items-end gap-2 text-sm text-esg16 font-normal place-content-center mt-5">
                                    <span>{{ __('Total') }}:</span>
                                    <span class="text-2xl text-esg8">
                                        {{ formatNumber(array_sum($ghg['data'])) }}
                                    </span>
                                    <span>{{ __($ghg['unit'] ?? 't CO2 eq') }}</span>
                                </div>
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($store_ghg as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(2)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION: water --}}
                <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="water">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water intensity')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$water_intensity['value']" :unit="$water_intensity['unit'] ?? 'm3 / €'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.enviroment.water', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water CONSUMED')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$water_consumed['value']" :unit="$water_consumed['unit'] ?? 'm3 / €'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.enviroment.water', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($water_stress as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(2)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION: ecosystems --}}
                <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="ecosystems">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($biodiversity as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(2)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- SECTION: waste --}}
                <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="waste">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Waste produced')]) }}"
                            type="none" class="!h-[290px]" contentplacement="none">
                            <div class="grid grid-cols-1 md:grid-cols-1 place-content-center gap-4">
                                <div class="text-center">
                                    @if ($waste_produced != null)
                                        <x-charts.donut id="waste_produced" class="m-auto !h-[180px] !w-[180px] mt-5"
                                            legendes="true" />
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            {{-- Social --}}
            <div x-show="social" x-data="{ organization: true, chain: false, communities: false, consumers: false, care: false }">
                <div class="my-8 grid grid-cols-1 md:grid-cols-5 gap-5">
                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="organization ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                        x-on:click="organization = true, chain= false, communities=false, consumers=false, care=false">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="organization">
                                @include('icons.dashboard.11.social.user', ['color' => color(1)])
                            </template>
                            <template x-if="!organization">
                                @include('icons.dashboard.11.social.user', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Own workers') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                        :class="chain ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                        x-on:click="organization = false, chain= true, communities=false, consumers=false, care=false">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="chain">
                                @include('icons.dashboard.11.social.user', ['color' => color(1)])
                            </template>
                            <template x-if="!chain">
                                @include('icons.dashboard.11.social.user', ['color' => color(16)])
                            </template>
                            <label for="main"
                                class="text-base">{{ __('Workers’ satisfaction and conditions') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                        :class="communities ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                        x-on:click="organization = false, chain= false, communities=true, consumers=false, care=false">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="communities">
                                @include('icons.dashboard.11.social.protech', ['color' => color(1)])
                            </template>
                            <template x-if="!communities">
                                @include('icons.dashboard.11.social.protech', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Safety and health') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                        :class="consumers ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                        x-on:click="organization=false, chain=false, communities=false, consumers=true, care=false">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="consumers">
                                @include('icons.dashboard.11.social.consumer', ['color' => color(1)])
                            </template>
                            <template x-if="!consumers">
                                @include('icons.dashboard.11.social.consumer', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Training for workers') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                        :class="care ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                        x-on:click="organization=false, chain=false, communities=false, consumers=false, care=true">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="care">
                                @include('icons.dashboard.11.social.care', ['color' => color(1)])
                            </template>
                            <template x-if="!care">
                                @include('icons.dashboard.11.social.care', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Social actions') }}</label>
                        </div>
                    </div>
                </div>

                {{-- Own workers --}}
                <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="organization">
                    @php
                        $subpoint = json_encode([['color' => 'bg-[#21A6E8]', 'text' => __('Men')], ['color' => 'bg-[#C5A8FF]', 'text' => __('Woman')], ['color' => 'bg-[#02C6A1]', 'text' => __('Other')]]);
                    @endphp
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender distribution')]) }}"
                        subpoint="{{ $subpoint }}" class="!h-min">
                        @if ($gender_distribution != null)
                            <x-charts.bar id="gender_distribution" class="m-auto relative !h-full !w-full" />
                        @else
                            <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                        @endif
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($management_positions as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(1)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($local_community as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(1)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- Workers’ satisfaction and conditions --}}
                <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="chain">
                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none" contentplacement="none">
                        @foreach ($perfomance as $row)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $row['label'] }}</p>
                                </div>
                                <div class="">
                                    @if ($row['status'])
                                        @include('icons.checkbox', ['color' => color(1)])
                                    @else
                                        @include('icons.checkbox-cancle')
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Pay gap gender')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$pay_gap['value']" :unit="$pay_gap['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', ['color' => color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('TURNOVER')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$turnover['value']" :unit="$turnover['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.social.user', [
                                        'color' => color(1),
                                        'width' => 47,
                                        'height' => 47,
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- Safety and health --}}
                <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="communities">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($osh as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(1)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- Training for workers --}}
                <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="consumers">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Training and CAPACITY DEVELOPMENT')]) }}" class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$capasity_development['value']" :unit="__($capasity_development['unit'] ?? 'hours')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.social.certificate', [
                                        'color' => color(1),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('ETHICS AND CONDUCT')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$ethic_conduct['value']" :unit="__($ethic_conduct['unit'] ?? 'hours')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.social.ethic', ['color' => color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('SOCIAL ISSUES*')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$social_issues['value']" :unit="__($social_issues['unit'] ?? 'hours')" :isNumber=true />
                                    <p class="text-xs text-esg16 mt-1">
                                        {{ __('*Human rights, forced labour or modern slavery') }}</p>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.social.customer', ['color' => color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- Social actions --}}
                <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="care">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($vulnerability as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(1)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($donation as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(1)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($corporate as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(1)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            @foreach ($mechanism as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(1)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('CORPORATE SOCIAL RESPONSIBILITY ACTIVITIES')]) }}" class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$social_activity['value']" :unit="$social_activity['unit'] ?? '€'" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', ['color' => color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            {{-- Governance --}}
            <div x-show="governance" x-data="{ culture: true, practices: false, detection: false, management: false }">

                <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="culture ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                        x-on:click="culture = true, practices= false, detection=false, management=false">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="culture">
                                @include('icons.dashboard.11.governance.conduct', ['color' => color(3)])
                            </template>
                            <template x-if="!culture">
                                @include('icons.dashboard.11.governance.conduct', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Structure') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                        :class="practices ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                        x-on:click="culture = false, practices= true, detection=false, management=false">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="practices">
                                @include('icons.dashboard.11.governance.diagram', ['color' => color(3)])
                            </template>
                            <template x-if="!practices">
                                @include('icons.dashboard.11.governance.diagram', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Practices') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                        :class="detection ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                        x-on:click="culture = false, practices= false, detection=true, management=false">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="detection">
                                @include('icons.dashboard.11.governance.supplier', ['color' => color(3)])
                            </template>
                            <template x-if="!detection">
                                @include('icons.dashboard.11.governance.supplier', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Relationship with suppliers') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                        :class="management ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                        x-on:click="culture = false, practices= false, detection=false, management=true">
                        <div class="flex items-center justify-center w-full gap-5">
                            <template x-if="management">
                                @include('icons.dashboard.11.money-hand', [
                                    'color' => color(3),
                                    'width' => 25,
                                    'height' => 28,
                                ])
                            </template>
                            <template x-if="!management">
                                @include('icons.dashboard.11.money-hand', [
                                    'color' => color(16),
                                    'width' => 25,
                                    'height' => 28,
                                ])
                            </template>
                            <label for="main" class="text-base">{{ __('Financial information') }}</label>
                        </div>
                    </div>
                </div>

                {{-- Structure --}}
                <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="culture">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow class="!h-full" type="none"
                            contentplacement="none">
                            @foreach ($mission as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('purpose and contributes to environment and society')]) }}"
                            class="!h-auto " contentplacement="none">
                            <div class="flex items-center justify-between w-full mt-5 pb-2">
                                {{ $purpose['value'] }}
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-full" type="none"
                            contentplacement="none">
                            @foreach ($goal_issue as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @if ($member_status)
                            <x-cards.card-dashboard-version1-withshadow class="!h-full" type="none"
                                contentplacement="none">
                                @foreach ($member as $row)
                                    @if ($row['status'])
                                        <div
                                            class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                            <div class="">
                                                <p for="checkbox-website"
                                                    class="font-encodesans text-sm font-normal text-esg8">
                                                    {{ $row['label'] }}</p>
                                            </div>
                                            <div class="">
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' => color(3)])
                                                @else
                                                    @include('icons.checkbox-cancle')
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>
                        @endif

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Gender distribution of members og the highest governance body')]) }}"
                            type="flex" contentplacement="none" class="!h-full">
                            @if ($high_governance_body != null)
                                <x-charts.donut id="gender_high_governance_body" class=" !h-[180px] !w-[180px]"
                                    legendes="true" />
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- Practices --}}
                <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="practices">
                    <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('GOVERNANCE BODY')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($governance_body as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="w-10/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('ETHICS AND CONDUCT')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($governance_ethic as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="w-10/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('POLICIES')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($policy as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="w-10/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('RISKS ASSESSMENT')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($risk as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="w-10/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                {{-- Relationship with suppliers --}}
                <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="detection">
                    <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="">
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Suppliers by INDUSTRY SECTOR')]) }}" class="!h-auto">

                                @if ($industry_sector != null)
                                    <x-charts.bar id="suppliers_industry" class="m-auto relative !h-full !w-full" />
                                @else
                                    <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                @endif
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Number of suppliers by industry sector')]) }}"
                                class="!h-min !mt-5" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$number_suppliers_industry_sector['value']" :unit="$number_suppliers_industry_sector['unit'] ?? '%'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.governance.world', [
                                            'color' => color(1),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('SUPPLIERS ASSESSED FOR ENVIRONMENT IMPACTS')]) }}"
                                class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$enviroment_impact['value']" :unit="$enviroment_impact['unit'] ?? '%'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.governance.world', [
                                            'color' => color(1),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('SUPPLIERS ASSESSED FOR SOCIAL IMPACTS')]) }}" class="!h-min"
                                contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$social_impact['value']" :unit="$social_impact['unit'] ?? '%'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.governance.care', [
                                            'color' => color(1),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                                contentplacement="none">
                                @foreach ($supplier as $row)
                                    <div
                                        class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="w-10/12">
                                            <p for="checkbox-website"
                                                class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}
                                            </p>
                                        </div>
                                        <div class="">
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' => color(3)])
                                            @else
                                                @include('icons.checkbox-cancle')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('payment to local suppliers / total amount paid to all suppliers')]) }}"
                                class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$supplier_paid['value']" :unit="$supplier_paid['unit'] ?? '%'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.money-hand', ['color' => color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>

                {{-- Financial information --}}
                <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="management">
                    <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('ANNUAL REVENUE')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$annual_revenue['value']" :unit="$annual_revenue['unit'] ?? '€'" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', ['color' => color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('ANNUAL NET REVENUE')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$annual_net_revenue['value']" :unit="$annual_net_revenue['unit'] ?? '€'" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', ['color' => color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('PERIODIC REPORTS')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($pridict_report as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="w-10/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('SIGNATORY OR INVOLVED IN EXTERNAL SUSTAINABILITY-RELATED INITIATIVES OR PRINCIPLES')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($pricipal as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="w-10/12">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' => color(3)])
                                        @else
                                            @include('icons.checkbox-cancle')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
