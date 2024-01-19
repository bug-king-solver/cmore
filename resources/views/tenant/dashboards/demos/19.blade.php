@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' =>'bg-esg4'])

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
            #launcher, #footer {
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
            size: A4; /* DIN A4 standard, Europe */
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

            pieCharts(
                {!! json_encode([__('Man'), __('Woman'), __('Other')]) !!},
                {!! json_encode([8, 1, 1]) !!},

                'gender_equility_employees',
                [social_female, social_male, social_other],
                '{{ __('employees') }}'
            );

            pieCharts(
                {!! json_encode([__('Man'), __('Woman'), __('Other')]) !!},
                {!! json_encode([8, 1, 1]) !!},
                'gender_high_governance_body',
                [color_female, color_male, color_other],
                '{{ __('members') }}'
            );

            pieCharts(
                {!! json_encode([__('Man'), __('Woman'), __('Other')]) !!},
                {!! json_encode([8, 1, 1]) !!},
                'gender_equality_executives',
                [social_female, social_male, social_other],
                '{{ __('employees') }}'
            );

            pieCharts(
                {!! json_encode([__('Man'), __('Woman'), __('Other')]) !!},
                {!! json_encode([8, 1, 1]) !!},
                'gender_equality_leadership',
                [social_female, social_male, social_other],
                '{{ __('employees') }}'
            );

            pieCharts(
                {!! json_encode([__('Man'), __('Woman'), __('Other')]) !!},
                {!! json_encode([8, 1, 1]) !!},
                'outsourced_workers',
                [social_female, social_male, social_other],
                '{{ __('employees') }}'
            );

            // NEW
            pieCharts(
                {!! json_encode([__('Renewable'), __('Non-renewable')]) !!},
                {!! json_encode(['51.25', '153.75']) !!},
                'energy_consumption_baseline',
                [enviroment_color1, enviroment_color2],
                '{{ __('MWh') }}'
            );

            // NEW
            pieCharts(
                {!! json_encode([__('Renewable'), __('Non-renewable')]) !!},
                {!! json_encode(['51.25', '153.75']) !!},
                'energy_consumption_reporting',
                [enviroment_color1, enviroment_color2],
                '{{ __('MWh') }}'
            );

            // NEW
            pieCharts(
                {!! json_encode([__('Recicled'), __('Sent for disposal'), __('Hazardous'), __('Radioactive')]) !!},
                {!! json_encode(['3.750', '3.750', '3.750', '3.750']) !!},
                'waste_produced',
                [enviroment_color1, enviroment_color2, enviroment_color4, enviroment_color3],
                '{{ __('t') }}'
            );

            // Bar charts
            // NEW
            barCharts(
                {!! json_encode([2019, 2020]) !!},
                {!! json_encode(['513.10', '751.03']) !!},
                'atmospheric_pollutants',
                ["#008131", "#99CA3C"]
            );

            // NEW
            barCharts(
                {!! json_encode([2019, 2020]) !!},
                {!! json_encode(['513.10', '751.03']) !!},
                'ozone_layer',
                ["#008131", "#99CA3C"]
            );

            // NEW
            barCharts(
                {!! json_encode([2019, 2020]) !!},
                {!! json_encode(['513.10', '751.03']) !!},
                'water_consumed',
                ["#008131", "#99CA3C"]
            );

                barCharts(
                    {!! json_encode([2019, 2020]) !!},
                    {!! json_encode(['513.10', '751.03']) !!},
                    'co2_emissions',
                    ["#008131", "#6AD794", "#98BDA6"]
                );
        });

        // Common function for bar charts
        function barCharts(labels, data, id, barColor, unit = '')
        {
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
                        formatter: function (value) {

                            return value + unit;
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
        function pieCharts(labels, data, id, barColor, centertext = '')
        {
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
                    let text = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));''
                    ctx.fillText(text, width / 2, height / 3 + top + 20);

                    ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                    let newtext = (centertext !== undefined ?  centertext : '-');
                    ctx.fillText(newtext, width / 2, height / 3 + top + 45);
                },
                afterInit: function (chart, args, options) {
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

                                const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                                let percentag = Math.round(value * 100 / sum) + '%';

                                if (id != 'energy_consumption_reporting' && id != 'energy_consumption_baseline') {
                                    html += `
                                        <div class="grid w-full grid-cols-3">
                                            <div class="col-span-2 flex items-center">
                                                <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}"></div>
                                                <div class="inline-block text-sm text-esg8">${labelText}</div>
                                            </div>
                                            <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value})</div>
                                        </div>
                                    `;
                                } else {
                                    html += `
                                    <div class="">
                                        <div class="text-center text-sm text-esg8 leading-8"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value} ${centertext})</div>
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
                            formatter: function (value) {
                                var total = data;
                                const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
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
    <div class="px-4 lg:px-0" x-data="{main: false, environment: true, social:true, governance:true}">

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
                        {{ __('Result - Screen Dashboard') }}
                    </a>
                </div>
                <div class="">
                    {{-- <x-buttons.btn-icon-text class="bg-esg5 text-esg4 !rounded-md" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]).'?print=true' }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium py-0.5">{{ __('View Report') }}</span>
                    </x-buttons.btn-icon-text> --}}
                </div>
            </div>
        </div>

        <div class="">
            <div class="max-w-2xl mx-auto mt-10 text-center">
                <label class="text-xl font-bold text-esg5"> {{ __('Welcome to the questionnaire’s dashboard!') }} </label>
                <p class="mt-4 text-lg text-esg16"> {{ __('This is the data visualization of the answers given on the questionnaire.
                        Select or unselect the sections below to control what is being showed.') }} </p>
            </div>

            <div class="my-8 grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- <div class="grid place-content-center border border-esg6 rounded-md bg-esg6/10 w-full place-content-center shadow">
                    <div class="flex items-center">
                        <input id="main" type="checkbox" checked value="" name="main" class="w-4 h-4 text-esg6 bg-esg4 border-esg6 rounded" x-on:click="main = ! main">
                        <label for="main" class="w-full py-4 ml-2 text-base font-medium text-esg6">{{ __('Main') }}</label>
                    </div>
                </div> --}}

                <div class="grid place-content-center border border-esg2 rounded-md bg-esg2/10  place-content-center shadow">
                    <div class="flex items-center">
                        <input id="Environment" type="checkbox" checked value="" name="Environment" class="w-4 h-4 text-esg2 bg-esg4 border-esg2 rounded" x-on:click="environment = ! environment">
                        <label for="Environment" class="w-full py-4 ml-2 text-base font-medium text-esg2">{{ __('Environment') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border border-esg1 rounded-md bg-esg1/10  place-content-center shadow">
                    <div class="flex items-center">
                        <input id="Social" type="checkbox" value="" checked name="Social" class="w-4 h-4 text-esg1 bg-esg4 border-esg1 rounded" x-on:click="social = ! social">
                        <label for="Social" class="w-full py-4 ml-2 text-base font-medium text-esg1">{{ __('Social') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border border-esg3 rounded-md bg-esg3/10  place-content-center shadow">
                    <div class="flex items-center ">
                        <input id="Governance" type="checkbox" value="" checked name="Governance" class="w-4 h-4 text-esg3 bg-esg4 border-esg3 rounded" x-on:click="governance = ! governance">
                        <label for="Governance" class="w-full py-4 ml-2 text-base font-medium text-esg3">{{ __('Governance') }}</label>
                    </div>
                </div>
            </div>


            {{-- Envviroment --}}
            <div class="mt-10 pagebreak print:mt-20" x-show="environment">
                <div class="px-8 bg-esg2/10 rounded-3xl py-8">
                    <div class="border-b border-esg2">
                        <span class="font-encodesans text-xl leading-10 text-esg8 uppercase"> {{ __('Environment') }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 print:-mt-10 nonavoid">

                        {{-- NEW --}}
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: 2019') ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: 2020') ]
                                ]);

                            $subinfo = json_encode([
                                ['value' => '1.264.13', 'unit' => 'm3'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Atmospheric Pollutants')]) }}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="atmospheric_pollutants" class="m-auto relative !h-full !w-full" unit="kg" subinfo="{{ $subinfo }}"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: 2019') ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: 2020') ]
                                ]);

                            $subinfo = json_encode([
                                ['value' => '1.264.13', 'unit' => 'm3'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Ozone layer depleting substances')]) }}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="ozone_layer" class="m-auto relative !h-full !w-full" unit="kg" subinfo="{{ $subinfo }}"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW - UPDATED  --}}
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: 2019') ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: 2020') ]
                                ]);

                            $subinfo = json_encode([
                                ['value' => '1.539.30', 'unit' => 't CO2 eq', 'color' => 'text-[#008131]'],
                                ['value' => '1.200', 'unit' => 't CO2 eq', 'color' => 'text-[#99CA3C]']
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('GHG emissions by category')]) }}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="co2_emissions" class="m-auto relative !h-full !w-full" unit="t CO2 eq" subinfo="{{ $subinfo }}"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Renewable') ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable') ]
                                ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('energy consumption')]) }}" type="none" class="!h-auto" subpoint="{{ $subpoint }}" contentplacement="none">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="text-center w-full">
                                    <label class="text-xs font-medium text-esg8"> {{ __('Baseline year: 2019') }} </label>
                                    <x-charts.donut id="energy_consumption_baseline" class="m-auto !h-[180px] !w-[180px] mt-5" />
                                    <div class="grid content-center mt-5" id="energy_consumption_baseline-legend"></div>
                                </div>

                                <div class="text-center  w-full">
                                    <label class="text-xs font-medium text-esg8"> {{ __('Reporting period: 2020') }} </label>
                                    <x-charts.donut id="energy_consumption_reporting" class="m-auto !h-[180px] !w-[180px] mt-5" />
                                    <div class="grid content-center mt-5" id="energy_consumption_reporting-legend"></div>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Carbon intensity')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">1.234 <span class="text-base text-esg8">t CO2 eq / €</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.co2', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Energy intensity')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">1.234 <span class="text-base text-esg8">MWh / €</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.gestao-energia', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    {{-- NEW --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div>
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Waste produced')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                                <x-charts.donut id="waste_produced" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Recycled waste')]) }}" class="!h-full" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">20 <span class="text-base text-esg8">t</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.recicle-residue', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Waste sent for disposal')]) }}" class="!h-full" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">123.4 <span class="text-base text-esg8">t</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.residues', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5 nonavoid">
                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Hazardous waste')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">123.4 <span class="text-base text-esg8">t</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.trash', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('radioactive waste')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">123.4 <span class="text-base text-esg8">t</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.trash', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('water consumption')]) }}" class="!h-auto" type="none" contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Located in an area(s) of high water stress') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(2)])
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Recycles and/or reuses water') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(2)])
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Carries out direct discharges into the aquatic environment') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(2)])
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Has a wastewater discharge license') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(2)])
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Monitors water discharge conditions (discharge values - physico-chemical parameters)') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(2)])
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Cases of non-compliance with discharge limits') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: 2019') ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: 2020') ]
                                ]);

                            $subinfo = json_encode([
                                ['value' => '1.264.13', 'unit' => 'm3'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('water consumed')]) }}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="water_consumed" class="m-auto relative !h-full !w-full" unit="m3" subinfo="{{ $subinfo }}"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water recycled and/or reused')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">123.4 <span class="text-base text-esg8">m3</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.save-water', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water intensity')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">123.4 <span class="text-base text-esg8">m3 / € </span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.water', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5 nonavoid">
                        <div class="grid grid-cols-1 gap-5">
                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Biodiversity Impact')]) }}" class="!h-auto" type="none" contentplacement="none">
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Present in biodiversity sensitive areas') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Operations in or near protected areas and/or areas rich in biodiversity') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Activities have a negative impact on a biodiversity') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Policy or strategy to address impacts on biodiversity and ecosystems') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Raw-Materials Consumption')]) }}" class="!h-auto" type="none" contentplacement="none">
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Carried out new construction and/or major renovation work') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Organisation Activities Impact')]) }}" class="!h-auto" type="none" contentplacement="none">
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Activity fall within sectors with a high climate impact') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Active in the fossil fuel sector') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Involved in activities related to the manufacture or sale of controversial weapons') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Conducts activities that are included in the manufacture of pesticides and other agrochemical products') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Conducts activities that contribute to land degradation, desertification, artificialisation and/or soil sealing') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Conducts activities that include exploiting seas and/or oceans') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Activities in forest areas') }}</p>
                                    </div>
                                    <div class="">
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('total building materials used')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">123.4 <span class="text-base text-esg8">t</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.construction', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Recovered, recycled and/or of biological origin materials')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">123.4 <span class="text-base text-esg8">t</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.wood', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            {{-- Social --}}
            <div class="mt-10 pagebreak print:mt-20" x-show="social">
                <div class="px-8 bg-esg1/10 rounded-3xl py-8">
                    <div class="border-b border-esg1">
                        <span class="font-encodesans text-xl leading-10 text-esg8 uppercase"> {{ __('Social') }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 print:-mt-10 nonavoid">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender Equality - Employees')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            <x-charts.donut id="gender_equility_employees" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender Equality - Outsourced workers')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            <x-charts.donut id="outsourced_workers" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender Equality - Executives')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            <x-charts.donut id="gender_equality_executives" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender Equality - Leadership')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            <x-charts.donut id="gender_equality_leadership" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('contracted Workers Turnover')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">11 <span class="text-base text-esg8">%</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.workers', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender pay gap')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">30 <span class="text-base text-esg8">%</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.money-hand', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Workers satisfaction and conditions')]) }}"
                            class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                                <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Performance evaluation process for contract workers')}}</label>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Safety and health')]) }}"
                            class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                                <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Accidents at work during the reporting period')}}</label>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Number of accidents at work during the reporting'), __('period')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">30 <span class="text-base text-esg8">{{ __('accidents') }}</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.work-accident', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Days lost due to injury, accident, death or illness')]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8"> 50 <span class="text-base text-esg8">{{ __('days') }}</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.work-accident', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Training for the Workers')]) }}" class="!h-auto" type="none" contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Training and capacity development') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(1)])
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Trainning on the topics covered in the code of ethics and conduct during the reporting period') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(1)])
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Trainning on preventing and combating corruption and bribery') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Days lost due to injury, accident, death or illness')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8"> 114 <span class="text-base text-esg8">{{ __('days') }}</span></label>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.qualification', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            {{-- Governance --}}
            <div class="mt-10 print:mt-20">
                <div class="px-8 bg-esg3/10 rounded-3xl py-8" x-show="governance">
                    <div class="border-b border-esg3">
                        <span class="font-encodesans text-xl leading-10 text-esg8 uppercase"> {{ __('governance') }}</span>
                    </div>

                    {{-- NEW --}}
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Highest governance body of the organisation constituted and structured')]) }}"
                        class="!h-auto !mt-10"
                        contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                            <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Board of Directors with no Executive Members')}}</label>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Highest governance bo distribution by gender')]) }}" type="flex" contentplacement="none" class="!h-[270px]">
                            <x-charts.donut id="gender_high_governance_body" class=" !h-[180px] !w-[180px]" legendes="true"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        {{-- NEW --}}
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Significant Incidents')]) }}" class="!h-auto" type="none" contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Convicted of violations of anti-corruption and anti-bribery laws') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(3)])
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-b border-b-esg7/40 mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ __('Incidents of discrimination, in particular resulting in the application of sanctions') }}</p>
                                </div>
                                <div class="">
                                    @include('icons.checkbox', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div class="grid grid-cols-1 gap-5">
                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Amount of the fines imposed') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">1.234 <span class="text-base text-esg8">€</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.fine', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('incidents of discrimination') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">1.234 <span class="text-base text-esg8">{{ __('incidents') }}</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.discrimination', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Annual revenue') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">1.234.567 <span class="text-base text-esg8">€</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.money-hand', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Annual net revenue') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website" class="font-encodesans text-4xl font-medium text-esg8">234.567 <span class="text-base text-esg8">€</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.money-hand', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Annual reporting')]) }}" class="!h-full" type="none" contentplacement="none">
                                <div class="grid grid-cols-1 md:grid-cols-1 mt-10">
                                    <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                        <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{__('Financial report')}}</label>
                                        <div class="">
                                            @include('icons.checkbox', ['color' => true ? color(3) : color(7)])
                                        </div>
                                    </div>

                                    {{-- NEW --}}
                                    <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                        <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{__('Impact report')}}</label>
                                        <div class="">
                                            @include('icons.checkbox', ['color' => true ? color(3) : color(7)])
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                        <label for="checkbox-policy" class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{__('Sustainability report')}}</label>
                                        <div class="">
                                            @include('icons.checkbox', ['color' => true ? color(3) : color(7)])
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                        <label for="checkbox-pa" class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{__('Activities Report')}}</label>
                                        <div class="">
                                            @include('icons.checkbox', ['color' => true ? color(3) : color(7)])
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                        <label for="checkbox-ethics" class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{__('Sales Report')}}</label>
                                        <div class="">
                                            @include('icons.checkbox', ['color' => true ? color(3) : color(7)])
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                        <label for="checkbox-environmental" class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{__('Customer Satisfaction Report')}}</label>
                                        <div class="">
                                            @include('icons.checkbox', ['color' => true ? color(3) : color(7)])
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                        <label for="checkbox-safety" class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{__('Employee Satisfaction Report')}}</label>
                                        <div class="">
                                            @include('icons.checkbox', ['color' => true ? color(3) : color(7)])
                                        </div>
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Strategic Sustainable Development Goals')]) }}" class="!h-auto !mt-5">
                        <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                            <div class="grid grid-cols-4 md:grid-cols-9 gap-3 mt-10 ">
                                <div class="w-full">
                                    @include('icons.goals.1', ['class' => 'inline-block', 'color' => true ? '#EA1D2D' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.2', ['class' => 'inline-block', 'color' => true ? '#D19F2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.3', ['class' => 'inline-block', 'color' => true ? '#2D9A47' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.4', ['class' => 'inline-block', 'color' => true ? '#C22033' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.5', ['class' => 'inline-block', 'color' => true ? '#EF412A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.6', ['class' => 'inline-block', 'color' => true ? '#00ADD8' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.7', ['class' => 'inline-block', 'color' => true ? '#FDB714' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.8', ['class' => 'inline-block', 'color' => true ? '#8F1838' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.9', ['class' => 'inline-block', 'color' => true ? '#F36E24' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.10', ['class' => 'inline-block', 'color' => true ? '#E01A83' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.11', ['class' => 'inline-block', 'color' => true ? '#F99D25' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.12', ['class' => 'inline-block', 'color' => true ? '#CD8B2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.13', ['class' => 'inline-block', 'color' => true ? '#48773C' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.14', ['class' => 'inline-block', 'color' => true ? '#007DBB' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.15', ['class' => 'inline-block', 'color' => true ? '#40AE49' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.16', ['class' => 'inline-block', 'color' => true ? '#00558A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.17', ['class' => 'inline-block', 'color' => true ? '#1A3668' : '#DCDCDC'])
                                </div>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    {{-- NEW --}}
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('sustainability-related initiatives or principles')]) }}" class="!h-auto !mt-5" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                            <div class="">
                                <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Signatory or involved in external sustainability-related initiatives or principles')}}</label>
                            </div>
                            <div class="">
                                @include('icons.checkbox', ['color' =>  color(3)])
                            </div>
                        </div>

                        <div class="flex items-center justify-center gap-12 py-8">
                            @include('icons.dashboard.9.logos.sbt')
                            @include('icons.dashboard.9.logos.bcorp')
                            @include('icons.dashboard.9.logos.pri')
                            @include('icons.dashboard.9.logos.unglobal')
                            @include('icons.dashboard.9.logos.wbcsd')
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>
        </div>
    </div>
@endsection
