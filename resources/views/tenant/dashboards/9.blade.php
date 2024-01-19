@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' =>'bg-esg4'])

@php
$categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
$genderIconUrl = global_asset('images/icons/genders/{id}.svg');
$activeLi = 'bg-esg71 pt-[7px] pr-[9px] pb-[11px] pl-[50px] -ml-[26px] rounded-lg';
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
        [x-cloak] {
            display: none !important;
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

            // Pie charts
                @if ($waste_produced != null)
                    pieCharts(
                        {!! json_encode($waste_produced['labels']) !!},
                        {!! json_encode($waste_produced['data']) !!},
                        'waste_produced',
                        [enviroment_color1, enviroment_color2, enviroment_color4, enviroment_color3],
                        '{{ $waste_produced['unit'] ?? 'MWh' }}'
                    );
                @endif

                @if ($contract_workers != null)
                    pieCharts(
                        {!! json_encode($contract_workers['labels']) !!},
                        {!! json_encode($contract_workers['data']) !!},
                        'contracted_workers',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
                    );
                @endif

                @if ($outsource_workers != null)
                    pieCharts(
                        {!! json_encode($outsource_workers['labels']) !!},
                        {!! json_encode($outsource_workers['data']) !!},
                        'outsourced_workers',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
                    );
                @endif

                @if ($middle_management != null)
                    pieCharts(
                        {!! json_encode($middle_management['labels']) !!},
                        {!! json_encode($middle_management['data']) !!},
                        'middle_management',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
                    );
                @endif

                @if ($top_management != null)
                    pieCharts(
                        {!! json_encode($top_management['labels']) !!},
                        {!! json_encode($top_management['data']) !!},
                        'top_management',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
                    );
                @endif

                @if ($high_governance_body)
                    pieCharts(
                        {!! json_encode($high_governance_body['labels']) !!},
                        {!! json_encode($high_governance_body['data']) !!},
                        'gender_high_governance_body',
                        [color_female, color_male, color_other],
                        '{{ __('members') }}'
                    );
                @endif

                @if ($energy_consumption_baseline)
                    pieCharts(
                        {!! json_encode($energy_consumption_baseline['labels']) !!},
                        {!! json_encode($energy_consumption_baseline['data']) !!},
                        'energy_consumption_baseline',
                        [enviroment_color1, enviroment_color2],
                        '{{ $energy_consumption_baseline['unit'] ?? 'MWh' }}'
                    );
                @endif

                @if ($energy_consumption_reporting)
                    pieCharts(
                        {!! json_encode($energy_consumption_reporting['labels']) !!},
                        {!! json_encode($energy_consumption_reporting['data']) !!},
                        'energy_consumption_reporting',
                        [enviroment_color1, enviroment_color2],
                        '{{ $energy_consumption_reporting['unit'] ?? 'MWh' }}'
                    );
                @endif

            // Bar charts
                barCharts(
                    {!! json_encode($atmospheric_pollutants['labels']) !!},
                    {!! json_encode($atmospheric_pollutants['data']) !!},
                    'atmospheric_pollutants',
                    ["#008131", "#99CA3C"]
                );

                barCharts(
                    {!! json_encode($ozone_layer_depleting['labels']) !!},
                    {!! json_encode($ozone_layer_depleting['data']) !!},
                    'ozone_layer',
                    ["#008131", "#99CA3C"]
                );

                barCharts(
                    {!! json_encode($water_consumed['labels']) !!},
                    {!! json_encode($water_consumed['data']) !!},
                    'water_consumed',
                    ["#008131", "#99CA3C"]
                );

                barCharts(
                    {!! json_encode($GHG_emission['labels']) !!},
                    {!! json_encode($GHG_emission['data']) !!},
                    'co2_emissions',
                    ["#008131", "#99CA3C"]
                );

                barCharts(
                    {!! json_encode($GHG_emission2['labels']) !!},
                    {!! json_encode($GHG_emission2['data']) !!},
                    'co2_emissions2',
                    ["#008131", "#99CA3C"]
                );

                barCharts(
                    {!! json_encode($GHG_emission3['labels']) !!},
                    {!! json_encode($GHG_emission3['data']) !!},
                    'co2_emissions3',
                    ["#008131", "#99CA3C"]
                );
        });

        // Common function for bar charts
        function barCharts(labels, data, id, barColor, unit = '', type = "single")
        {
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
                        //color: barColor,
                        //backgroundColor : hexToRgbA(barColor),
                        formatter: function (value) {
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
                    ctx.fillText(formatNumber(text), width / 2, height / 3 + top + 20);

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

                                    value = (id == 'waste_produced' ? (value + ' ' + centertext) : value);

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
    <div class="px-4 lg:px-0" x-data="{main: true, environment: true, social:true, governance:true}">

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
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 !rounded-md" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]).'?print_vertical=true' }}'">
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
                <p class="mt-4 text-lg text-esg16"> {{ __('This is the data visualization of the answers given on the questionnaire.
                        Select or unselect the sections below to control what is being showed.') }} </p>
            </div>

            <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="grid place-content-center border border-esg6 rounded-md bg-esg6/10 w-full place-content-center shadow">
                    <div class="flex items-center">
                        <input id="main" type="checkbox" checked value="" name="main" class="w-4 h-4 text-esg6 bg-esg4 border-esg6 rounded" x-on:click="main = ! main">
                        <label for="main" class="w-full py-4 ml-2 text-base font-medium text-esg6">{{ __('Main') }}</label>
                    </div>
                </div>

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

            {{-- Main --}}
            <div x-show="main">
                <div class="mt-5 pagebreak">
                    {{-- TODO fix the descriptions  --}}
                    <div class="" x-data="{ selected: {{$readiness['current_level']}}, activeClass : 'bg-esg71 pt-[7px] pr-[9px] pb-[11px] pl-[50px] -ml-[26px] rounded-lg', current_level : {{$readiness['current_level']}} }">
                        @php $text = json_encode([__('Readiness level')]); @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto" type="" contentplacement="">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-6 px-9">
                                <div class="flex items-end">
                                    <ol class="relative text-esg8 border-l-2 border-l border-dashed border-esg8">
                                        {{-- Level 5 --}}
                                        <li class="mb-5" :class="selected === 5 ? activeClass : 'ml-6'">
                                            <span class="absolute flex items-center justify-center w-8 -left-4 ">
                                                @include('icons.checkbox-fill', ($readiness['current_level'] != 5 ? ['color' => color(7)] : []))
                                            </span>

                                            <div class="flex items-center gap-2" @click="current_level > 4 && selected !== 5 ? selected = 5 : selected = selected">
                                                <h3 class="text-sm font-black {{ $readiness['current_level'] != 5 ? 'text-esg7' : 'text-black cursor-pointer' }}">{{ __('Ready for the next step') }}</h3>
                                                @include('icons.dashboard.9.badge.level5.1', ($readiness['current_level'] != 5 ? ['color' => color(7)] : []))
                                            </div>
                                            <div x-cloak x-show="selected === 5" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                                                <p class="text-xs font-normal {{ $readiness['current_level'] != 5 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('This means that you are more than ready to try out the in-depth questionnaire.') }}</p>
                                            </div>
                                        </li>

                                        {{-- Level 4 --}}
                                        <li class="mb-5" :class="selected === 4 ? activeClass : 'ml-6'">
                                            <span class="absolute flex items-center justify-center w-8 -left-4 ">
                                                @include('icons.checkbox-fill', ($readiness['current_level'] > 3 ? [] : ['color' => color(7)]))
                                            </span>
                                            <div class="flex items-center gap-2" @click="current_level > 3 && selected !== 4 ? selected = 4 : selected = selected">
                                                <h3 class="text-sm font-black {{ $readiness['current_level'] > 3 ? 'text-black cursor-pointer' : 'text-esg7' }}">{{ __('Maturity Ready') }}</h3>
                                                @for ($i = 0; $i <= 4; $i++)
                                                    <x-generic.badge-tooltip :tooltip="$readiness['level4'][$i]['tooltip']">
                                                        @include(
                                                            'icons.dashboard.9.badge.level4.'.$i+1,
                                                            (! $readiness['level4'][$i]['complete'] || $readiness['current_level'] < 3 ? ['color' => color(7)] : [])
                                                        )
                                                    </x-generic.badge-tooltip>
                                                @endfor
                                            </div>
                                            <div x-cloak x-show="selected === 4" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                                                <p class="text-xs font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisation is highly committed to complying with and respecting the ESG pillars and has begun to define and implement strategies that contribute to reducing, mitigating and preventing their impacts.') }}</p>
                                                <p class="text-xs font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('It is notable for its external collaborations in the field of sustainability and for its contribution to sustainable development objectives within the scope of its activity.') }}</p>
                                                <p class="text-xs font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The efforts made by the organisation give it credibility and reputation, setting an example for others.') }}</p>
                                            </div>
                                        </li>

                                        {{-- Level 3 --}}
                                        <li class="mb-5" :class="selected === 3 ? activeClass : 'ml-6'">
                                            <span class="absolute flex items-center justify-center w-8 -left-4 ">
                                                @include('icons.checkbox-fill', ($readiness['current_level'] > 2 ? [] : ['color' => color(7)] ))
                                            </span>
                                            <div class="flex items-center gap-2" @click="current_level > 2 && selected !== 3 ? selected = 3 : selected = selected">
                                                <h3 class="text-sm font-black {{ $readiness['current_level'] > 2 ? 'text-black cursor-pointer' : 'text-esg7' }}">{{ __('Performance Ready') }}</h3>
                                                @for ($i = 0; $i <= 4; $i++)
                                                    <x-generic.badge-tooltip :tooltip="$readiness['level3'][$i]['tooltip']">
                                                        @include(
                                                            'icons.dashboard.9.badge.level3.'.$i+1,
                                                            (! $readiness['level3'][$i]['complete'] || $readiness['current_level'] < 2 ? ['color' => color(7)] : [])
                                                        )
                                                    </x-generic.badge-tooltip>
                                                @endfor
                                            </div>
                                            <div x-cloak x-show="selected === 3" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                                                <p class="text-xs font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisation monitors and communicates its performance in relation to a set of indicators relating to the three ESG pillars. It has implemented a strategy for receiving complaints in the event of non-compliance or irregularities, reflecting its commitment to stakeholders.') }}</p>
                                                <p class="text-xs font-normal {{ $readiness['current_level'] < 3 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisations performance is oriented towards transparency, credibility and evolution and, as such, continuous investment in ESG issues is encouraged.') }}</p>
                                            </div>
                                        </li>

                                        {{-- Level 2 --}}
                                        <li class="mb-5" :class="selected === 2 ? activeClass : 'ml-6'">
                                            <span class="absolute flex items-center justify-center w-8 -left-4 ">
                                                @include('icons.checkbox-fill', ($readiness['current_level'] > 1 ? [] : ['color' => color(7)]))
                                            </span>
                                            <div class="flex items-center gap-2" @click="current_level > 1 && selected !== 2 ? selected = 2 : selected = selected">
                                                <h3 class="text-sm font-black {{ $readiness['current_level'] > 1 ? 'text-black cursor-pointer' : 'text-esg7' }}">{{ __('Knowledge Ready') }}</h3>
                                                @for ($i = 0; $i <= 4; $i++)
                                                    <x-generic.badge-tooltip :tooltip="$readiness['level2'][$i]['tooltip']">
                                                        @include(
                                                            'icons.dashboard.9.badge.level2.'.$i+1,
                                                            (! $readiness['level2'][$i]['complete'] || $readiness['current_level'] < 1 ? ['color' => color(7)] : [])
                                                        )
                                                    </x-generic.badge-tooltip>
                                                @endfor
                                            </div>
                                            <div x-cloak x-show="selected === 2" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                                                <p class="text-xs font-normal {{ $readiness['current_level'] < 1 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('The organisation is committed to implementing a set of training programmes, practices and processes that ensure the implementation of the policies defined by the organisation in the areas of health and safety at work, ethics and conduct, human rights, anti-corruption and conflicts of interest.') }}</p>
                                                <p class="text-xs font-normal {{ $readiness['current_level'] < 1 ? 'text-esg7' : 'text-esg16' }} mt-1">{{ __('There is a clear effort to improve the organisations conditions with regard to ESG themes, and there is room for progress.') }}</p>
                                            </div>
                                        </li>

                                        {{-- Level 1 --}}
                                        <li class="mb-1" :class="selected === 1 ? activeClass : 'ml-6'" >
                                            <span class="absolute flex items-center justify-center w-8 -left-4 ">
                                                @include('icons.checkbox-fill', ($readiness['current_level'] > 0 ? [] : ['color' => color(7)]))
                                            </span>
                                            <div class="flex items-center gap-2" @click="current_level > 0 && selected !== 1 ? selected = 1 : selected = selected">
                                                <h3 class="text-sm font-black {{ $readiness['current_level'] > 0 ? 'text-black cursor-pointer' : 'text-esg7' }}">{{ __('Awareness Ready') }}</h3>
                                                @for ($i = 0; $i <= 3; $i++)
                                                    <x-generic.badge-tooltip :tooltip="$readiness['level1'][$i]['tooltip']">
                                                        @include(
                                                            'icons.dashboard.9.badge.level1.'.$i+1,
                                                            (! $readiness['level1'][$i]['complete'] ? ['color' => color(7)] : [])
                                                        )
                                                    </x-generic.badge-tooltip>
                                                @endfor
                                            </div>

                                            <div x-cloak x-show="selected === 1" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95">
                                                <p class="text-xs font-normal text-esg16 mt-1">{{ __('The organisation is committed to the core themes of the ESG pillars and has defined and implemented a set of basic policies relating to them.') }}</p>
                                                <p class="text-xs font-normal text-esg16 mt-1">{{ __('The organisation has the necessary foundations to ensure its future growth and development, and a commitment to acquiring additional knowledge is recommended.') }}</p>
                                            </div>
                                        </li>
                                    </ol>
                                </div>

                                <div class="flex justify-center items-start">
                                    @if ($readiness['current_level'] == 5)
                                        @include('icons.dashboard.9.readiness.5')
                                    @else
                                        @include('icons.dashboard.9.readiness.' . ($readiness['current_level'] === 0 ? 1 : $readiness['current_level']) )
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 text-center" x-data="{ open: false }">
                                <label class="text-base font-bold text-esg8"> {{ __('See achievements') }} </label>

                                <div class="grid place-content-center mt-2">
                                    <span class="hide" x-on:click="open = ! open" x-show="open">@include('icons.dashboard.9.hide')</span>
                                    <span class="show" x-on:click="open = ! open" x-show="!open">@include('icons.dashboard.9.show')</span>
                                </div>

                                <div class="mt-8" x-show="open">
                                    {{-- Level 1 --}}
                                    <div x-cloak x-show="selected <= 1">

                                        <label class="text-base font-bold text-[#009B7F]"> {{ __('Awareness Ready') }} </label>

                                        <div class="flex items-center justify-center gap-5 mt-9">
                                            @for ($i = 0; $i <= 3; $i++)
                                                <x-generic.badge-tooltip :tooltip="$readiness['level1'][$i]['tooltip']">
                                                    @include(
                                                        'icons.dashboard.9.badge.level1.'.$i+1,
                                                        (! $readiness['level1'][$i]['complete'] ? ['color' => color(7), 'width' => 60, 'height' => 60] : ['width' => 60, 'height' => 60])
                                                    )
                                                </x-generic.badge-tooltip>
                                            @endfor
                                        </div>

                                        <div class="flex gap-10 items-center justify-between text-left mt-9">
                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Assessing environmental impact') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Be aware of wage conditions and ensure health and safety conditions at work with regard to subcontractors') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Define the organisational chart') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Constitute and structure the highest governance body') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Create a remuneration policy') }} </p>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Create an occupational health and safety policy') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Create a code of ethics and conduct') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Implement a customer data privacy policy') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Implementing a supplier selection policy and codes of ethics and conduct') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Implement a policy to prevent risks of corruption and conflicts of interest') }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Level 2 --}}
                                    <div x-cloak x-show="selected == 2">

                                        <label class="text-base font-bold text-[#009B7F]"> {{ __('Knowledge Ready') }} </label>
                                        <div class="flex items-center justify-center gap-5 mt-9">
                                            @for ($i = 0; $i <= 4; $i++)
                                                <x-generic.badge-tooltip :tooltip="$readiness['level2'][$i]['tooltip']">
                                                    @include(
                                                        'icons.dashboard.9.badge.level2.'.$i+1,
                                                        (! $readiness['level2'][$i]['complete'] ? ['color' => color(7), 'width' => 60, 'height' => 60] : ['width' => 60, 'height' => 60])
                                                    )
                                                </x-generic.badge-tooltip>
                                            @endfor
                                        </div>

                                        <div class="flex gap-10 items-center justify-between text-left mt-9">
                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Take initiatives to implement occupational health and safety policy') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Make the remuneration policy available') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Implement a training programme on the topics covered in the code of ethics and conduct') }} </p>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Have the code of ethics and conduct available on the website') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Implement a due diligence process to identify, mitigate and combat negative impacts on human rights') }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Level 3 --}}
                                    <div x-cloak x-show="selected == 3">

                                        <label class="text-base font-bold text-[#009B7F]"> {{ __('Performance Ready') }} </label>
                                        <div class="flex items-center justify-center gap-5 mt-9">
                                            @for ($i = 0; $i <= 4; $i++)
                                                <x-generic.badge-tooltip :tooltip="$readiness['level3'][$i]['tooltip']">
                                                    @include(
                                                        'icons.dashboard.9.badge.level3.'.$i+1,
                                                        (! $readiness['level3'][$i]['complete'] ? ['color' => color(7), 'width' => 60, 'height' => 60] : ['width' => 60, 'height' => 60])
                                                    )
                                                </x-generic.badge-tooltip>
                                            @endfor
                                        </div>

                                        <div class="flex gap-10 items-center justify-between text-left mt-9">
                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Monitoring of environmental indicators') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Monitoring the impact on the community') }} </p>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Prepare and publish annual reports') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Implement a complaints channel for workers, customers and suppliers') }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Level 4 --}}
                                    <div x-cloak x-show="selected == 4">

                                        <label class="text-base font-bold text-[#009B7F]"> {{ __('Maturity Ready') }} </label>
                                        <div class="flex items-center justify-center gap-5 mt-9">
                                            @for ($i = 0; $i <= 4; $i++)
                                                <x-generic.badge-tooltip :tooltip="$readiness['level4'][$i]['tooltip']">
                                                    @include(
                                                        'icons.dashboard.9.badge.level4.'.$i+1,
                                                        (! $readiness['level4'][$i]['complete'] ? ['color' => color(7), 'width' => 60, 'height' => 60] : ['width' => 60, 'height' => 60])
                                                    )
                                                </x-generic.badge-tooltip>
                                            @endfor
                                        </div>

                                        <div class="flex gap-10 items-center justify-between text-left mt-9">
                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Implement measures to prevent, reduce and mitigate environmental impacts') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Define a community impact strategy') }} </p>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="flex gap-2 items-center">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Adopting initiatives or a set of external principles in the field of sustainability') }} </p>
                                                </div>
                                                <div class="flex gap-2 items-center mt-3">
                                                    @include('icons.checkbox')
                                                    <p class="text-sm text-esg8 font-normal">{{ __('Defining the organisation`s strategic Sustainable Development Goals') }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Level 5 --}}
                                    <div x-cloak x-show="selected == 5">

                                        <label class="text-base font-bold text-[#009B7F]"> {{ __('Ready for the next step') }} </label>
                                        <div class="flex items-center justify-center gap-5 mt-9">
                                            @include('icons.dashboard.9.badge.level5.goal')
                                        </div>

                                        <div class="text-center mt-9">
                                            <p> {{ __('Congratulations! You were able to gain all the previous steps achievements!') }} </p>
                                            <p> {{ __('This means that you are more than ready to try out the ') }} <span class="font-bold"> {{ __('in-depth questionnaire.') }} </span> </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 mt-10 gap-5">
                        @if ($action_plan_table)
                            @php $text = json_encode([__('Action Plans')]); @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto">
                                <div id="action_list" class="md:col-span-1 lg:p-5 xl:p-10 lg:mt-0 h-[500px] overflow-x-auto">

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
                                                            @include('icons.categories.' .
                                                                    ($initiative->category->parent_id ??
                                                                        $initiative->category_id))
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="p-2">{{ $initiative->name }}</td>
                                                    <td class="p-2">
                                                    @if(Storage::disk('toolkits')->exists($initiative->id.'.pdf'))
                                                    <x-buttons.a-alt href="{{tenantPrivateAsset($initiative->id.'.pdf', 'toolkits')}}" text="{{ __('Download') }}" />
                                                    @else
                                                    <x-buttons.a-alt href="{{tenantPrivateAsset('toolkit-desenvolvimento.pdf', 'toolkits')}}" text="{{ __('Download') }}" />
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
                    @php $text = json_encode([__('Documentation')]); @endphp
                    <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto" contentplacement="none">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                            <div class="">
                                @foreach($documents[0] as $row)
                                    <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="">
                                            <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                        </div>
                                        <div class="">
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(5)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="">
                                @foreach($documents[1] as $row)
                                    <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="">
                                            <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                        </div>
                                        <div class="">
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(5)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            {{-- Envviroment --}}
            <div class="mt-10 pagebreak print:mt-20" x-show="environment">
                <div class="px-8 bg-esg2/10 rounded-3xl py-8">
                    <div class="border-b border-esg2">
                        <span class="font-encodesans text-xl leading-10 text-esg8 uppercase"> {{ __('Environment') }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 print:-mt-10 nonavoid">
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $atmospheric_pollutants['labels'][0] ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $atmospheric_pollutants['labels'][1]]
                                ]);

                            $subinfo = json_encode([
                                ['value' => array_sum($atmospheric_pollutants['data']), 'unit' => $atmospheric_pollutants['unit'] ?? 'm3'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Atmospheric Pollutants')]) }}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="atmospheric_pollutants" class="m-auto relative !h-full !w-full" unit="{{ $atmospheric_pollutants['unit'] ?? 'kg' }}" subinfo="{{ $subinfo }}"/>
                        </x-cards.card-dashboard-version1-withshadow>


                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $ozone_layer_depleting['labels'][0] ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $ozone_layer_depleting['labels'][1] ]
                                ]);

                            $subinfo = json_encode([
                                ['value' => array_sum($ozone_layer_depleting['data']), 'unit' => $ozone_layer_depleting['unit'] ?? 'm3'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Ozone layer depleting substances')]) }}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="ozone_layer" class="m-auto relative !h-full !w-full" unit="{{ $ozone_layer_depleting['unit'] ?? 'kg' }}" subinfo="{{ $subinfo }}"/>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                    <div class="grid grid-cols-1 gap-5 mt-10 print:-mt-10 nonavoid">
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year') ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period') ]
                                ]);

                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('GHG emissions by category')]) }}" subpoint="{{ $subpoint }}" class="!h-auto" contentplacement="none">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <p class="text-center text-xs font-medium text-esg8">{{ __('Scope 1') }}</p>
                                    @php
                                        $subinfo = json_encode([
                                            ['value' => array_sum($GHG_emission['data']), 'unit' => $GHG_emission['unit'] ?? 't CO2 eq']
                                        ]);
                                    @endphp
                                    <x-charts.bar id="co2_emissions" class="m-auto relative !h-auto !w-full" unit="{{ $GHG_emission['unit'] ?? 't CO2 eq' }}" subinfo="{{ $subinfo }}"/>
                                </div>
                                <div>
                                    <p class="text-center text-xs font-medium text-esg8">{{ __('Scope 2') }}</p>
                                    @php
                                        $subinfo = json_encode([
                                            ['value' => array_sum($GHG_emission2['data']), 'unit' => $GHG_emission2['unit'] ?? 't CO2 eq']
                                        ]);
                                    @endphp
                                    <x-charts.bar id="co2_emissions2" class="m-auto relative !h-auto !w-full" unit="{{ $GHG_emission2['unit'] ?? 't CO2 eq' }}" subinfo="{{ $subinfo }}"/>
                                </div>
                                <div>
                                    <p class="text-center text-xs font-medium text-esg8">{{ __('Scope 3') }}</p>
                                    @php
                                        $subinfo = json_encode([
                                            ['value' => array_sum($GHG_emission3['data']), 'unit' => $GHG_emission3['unit'] ?? 't CO2 eq']
                                        ]);
                                    @endphp
                                    <x-charts.bar id="co2_emissions3" class="m-auto relative !h-auto !w-full" unit="{{ $GHG_emission3['unit'] ?? 't CO2 eq' }}" subinfo="{{ $subinfo }}"/>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 print:-mt-10 nonavoid">
                        <div class="">
                            @php
                                $subpoint = json_encode([
                                        [ 'color' => 'bg-[#008131]', 'text' => __('Renewable') ],
                                        [ 'color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable') ]
                                    ]);
                            @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('energy consumption')]) }}" type="none" class="!h-full" subpoint="{{ $subpoint }}" >
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="text-center w-full">
                                        @if ($energy_consumption_baseline != null)
                                            <label class="text-xs font-medium text-esg8"> {{ __('Baseline year: ') . $energy_consumption_baseline_year['value'] }} </label>
                                            <x-charts.donut id="energy_consumption_baseline" class="m-auto !h-[180px] !w-[180px] mt-5"  legendes="true" grid="1"/>
                                        @else
                                            <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                        @endif
                                    </div>

                                    <div class="text-center w-full">
                                        @if ($energy_consumption_reporting != null)
                                            <label class="text-xs font-medium text-esg8"> {{ __('Reporting period: ') . $energy_consumption_reporting_year }} </label>
                                            <x-charts.donut id="energy_consumption_reporting" class="m-auto !h-[180px] !w-[180px] mt-5" legendes="true" grid="1"/>
                                        @else
                                            <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Carbon intensity')]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$carbon_intensity['value']" :unit="$carbon_intensity['unit'] ?? 't CO2 eq / €'" :isNumber=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.co2', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Energy intensity')]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$energy_intensity['value']" :unit="$energy_intensity['unit'] ?? 'MWh / €'" :isNumber=true />
                                    </div>
                                    <div class="-mt-3">
                                        @include('icons.dashboard.gestao-energia', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <div class="">
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Waste produced')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                                    @if ($waste_produced != null)
                                        <x-charts.donut id="waste_produced" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 print:-mt-10 nonavoid">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Recycled waste')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$recycled_waste['value']" :unit="$recycled_waste['unit'] ?? 't'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.recicle-residue', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Waste sent for disposal')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$waste_sent_for_disposal['value']" :unit="$waste_sent_for_disposal['unit'] ?? 't'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.residues', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5 nonavoid">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Hazardous waste')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$hazardous_waste['value']" :unit="$hazardous_waste['unit'] ?? 't'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.trash', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('radioactive waste')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$radioactive_waste['value']" :unit="$radioactive_waste['unit'] ?? 't'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.trash', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('water consumption')]) }}" class="!h-auto" type="none" contentplacement="none">
                            @foreach($water_consumption as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $water_consumed['labels'][0]],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $water_consumed['labels'][1]]
                                ]);

                            $subinfo = json_encode([
                                ['value' => array_sum($water_consumed['data']), 'unit' => $water_consumed['unit'] ?? 'm3'],
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('water consumed')]) }}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="water_consumed" class="m-auto relative !h-full !w-full" unit="{{ $water_consumed['unit'] ?? 'm3' }}" subinfo="{{ $subinfo }}"/>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water recycled and/or reused')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$water_recycle_reused['value']" :unit="$water_recycle_reused['unit'] ?? 'm3'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.save-water', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water intensity')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$water_intensity['value']" :unit="$water_intensity['unit'] ?? 'm3 / €'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.water', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5 nonavoid">
                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Biodiversity Impact')]) }}" class="!h-auto" type="none" contentplacement="none">
                                @foreach($biodiversity_impact as $row)
                                    <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="">
                                            <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                        </div>
                                        <div class="">
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(2)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Raw-Materials Consumption')]) }}" class="!h-auto" type="none" contentplacement="none">
                                @foreach($raw_matrial as $row)
                                    <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="">
                                            <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                        </div>
                                        <div class="">
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(2)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Organisation Activities Impact')]) }}" class="!h-auto" type="none" contentplacement="none">
                                @foreach($organization_activity_impact as $row)
                                    <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="">
                                            <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                        </div>
                                        <div class="">
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(2)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('total building materials used')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$total_building_matrial_used['value']" :unit="$total_building_matrial_used['unit'] ?? 't'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.construction', ['color' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Recovered, recycled and/or of biological origin materials')]) }}" class="!h-full" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$biological_origin_matrials['value']" :unit="$biological_origin_matrials['unit'] ?? 't'" :isNumber=true />
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
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted workers')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            @if ($contract_workers != null)
                                <x-charts.donut id="contracted_workers" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Subcontracted workers')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            @if ($outsource_workers != null)
                                <x-charts.donut id="outsourced_workers" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Middle management (Contracted)')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            @if ($middle_management != null)
                                <x-charts.donut id="middle_management" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('top management (Contracted)')]) }}" type="flex" class="!h-[270px]" contentplacement="none">
                            @if ($top_management != null)
                                <x-charts.donut id="top_management" class="m-auto !h-[180px] !w-[180px]" legendes="true"/>
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('contracted Workers Turnover')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$worker_turnover['value']" :unit="$worker_turnover['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.workers', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender pay gap')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$gender_paygap['value']" :unit="$gender_paygap['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.money-hand', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Workers satisfaction and conditions')]) }}"
                            class="!h-min"
                            contentplacement="none">
                            @foreach($performance_appraisal as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' =>  color(1)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Safety and health')]) }}"
                            class="!h-min"
                            contentplacement="none">
                            @foreach($accident_work as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' =>  color(1)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Number of accidents at work during the reporting'), __('period')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$numberof_accident['value']" :unit="__('accidents')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.work-accident', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Days lost due to injury, accident, death or illness')]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$day_lost_by_accident['value']" :unit="__('days')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.work-accident', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Training for the Workers')]) }}" class="!h-auto" type="none" contentplacement="none">
                            @foreach($traning_for_works as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' =>  color(1)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Number of hours on training')]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$number_of_hours['value']" :unit="__('hours')" :isNumber=true />
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

                    @if ($highest_governance)
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Highest governance body of the organisation constituted and structured')]) }}"
                            class="!h-auto !mt-10"
                            contentplacement="none">
                            @if ($no_executive_members)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                                    <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Board of Directors with no Executive Members')}}</label>
                                </div>
                            @endif

                            @if ($with_executive_members)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                                    <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Board of Directors with Executive Members')}}</label>
                                </div>
                            @endif

                            @if ($mixed)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                                    <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Mixed')}}</label>
                                </div>
                            @endif

                            @if ($management_board)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                                    <label for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{__('Management Board')}}</label>
                                </div>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Highest governance bo distribution by gender')]) }}" type="flex" contentplacement="none" class="!h-[270px]">
                            @if ($high_governance_body)
                                <x-charts.donut id="gender_high_governance_body" class=" !h-[180px] !w-[180px]" legendes="true"/>
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Significant Incidents')]) }}" class="!h-auto" type="none" contentplacement="none">
                            @foreach($significant_incidents as $row)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                    </div>
                                    <div class="">
                                        @if ($row['status'])
                                            @include('icons.checkbox', ['color' =>  color(3)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Amount of the fines imposed') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$amount_fines_imposed['value']" :unit="$amount_fines_imposed['unit'] ?? '€'" :isCurrency=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.fine', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('incidents of discrimination') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$incidents_discrimination['value']" :unit="__('incidents')" :isNumber=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.discrimination', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Annual revenue') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$annual_revenue['value']" :unit="$annual_revenue['unit'] ?? '€'" :isCurrency=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.money-hand', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Annual net revenue') ]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$annual_net_revenue['value']" :unit="$annual_net_revenue['unit'] ?? '€'" :isCurrency=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.9.money-hand', ['color' =>  color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Annual reporting')]) }}" class="!h-full" type="none" contentplacement="none">
                                @foreach($annual_reporting as $row)
                                    <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="">
                                            <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                        </div>
                                        <div class="">
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Strategic Sustainable Development Goals')]) }}" class="!h-auto !mt-5">
                        <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                            <div class="grid grid-cols-4 md:grid-cols-9 gap-3 mt-10 ">
                                <div class="w-full">
                                    @include('icons.goals.1', ['class' => 'inline-block', 'color' => $sdg[1] ? '#EA1D2D' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.2', ['class' => 'inline-block', 'color' => $sdg[2] ? '#D19F2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.3', ['class' => 'inline-block', 'color' => $sdg[3] ? '#2D9A47' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.4', ['class' => 'inline-block', 'color' => $sdg[4] ? '#C22033' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.5', ['class' => 'inline-block', 'color' => $sdg[5] ? '#EF412A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.6', ['class' => 'inline-block', 'color' => $sdg[6] ? '#00ADD8' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.7', ['class' => 'inline-block', 'color' => $sdg[7] ? '#FDB714' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.8', ['class' => 'inline-block', 'color' => $sdg[8] ? '#8F1838' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.9', ['class' => 'inline-block', 'color' => $sdg[9] ? '#F36E24' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.10', ['class' => 'inline-block', 'color' => $sdg[10] ? '#E01A83' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.11', ['class' => 'inline-block', 'color' => $sdg[11] ? '#F99D25' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.12', ['class' => 'inline-block', 'color' => $sdg[12] ? '#CD8B2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.13', ['class' => 'inline-block', 'color' => $sdg[13] ? '#48773C' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.14', ['class' => 'inline-block', 'color' => $sdg[14] ? '#007DBB' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.15', ['class' => 'inline-block', 'color' => $sdg[15] ? '#40AE49' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.16', ['class' => 'inline-block', 'color' => $sdg[16] ? '#00558A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.17', ['class' => 'inline-block', 'color' => $sdg[17] ? '#1A3668' : '#DCDCDC'])
                                </div>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    @if ($logos['status'])
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
                                @if ($logos['sbt'])
                                    @include('icons.dashboard.9.logos.sbt')
                                @endif

                                @if ($logos['b_corp'])
                                    @include('icons.dashboard.9.logos.bcorp')
                                @endif

                                @if ($logos['pri'])
                                    @include('icons.dashboard.9.logos.pri')
                                @endif

                                @if ($logos['un'])
                                    @include('icons.dashboard.9.logos.unglobal')
                                @endif

                                @if ($logos['wbc'])
                                    @include('icons.dashboard.9.logos.wbcsd')
                                @endif

                                @if ($logos['prb'])
                                    @include('icons.dashboard.9.logos.prb', ['width' => 100, 'height' => 100])
                                @endif

                                @if ($logos['psi'])
                                    @include('icons.dashboard.9.logos.psi')
                                @endif
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
