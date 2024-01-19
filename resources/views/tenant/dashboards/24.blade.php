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

            @if (isset($action_plan))
                var data = {!! $action_plan['series'] !!};
                let actionPlanData = data.map(function(value, index) {
                    return value['data'][0];
                });
                let actionPlanLabel = data.map(function(value, index) {
                    return value['name'];
                });

                var plugins = [{
                    afterDatasetsDraw: function(bubbleChart, easing) {
                        var ctx = bubbleChart.ctx;

                        bubbleChart.data.datasets.forEach(function(dataset, i) {
                            var meta = bubbleChart.getDatasetMeta(i);
                            if (meta.type == "bubble") {
                                meta.data.forEach(function(element, index) {
                                    var dataString = dataset.label[index].toString();

                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    ctx.font = "15px bold " + twConfig.theme.fontFamily
                                        .encodesans;
                                    ctx.fillStyle = twConfig.theme.colors.esg4;

                                    var position = element.tooltipPosition();

                                    ctx.fillText(dataString, position.x, position.y);
                                });
                            }
                        });
                    },
                    beforeEvent(chart, args, pluginOptions) {
                        let actionPlanTableRow = `action_plan_tr`;

                        for (var i = 0; i < document.getElementsByClassName(actionPlanTableRow)
                            .length; i++) {
                            document.getElementsByClassName(actionPlanTableRow)[i].style.filter = 'none';
                        }
                    }
                }];

                const getOrCreateTooltip = (chart) => {

                    const elements = document.getElementsByClassName("action_tootltip");
                    while (elements.length > 0) {
                        elements[0].parentNode.removeChild(elements[0]);
                    }

                    tooltipEl = document.createElement('div');
                    tooltipEl.classList.add("action_tootltip");
                    tooltipEl.style.background = twConfig.theme.colors.esg4;
                    tooltipEl.style.fontFamily = twConfig.theme.fontFamily.encodesans;
                    tooltipEl.style.fontWeight = 500;
                    tooltipEl.style.borderRadius = '8px';
                    tooltipEl.style.color = twConfig.theme.colors.esg6;
                    tooltipEl.style.fontSize = '0.75rem';
                    tooltipEl.style.opacity = 1;
                    tooltipEl.style.textAlign = "center";
                    tooltipEl.style.pointerEvents = 'none';
                    tooltipEl.style.marginTop = "20px";
                    tooltipEl.style.position = 'absolute';
                    tooltipEl.style.transform = 'translate(-50%, 0)';
                    tooltipEl.style.transition = 'all .1s ease';

                    const table = document.createElement('table');
                    table.style.margin = '0px';

                    tooltipEl.appendChild(table);
                    chart.canvas.parentNode.appendChild(tooltipEl);

                    return tooltipEl;
                };

                const externalTooltipHandler = (context) => {
                    // Tooltip Element
                    const {
                        chart,
                        tooltip
                    } = context;
                    const tooltipEl = getOrCreateTooltip(chart);

                    // Hide if no tooltip
                    if (tooltip.opacity === 0) {
                        tooltipEl.style.opacity = 0;
                        return;
                    }

                    // Set Text
                    if (tooltip.body) {
                        const titleLines = tooltip.title || [];
                        const bodyLines = tooltip.body.map(b => b.lines[0]);
                        const LabelsArray = bodyLines[0];

                        const tableBody = document.createElement('tbody');
                        const colors = tooltip.labelColors;

                        const span = document.createElement('span');
                        span.style.background = colors.backgroundColor;
                        span.style.fontFamily = twConfig.theme.fontFamily.encodesans;
                        span.style.marginRight = '10px';
                        span.style.height = '10px';
                        span.style.width = '150px';
                        span.style.display = 'inline-block';
                        span.innerHTML = LabelsArray;

                        const tr = document.createElement('tr');
                        tr.style.backgroundColor = 'inherit';
                        tr.style.borderWidth = 0;

                        const td = document.createElement('td');
                        td.style.borderWidth = 0;

                        td.appendChild(span);
                        tr.appendChild(td);
                        tableBody.appendChild(tr);

                        tooltipEl.appendChild(tableBody);
                    }

                    const {
                        offsetLeft: positionX,
                        offsetTop: positionY
                    } = chart.canvas;

                    // Display, position, and set styles for font
                    tooltipEl.style.opacity = 1;
                    tooltipEl.style.left = positionX + tooltip.caretX + 'px';
                    tooltipEl.style.top = positionY + tooltip.caretY + 'px';
                    tooltipEl.style.font = tooltip.options.bodyFont.string;
                    tooltipEl.style.padding = '8px';
                };

                var options = {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            align: 'start',
                            display: false,
                            text: "{{ __('Priority Matrix') }}",
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: '18px',
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            padding: {
                                bottom: 30
                            },
                            color: color_code
                        },
                        tooltip: {
                            enabled: false,
                            position: 'nearest',
                            external: externalTooltipHandler,
                            callbacks: {
                                label: function(context) {
                                    let label = '',
                                        index = context.dataIndex,
                                        currentLabel = context.dataset.label[index];

                                    let actionPlanTableRow = `action_plan_${currentLabel}`;
                                    let currentTr = document.getElementsByClassName(actionPlanTableRow)[0];
                                    let labelText = currentTr.getElementsByTagName("td")[2].innerHTML;

                                    if (context.parsed.y !== null) {
                                        label += labelText;
                                    }

                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: "{{ __('Business Criticality') }}",
                                color: color_code,
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: '18px',
                                    weight: twConfig.theme.fontWeight.bold,
                                    lineHeight: 1.2,
                                },
                                padding: {
                                    top: 20,
                                    left: 00,
                                    right: 00,
                                    bottom: 0
                                }
                            },
                            grid: {
                                borderColor: color_code,
                                borderWidth: 2,
                                tickLength: 0,
                                color: color_code,
                                borderDash: [4, 1],
                            },
                            ticks: {
                                display: false,
                                maxTicksLimit: 3
                            },
                            min: {!! $action_plan['xaxis']['min'] !!},
                            max: {!! $action_plan['xaxis']['max'] !!}
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: "{{ __('PRIORITY') }}",
                                color: color_code,
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: '18px',
                                    weight: twConfig.theme.fontWeight.bold
                                },
                                padding: {
                                    top: 0,
                                    left: 0,
                                    right: 0,
                                    bottom: 20
                                }
                            },
                            grid: {
                                borderColor: color_code,
                                borderWidth: 2,
                                tickLength: 0,
                                color: color_code,
                                borderDash: [4, 1],
                            },
                            ticks: {
                                display: false,
                                maxTicksLimit: 3
                            },
                            min: {!! $action_plan['yaxis']['min'] !!},
                            max: {!! $action_plan['yaxis']['max'] !!}
                        }
                    },
                    onHover: function(evt) {
                        let actionPlanTableRowAll = `action_plan_tr`;


                        var item = actionPlanChart.getElementsAtEventForMode(evt, 'nearest', {
                            intersect: true
                        }, true);
                        if (item.length) {

                            for (var i = 0; i < document.getElementsByClassName(actionPlanTableRowAll)
                                .length; i++) {
                                document.getElementsByClassName(actionPlanTableRowAll)[i].style.filter =
                                    'blur(2px)';
                            }

                            let currentLabel = actionPlanChart.data.datasets[0].label[item[0].index];
                            let actionPlanTableRow = `action_plan_${currentLabel}`;

                            document.getElementsByClassName(actionPlanTableRow)[0].style.filter = 'none';
                        }
                    }
                };

                var actionPlanChart = new Chart(document.getElementById("actions_plan"), {
                    type: 'bubble',
                    data: {
                        datasets: [{
                            label: actionPlanLabel,
                            data: actionPlanData,
                            borderColor: twConfig.theme.colors.esg5,
                            backgroundColor: twConfig.theme.colors.esg5
                        }]
                    },
                    options,
                    plugins
                });
            @endif

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

                pieCharts(
                    null,
                    {!! json_encode([ ($readiness['current_level'] * 20), 100 - ($readiness['current_level'] * 20) ]) !!},
                    'readiness_level',
                    ['{{ color(6) }}', '#F5F5F5'],
                    '%',
                    '{{ ($readiness['current_level'] * 20) . '%' }} '
                );

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

                @if ($consumption_expenditure != null)
                    barCharts(
                        {!! json_encode($consumption_expenditure['labels']) !!},
                        {!! json_encode($consumption_expenditure['data']) !!},
                        'consumption_expenditure',
                        ["#008131", "#99CA3C"]
                    );
                @endif

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

                @if ($type_of_contract != null)
                    barCharts(
                        {!! json_encode($type_of_contract['labels']) !!},
                        {!! json_encode($type_of_contract['data']) !!},
                        'type_of_contract',
                        ["#008131", "#99CA3C"],
                        null,
                        'multi'
                    );
                @endif

                @if ($contract_workers_distribution != null)
                    barCharts(
                        {!! json_encode($contract_workers_distribution['labels']) !!},
                        {!! json_encode($contract_workers_distribution['data']) !!},
                        'contract_workers_distribution',
                        ["#E86321", "#FBB040", "#FDC97B"]
                    );
                @endif

        });

        // Common function for bar charts
        function barCharts(labels, data, id, barColor, unit = null, type = "single")
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
                            return formatNumber(value) + (unit != null ? unit : '');
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
        function pieCharts(labels, data, id, barColor, centertext = '', customCenter = null)
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
                    let text = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                    if (customCenter != null) {
                        ctx.fillText(formatNumber(customCenter), width / 2 , height / 3 + top + 20);

                        ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                        let newtext = (centertext !== undefined ?  centertext : '-');
                        ctx.fillText(newtext, width / 2 + 22, height / 3 + top + 20);
                    } else {
                        ctx.fillText(formatNumber(text), width / 2, height / 3 + top + 20);
                        ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                        let newtext = (centertext !== undefined ?  centertext : '-');
                        ctx.fillText(newtext, width / 2, height / 3 + top + 45);
                    }



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
    <div class="px-4 lg:px-0" x-data="{ main: true, environment: false, social: false, governance: false, extra: false }">

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
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 !rounded-md" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]).'?print=true' }}'">
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

            <div class="my-8 grid grid-cols-1 md:grid-cols-5 gap-5">
                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer"
                    x-on:click="main = true, environment = false, social = false, governance = false, extra = false"
                    :class="main ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Main') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main = false, environment = true, social = false, governance = false, extra = false"
                    :class="environment ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Environment') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main = false, environment = false, social = true, governance = false, extra = false"
                    :class="social ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Social') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main = false, environment = false, social = false, governance = true, extra = false"
                    :class="governance ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Governance') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer"
                    x-on:click="main = false, environment = false, social = false, governance = false, extra = true"
                    :class="extra ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="extra"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Extra') }}</label>
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

                                <div class="relative">
                                    <div class="absolute right-0 top-0">
                                        <p class="text-base font-semibold mb-1">{!! __('Overall Readiness Level') !!}</p>
                                        <x-charts.donut id="readiness_level" class="m-auto !h-[100px] !w-[100px]" />
                                    </div>
                                    <div class="flex justify-center items-start">
                                        @if ($readiness['current_level'] == 5)
                                            @include('icons.dashboard.9.readiness.5')
                                        @else
                                            @include('icons.dashboard.9.readiness.' . ($readiness['current_level'] === 0 ? 1 : $readiness['current_level']) )
                                        @endif
                                    </div>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 mt-10 gap-5">
                        @if ($action_plan)
                            @php $text = json_encode([__('Action Plans - Prority Matrix')]); @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto">
                                <div x-data="{ showExtraLegend: false }" class="md:col-span-1 lg:p-5 xl:p-10 ">
                                    <div @mouseover="showExtraLegend = true" @mouseover.away="showExtraLegend = false"
                                        class="relative w-full">
                                        <div class="h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px]">
                                            <div></div>
                                            <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                            <div class="text-esg8 absolute left-[31px] top-[15px] rotate-90 text-4xl">
                                                @include('icons.arrow', [
                                                    'class' => 'rotate-180',
                                                    'fill' => color(7),
                                                ])
                                            </div>
                                            <div
                                                class="text-esg8 absolute left-[310px] top-[300px] sm:left-[465px] sm:top-[448px] text-4xl">
                                                @include('icons.arrow', ['fill' => color(7)])
                                            </div>
                                            <div x-show="showExtraLegend"
                                                class="absolute left-[50px] top-[60px] text-sm text-esg9">
                                                {{ __('Highly Recommended') }}</div>
                                            <div x-show="showExtraLegend"
                                                class="absolute left-[50px] bottom-[60px] text-sm text-esg9">
                                                {{ __('Recommended') }}</div>
                                            <div x-show="showExtraLegend"
                                                class="absolute right-[60px] top-[60px] text-sm text-esg9">
                                                {{ __('High Criticality') }}</div>
                                            <div x-show="showExtraLegend"
                                                class="absolute right-[60px] bottom-[60px] text-sm text-esg9">
                                                {{ __('High Priority') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        @endif

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

            {{-- Enviroment --}}
            <div class="mt-10 pagebreak print:mt-20" x-show="environment">
                <div class="px-8 bg-esg2/10 rounded-3xl py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

                    <div class="grid grid-cols-1 gap-5 mt-5 ">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5 ">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        @php
                            $subpoint = json_encode([
                                    [ 'color' => 'bg-[#008131]', 'text' => __('Refrigeration') ],
                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Air Conditioning')]
                                ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('CONSUMPTION EXPENDITURE ON') !!}"
                            subpoint="{{ $subpoint }}"
                            class="!h-auto"
                            contentplacement="none"
                            >
                            <x-charts.bar id="consumption_expenditure" class="m-auto relative !h-full !w-full" unit="{{ $consumption_expenditure['unit'] ?? 'percentage of turnover' }}" />
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('TYPE OF REFRIGERANT GAS USED IN REFRIGERATION AND AIR CONDITIONING EQUIPMENT') !!}"
                            class="!h-min"
                            contentplacement="none"
                            >
                            @foreach($refrigerant_gas as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="2"
                                />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div class="grid gri-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow
                                class="!h-min"
                                contentplacement="none"
                                >
                                @foreach($renewable_guarantee as $row)
                                    <x-list.checkbox
                                        label="{{ $row['label'] }}"
                                        status="{{ $row['status'] }}"
                                        color="2"
                                    />
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{!! __('Company`s installed renewable energy generation capacity for self-consumption') !!}" class="!h-full" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$renewable_energy_generation['value']" :unit="$renewable_energy_generation['unit'] ?? 'kW'" :isNumber=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.gestao-energia', ['color' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{!! __('energy consumption expenditure for vehicle travel as a percentage of turnover') !!}" class="!h-full" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$energy_consumption_expenditure['value']" :unit="$energy_consumption_expenditure['unit'] ?? '%'" :isNumber=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.capa', ['fill' =>  color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('Company(ies) with which there is electricity supply contract') !!}"
                            class="!h-auto"
                            contentplacement="none"
                            >
                            @foreach($groups as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="2"
                                />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('Type of fuel consumed by vehicles belonging to the company') !!}"
                            class="!h-auto"
                            contentplacement="none"
                            >
                            @foreach($fuel_type_vehicles as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="2"
                                />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('Type of fuel consumed by fixed installations') !!}"
                            class="!h-auto"
                            contentplacement="none"
                            >
                            @foreach($fuel_type_fix as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="2"
                                />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('energy consumption expenditure for fixed installations as a percentage of turnover') !!}"
                            class="!h-full"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$energy_consumption_expenditure_fix['value']" :unit="$energy_consumption_expenditure_fix['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.energy_consumption', ['fill' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('facilities certified to ISO 9001, SA 8000 or OHSAS18001 standards') !!}"
                            class="!h-full"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$facilities_certified['value']" :unit="$facilities_certified['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.iso', ['fill' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('electricity consumption of the buildings used for the company`s business activities') !!}"
                            class="!h-full"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$electricity_consumption['value']" :unit="$electricity_consumption['unit'] ?? 'kWh'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.energy_consumption', ['fill' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('gas consumption of buildings') !!}"
                            class="!h-full"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$gas_consumption['value']" :unit="$gas_consumption['unit'] ?? 'm3'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.gas', ['fill' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('renewable energy consumption of buildings ') !!}"
                            class="!h-full"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$renewable_energy['value']" :unit="$renewable_energy['unit'] ?? 'kWh'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.renuable', ['fill' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('utilisation rate of total capacity of buildings/offices under company control as a percentage of total available facilities') !!}"
                            class="!h-full"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$utilisation_rate['value']" :unit="$utilisation_rate['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.office', ['fill' =>  color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('Circular economy practices of the company') !!}"
                            class="!h-auto"
                            contentplacement="none"
                            >
                            @foreach($circular_economy as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="2"
                                />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow
                                text="{!! __('Company promotes the analysis of the life cycle of products, CONSIDERING eco-design, reducing harmfulL substances AND facilitating theIR repairability AND recovery') !!}"
                                class="!h-auto"
                                contentplacement="none"
                                >
                                @foreach($company_promotes as $row)
                                    <x-list.checkbox
                                        label="{{ $row['label'] }}"
                                        status="{{ $row['status'] }}"
                                        color="2"
                                    />
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{!! __('Company facilitates and promotes appropriate channels with administrations, the scientific and technological community and economic and social agents to promote the transition to a circular ecosystem') !!}"
                                class="!h-auto"
                                contentplacement="none"
                                >
                                @foreach($company_facilitates as $row)
                                    <x-list.checkbox
                                        label="{{ $row['label'] }}"
                                        status="{{ $row['status'] }}"
                                        color="2"
                                    />
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                class="!h-auto"
                                contentplacement="none"
                                >
                                @foreach($recognised_certificate as $row)
                                    <x-list.checkbox
                                        label="{{ $row['label'] }}"
                                        status="{{ $row['status'] }}"
                                        color="2"
                                    />
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Social --}}
            <div class="mt-10 pagebreak print:mt-20" x-show="social">
                <div class="px-8 bg-esg1/10 rounded-3xl py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

                        @php
                            $subpoint = json_encode([
                                [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ],
                                [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ],
                                [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ]
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('type of contract') !!}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="type_of_contract" class="m-auto relative !h-full !w-full" />
                        </x-cards.card-dashboard-version1-withshadow>

                        @php
                            $subpoint = json_encode([
                                [ 'color' => 'bg-[#E86321]', 'text' => __('Less than 30') ],
                                [ 'color' => 'bg-[#FBB040]', 'text' => __('Less than 30') ],
                                [ 'color' => 'bg-[#FDC97B]', 'text' => __('More than 50') ]
                            ]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{!! __('contracted Worker’s age distribution') !!}" subpoint="{{ $subpoint }}" class="!h-min">
                            <x-charts.bar id="contract_workers_distribution" class="m-auto relative !h-full !w-full" />
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

                        <x-cards.card-dashboard-version1-withshadow text="{!! __('Workers satisfaction and conditions') !!}"
                            type="flex"
                            class="!h-auto"
                            contentplacement="none">
                            @foreach($worker_satisfaction as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="1"
                                />
                            @endforeach
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

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender pay gap')]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$gender_paygap['value']" :unit="$gender_paygap['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.money-hand', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('bonuses (variable remuneration) paid as a percentage of turnover')]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$bonuses['value']" :unit="$bonuses['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.money-hand', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('full-time employees receiving 90% of total bonuses (variable remuneration)')]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$fulltime_employee_bonuses['value']" :unit="$fulltime_employee_bonuses['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.money-hand', ['color' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('customers surveyed who consider themselves satisfied')]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$customers_survey['value']" :unit="$customers_survey['unit'] ?? '%'" />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.survay', ['fill' =>  color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Safety and health')]) }}"
                            class="!h-auto"
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

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('employees who have suffered accidents at work that required external medical attention')]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$employees_suffered_accidents['value']" :unit="__('%')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.stethoscope', ['fill' =>  color(1)])
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

                        <div class="grid grid-cols-1 gap-5">
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

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Average training expenditure per full-time employee')]) }}" class="!h-min" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$avg_training_expenditure['value']" :unit="__('€')" :isNumber=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.qualification', ['color' =>  color(1)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <x-cards.card-dashboard-version1-withshadow
                            class="!h-auto"
                            contentplacement="none"
                            >
                            @foreach($recognised_certification as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="2"
                                />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            {{-- Governance --}}
            <div class="mt-10 print:mt-20" x-show="governance">
                <div class="px-8 bg-esg3/10 rounded-3xl py-8" >
                    @if ($highest_governance)
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Highest governance body of the organisation constituted and structured')]) }}"
                            class="!h-auto"
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

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Amount of the fines due to violations of anti-bribery and anti-corruption laws') ]) }}" class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$amount_fines_imposed['value']" :unit="$amount_fines_imposed['unit'] ?? '€'" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.fine', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('incidents of discrimination') ]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$incidents_discrimination['value']" :unit="__('incidents')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.discrimination', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('revenues from regions whose Transparency International corruption index score is below 60 (out of 100)') ]) }}"
                            class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$international_corruption['value']" unit="%" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.magnifier', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('Contributions to political parties as a percentage of total revenues') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$contributions_political['value']" unit="%" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.polictics', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('Amount of fines or penalties due to non-compliance with legal regulations') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$amount_fines_penalties['value']" :unit="$amount_fines_penalties['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.9.fine', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('Total number of suppliers') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$total_suppliers['value']" :unit="__('suppliers')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.suppliers', ['fill' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('Percentage of supply from the 3 largest external suppliers') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$external_suppliers['value']" :unit="$external_suppliers['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.suppliers', ['fill' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('Supplier turnover') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$supplier_turnover['value']" :unit="$supplier_turnover['unit'] ?? '€'" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.8.money-hand', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('sales of new or modified products/services launched less than twelve months ago') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$sales_modified_products['value']" :unit="$sales_modified_products['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.cart', ['fill' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('expenditure on corporate product security as a percentage of turnover') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$expenditure_corporate_product['value']" :unit="$expenditure_corporate_product['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.8.money-hand', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('corporate products sold or shipped subject to product recalls for safety or health reasons') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$corporate_product_sold['value']" :unit="$corporate_product_sold['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.product_sold', ['fill' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('products recalled due to regulatory pressure') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$products_recalled['value']" :unit="__('products')" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.product_sold', ['fill' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('product withdrawn from the market for health reasons (compared to turnover)') ]) }}"
                            class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$product_withdrawn['value']" :unit="$product_withdrawn['unit'] ?? '€'" :isCurrency=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.product_sold', ['fill' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 my-5">

                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Percentage of total products/services by objective')]) }}" class="!h-full" type="none" contentplacement="none">
                                @foreach($product_services as $row)
                                    <div class="flex items-center gap-4 justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                        <div class="">
                                            <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                        </div>
                                        <div class="text-esg8 text-xs">
                                            <span class="text-lg font-medium">{{ $row['value'] }}</span> {{ $row['unit'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow class="!h-full" type="none" contentplacement="none">
                                @foreach($certificate_governance as $row)
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

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Annual R&D investment as a percentage of turnover') ]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$annual_investment['value']" :unit="$annual_investment['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.randd', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([ __('Total expenditure on maintenance and safety as a percentage of turnover') ]) }}" class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$expenditure_maintenance['value']" :unit="$expenditure_maintenance['unit'] ?? '%'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.maintanace', ['color' =>  color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Percentage of turnover devoted to investments in relevant aspects of business sustainability in the last twelve months.') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$percentage_turnover['value']" :unit="$percentage_turnover['unit'] ?? '%'" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' =>  color(3)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Strategic Sustainable Development Goals')]) }}"
                        class="!h-auto !mt-5">
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
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    @endif
                </div>
            </div>

            {{-- Extra --}}
            <div class="mt-10 print:mt-20" x-show="extra">
                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([ __('Context') ]) }}"
                    class="!h-auto"
                    contentplacement="none">
                    <div class="text-sm text-esg8">
                        {!! __('These section refers to the calculation of the :styleCarbon Footprint:closestyle, which is an optional module of the ST questionnaire.', [
                            'style' => '<span class="font-semibold">', 'closestyle' => '</span>'
                        ]) !!}
                    </div>
                </x-cards.card-dashboard-version1-withshadow>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{!! __('Company(ies) with which there is electricity supply contract') !!}"
                        class="!h-auto"
                        contentplacement="none"
                        >
                        @foreach($company_electricity_supply as $row)
                            <x-list.checkbox
                                label="{{ $row['label'] }}"
                                status="{{ $row['status'] }}"
                                color="6"
                            />
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="grid grid-cols-1 gap-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([ __('Total electricity consumption of the company') ]) }}"
                            class="!h-auto "
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$electricity_consumption_company['value']" :unit="$electricity_consumption_company['unit'] ?? 'kWh'" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.8.money-hand', ['color' =>  color(6)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{!! __('Type of fuel consumed by vehicles belonging to the company') !!}"
                            class="!h-auto"
                            contentplacement="none"
                            >
                            @foreach($fuel_tyoe_company as $row)
                                <x-list.checkbox
                                    label="{{ $row['label'] }}"
                                    status="{{ $row['status'] }}"
                                    color="6"
                                />
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Installed capacity of photovoltaic renewable energy generation for self-consumption of the company') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$capacity_photovoltaic['value']" :unit="$capacity_photovoltaic['unit'] ?? 'W'" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.solar', ['color' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Installed capacity of renewable energy generation from other sources for self-consumption of the company') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$capacity_renewable_energy['value']" :unit="$capacity_renewable_energy['unit'] ?? 'W'" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.green_energey', ['color' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Total energy consumption per vehicle trip') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$energy_consumption_per_trip['value']" :unit="$energy_consumption_per_trip['unit'] ?? 'litres'" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.capa', ['fill' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Total energy consumption per vehicle trip') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$energy_consumption_per_trip_cost['value']" :unit="$energy_consumption_per_trip_cost['unit'] ?? '€'" :isCurrency=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.capa', ['fill' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Total energy consumption by fixed installations') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$energy_consumption_installation['value']" :unit="$energy_consumption_installation['unit'] ?? 'litres'" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Total energy consumption by fixed installations') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$energy_consumption_installation_cost['value']" :unit="$energy_consumption_installation_cost['unit'] ?? '€'" :isCurrency=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Consumption for cooling and air conditioning') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$ac_consumption['value']" :unit="$ac_consumption['unit'] ?? 'kg'" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.ac', ['color' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([ __('Consumption for cooling and air conditioning') ]) }}"
                        class="!h-auto "
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$ac_consumption_cost['value']" :unit="$ac_consumption_cost['unit'] ?? '€'" :isCurrency=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.ac', ['color' =>  color(6)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="mt-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{!! __('TYPE OF REFRIGERANT GAS USED IN REFRIGERATION AND AIR CONDITIONING EQUIPMENT') !!}"
                        class="!h-auto"
                        contentplacement="none"
                        >
                        @foreach($gas_used_ac as $row)
                            <x-list.checkbox
                                label="{{ $row['label'] }}"
                                status="{{ $row['status'] }}"
                                color="6"
                            />
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>
        </div>
    </div>
@endsection
