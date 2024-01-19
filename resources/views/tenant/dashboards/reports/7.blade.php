@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' =>'bg-esg4', 'isheader' => true])

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
        var color_male = "#21A6E8",
            color_female = "#C5A8FF",
            color_other = "#02C6A1";

        document.addEventListener('DOMContentLoaded', () => {
            var color_code = twConfig.theme.colors.esg7;

            @if ($charts['gender_equility_employees'])
                pieCharts(
                    {!! json_encode($charts['gender_equility_employees']['labels']) !!},
                    {!! json_encode($charts['gender_equility_employees']['series']) !!},
                    'gender_equility_employees',
                    [color_female, color_male, color_other],
                    {!! json_encode($charts['gender_equility_employees']['unit']) !!},
                );
            @endif

            @if ($charts['gender_high_governance_body'])
                pieCharts(
                    {!! json_encode($charts['gender_high_governance_body']['labels']) !!},
                    {!! json_encode($charts['gender_high_governance_body']['series']) !!},
                    'gender_high_governance_body',
                    [color_female, color_male, color_other],
                    {!! json_encode($charts['gender_high_governance_body']['unit']) !!},
                );
            @endif

            @if ($charts['gender_equality_executives'])
                pieCharts(
                    {!! json_encode($charts['gender_equality_executives']['labels']) !!},
                    {!! json_encode($charts['gender_equality_executives']['series']) !!},
                    'gender_equality_executives',
                    [color_female, color_male, color_other],
                    {!! json_encode($charts['gender_equality_executives']['unit']) !!},
                );
            @endif

            @if ($charts['gender_equality_leadership'])
                pieCharts(
                    {!! json_encode($charts['gender_equality_leadership']['labels']) !!},
                    {!! json_encode($charts['gender_equality_leadership']['series']) !!},
                    'gender_equality_leadership',
                    [color_female, color_male, color_other],
                    {!! json_encode($charts['gender_equality_leadership']['unit']) !!},
                );
            @endif

            // @if($charts['outsourced_workers'])
            //     pieCharts(
            //         {!! json_encode($charts['outsourced_workers']['labels']) !!},
            //         {!! json_encode($charts['outsourced_workers']['series']) !!},
            //         'outsourced_workers',
            //         [color_female, color_male, color_other],
            //         {!! json_encode($charts['outsourced_workers']['unit']) !!},
            //     );
            // @endif;

            @if($report['energy_consumption_sources'])
                pieCharts(
                    {!! json_encode($report['energy_consumption_sources']['labels']) !!},
                    {!! json_encode($report['energy_consumption_sources']['series']) !!},
                    'energy_consumption_sources',
                    ["#0D9401", "#99CA3C"],
                    {!! json_encode($report['energy_consumption_sources']['unit']) !!},
                );
            @endif;

            @if($report['waste_treated'])
                pieCharts(
                    {!! json_encode($report['waste_treated']['labels']) !!},
                    {!! json_encode($report['waste_treated']['series']) !!},
                    'waste_treated',
                    ["#0D9401", "#99CA3C"],
                    {!! json_encode($report['waste_treated']['unit']) !!},
                );
            @endif;

            @if($report['water_emissions_type'])
                pieCharts(
                    {!! json_encode($report['water_emissions_type']['labels']) !!},
                    {!! json_encode($report['water_emissions_type']['series']) !!},
                    'water_emissions_type',
                    ["#0D9401", "#99CA3C", "#02C6A1"],
                    {!! json_encode($report['water_emissions_type']['unit']) !!},
                );
            @endif;

            @if($report['distribution_gender'])
                pieCharts(
                    {!! json_encode($report['distribution_gender']['labels']) !!},
                    {!! json_encode($report['distribution_gender']['series']) !!},
                    'distribution_gender',
                    ["#21A6E8", "#C5A8FF", "#02C6A1"],
                    {!! json_encode($report['distribution_gender']['unit']) !!},
                );
            @endif;

            @if($report['color_leadership_roles'])
                pieCharts(
                    {!! json_encode($report['color_leadership_roles']['labels']) !!},
                    {!! json_encode($report['color_leadership_roles']['series']) !!},
                    'color_leadership_roles',
                    ["#FBB040", "#FAF9FB"],
                    {!! json_encode($report['color_leadership_roles']['unit']) !!},
                );
            @endif;

            @if ($charts['action_plan'])
                var data = {!! $charts['action_plan']['series'] !!};
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

                        for(var i=0; i < document.getElementsByClassName(actionPlanTableRow).length; i++) {
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
                            min: {!! $charts['action_plan']['xaxis']['min'] !!},
                            max: {!! $charts['action_plan']['xaxis']['max'] !!}
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
                            min: {!! $charts['action_plan']['yaxis']['min'] !!},
                            max: {!! $charts['action_plan']['yaxis']['max'] !!}
                        }
                    },
                    onHover: function(evt) {
                        let actionPlanTableRowAll = `action_plan_tr`;


                        var item = actionPlanChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                        if (item.length) {

                            for(var i=0; i < document.getElementsByClassName(actionPlanTableRowAll).length; i++) {
                                document.getElementsByClassName(actionPlanTableRowAll)[i].style.filter = 'blur(2px)';
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

            @if ($charts['co2_emissions'])
                barCharts(
                    {!! json_encode($charts['co2_emissions']['label']) !!},
                    {!! json_encode($charts['co2_emissions']['data']) !!},
                    'co2_emissions',
                    ["#008131", "#6AD794", "#98BDA6"],
                    {!! json_encode($charts['co2_emissions']['unit']) !!},
                );
            @endif

            @if ($report['atmospheric_pollutants'])
                barCharts(
                    {!! json_encode($report['atmospheric_pollutants']['label']) !!},
                    {!! json_encode($report['atmospheric_pollutants']['data']) !!},
                    'atmospheric_pollutants',
                    ["#99CA3C"],
                    {!! json_encode($report['atmospheric_pollutants']['uni']) !!},
                );
            @endif

            @if ($report['ozone_depleting'])
                barCharts(
                    {!! json_encode($report['ozone_depleting']['label']) !!},
                    {!! json_encode($report['ozone_depleting']['data']) !!},
                    'ozone_depleting',
                    ["#02C6A1"],
                    {!! json_encode($report['ozone_depleting']['unit']) !!},
                );
            @endif
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
        function pieCharts(labels, data, id, barColor, unit = '%')
        {
            var options = {
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: true,
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
                                return Math.round(value * 100 / sum) + unit;
                            },
                            font: {
                                weight: 'bold',
                                size: 10,
                            }
                        }
                    },
                    cutout: '70%',
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
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options: options,
                    plugins: [ChartDataLabels]
                };

            return new Chart(document.getElementById(id), config);
        }

    </script>
@endpush

@section('content')
    <div class="px-4 lg:px-0" x-data="{printview: false}">
        <div class="max-w-7xl mx-auto">

            <div class="flex justify-between items-center border-b border-esg7/50 mb-10 pb-5">
                <div class="text-base font-semibold text-esg8">
                    {{ $questionnaireinfo['company']['name'] }}
                </div>
                <div class="flex items-center flex-row-reverse gap-3">
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 print:hidden" @click="window.print()">
                        <x-slot name="buttonicon">
                            @includeFirst([tenant()->views . 'icons.download', 'icons.download'], ['class' => 'inline',
                            'color' => '#FFFFFF'  ])
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium">{{ __('Imprimir') }}</span>
                    </x-buttons.btn-icon-text>

                    <x-buttons.btn-icon-text class="!bg-esg4 !text-esg8 border-esg8  print:hidden" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]) }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium">{{ __('Voltar') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>

            {{-- Print text --}}
            <div class="pagebreak print:-mt-[150px]" >
                <div class="pagebreak print:h-full">
                    <img src="{{ global_asset('images/dashboard/screen-report-cover.jpeg')}}" class="">


                    <div class="bg-esg6 pt-20 text-center">
                        <p class="text-3xl font-bold text-esg27 text-center"> {{ __('SUSTAINABILITY REPORT') }} </p>
                        <p class="text-xl font-bold text-esg27 text-center mt-10"> {{ __('ESG | 2023') }} </p>
                        <p class="text-5xl font-bold text-esg27 text-center mt-28"> {{ __('C-MORE') }} </p>
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.logos.esg')</div>
                    </div>
                </div>

                <div class="pagebreak print:mt-20">
                    <p class="font-encodesans text-base font-bold leading-10 text-esg5"> {{ __('Table of Contents') }} </p>

                    <div class="flex justify-between">
                        <p class="font-encodesans text-xs font-bold leading-10 text-esg6 mt-4"> 1 {{ __('Company Overview') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg6 mt-4">  </p>
                    </div>

                    <div class="flex justify-between">
                        <p class="font-encodesans text-xs font-bold leading-10 text-esg6 mt-4"> 2 {{ __('Environment') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg6 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Impacts on climate and pollution') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Use of resources') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Use of water resources') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Impacts on biodiversity and ecosystems') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between">
                        <p class="font-encodesans text-xs font-bold leading-10 text-esg6 mt-4"> 3 {{ __('Social') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg6 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Contracted workers') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Subcontracted workers') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Relationship with customers and suppliers') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Affected communities') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between">
                        <p class="font-encodesans text-xs font-bold leading-10 text-esg6 mt-4"> 4 {{ __('Governance') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-[#008131] mt-4"> </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Corporate culture and business conduct policies') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Prevention and detection of corruption and bribery') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between pl-10">
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4"> {{ __('Management of the relationship with the value chain') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-esg8 mt-4">  </p>
                    </div>

                    <div class="flex justify-between">
                        <p class="font-encodesans text-xs font-bold leading-10 text-esg6 mt-4"> 5 {{ __('Statement of Responsibility') }} </p>
                        <p class="font-encodesans text-xs font-normal leading-10 text-[#008131] mt-4">  </p>
                    </div>
                </div>

                {{-- COMPNAY OVERVIEW --}}
                <div class="pagebreak print:h-full hidden print:block">
                    <img src="{{ global_asset('images/dashboard/chapter_1_cover.jpg')}}" class="h-[1100px]">

                    <div class="absolute w-[31.8%] h-56 text-esg27 -mt-[45%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%] print:-mt-[180%] print:h-full print:z-50">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.building')</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('1. COMPANY OVERVIEW') }}</p>
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-[1000px] bg-black/50 text-center print:-mt-[135%] print:w-[96%] hidden print:block">
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-20 bg-esg5 -mt-[28%] text-center grid place-content-center text-3xl font-bold print:w-[96%] print:-mt-[10%]">
                    </div>
                </div>

                <div class="pagebreak print:h-full block print:hidden relative">
                    <img src="{{ global_asset('images/dashboard/chapter_1_cover.jpg')}}" class="h-[1100px]">

                    <div class="absolute w-full h-56 text-esg27 -mt-[80%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%] print:-mt-[180%] print:h-full print:z-50">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.building')</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('1. COMPANY OVERVIEW') }}</p>
                    </div>

                    <div class="absolute z-40 w-full h-20 bg-esg5 -mt-[6.5%] text-center grid place-content-center text-3xl font-bold">
                    </div>
                </div>

                <div class="pagebreak print:mt-20 mb-20 print:mb-0">
                    <div class="flex gap-5 mt-10 print:mt-0">
                        @include('icons.building', ['color' => color(8), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg6 uppercase"> {{ __('company overview') }} </p>
                    </div>

                    <p class="font-encodesans text-base font-bold leading-10 text-esg5 uppercase"> {{ __('company name') }} </p>
                    <p class="font-encodesans text-base font-normal leading-10 text-esg8 uppercase mt-5"> {{ $questionnaireinfo['company']['name'] }} </p>

                    <p class="font-encodesans text-base font-bold leading-10 text-esg5 uppercase mt-10"> {{ __('nipc/nif') }} </p>
                    <p class="font-encodesans text-base font-normal leading-10 text-esg8 uppercase mt-5"> {{ $questionnaireinfo['company']['vat_number'] }} </p>

                    <p class="font-encodesans text-base font-bold leading-10 text-esg5 uppercase mt-10"> {{ __('report period') }} </p>
                    <p class="font-encodesans text-base font-normal leading-10 text-esg8 uppercase mt-5"> {{ date('d/m/Y', strtotime($questionnaireinfo['from'])) }} {{ __('to') }} {{ date('d/m/Y', strtotime($questionnaireinfo['to'])) }} </p>


                    <div class="grid grid-cols-2 gap-5">
                        @php $text = json_encode([__('Activity Sector')]); @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px]">
                            <p class="text-xl font-medium text-esg8"> {{ $business_sector['name'] ?? '' }} </p>
                        </x-cards.card-dashboard-version1>

                        @php $text = json_encode([__('Location')]); @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px]">
                            @foreach($country as $row)
                                <p class="text-xl font-medium text-esg8">{{ $row['name'] ?? '' }} </p>
                            @endforeach
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                {{-- Enviroment --}}
                <div class="pagebreak print:h-full hidden print:block">
                    <img src="{{ global_asset('images/dashboard/chapter_2_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-[31.8%] h-56 text-esg27 -mt-[45%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%] print:-mt-[180%] print:h-full print:z-50">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.categories.1', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('2. Environment') }}</p>
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-[1000px] bg-black/50 text-center print:-mt-[135%] print:w-[96%] hidden print:block">
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-20 bg-esg2 -mt-[28%] text-center grid place-content-center text-3xl font-bold print:w-[96%] print:-mt-[10%]">
                    </div>
                </div>

                <div class="pagebreak print:h-full block print:hidden relative">
                    <img src="{{ global_asset('images/dashboard/chapter_2_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-full h-56 text-esg27 -mt-[80%] text-center grid place-content-center text-3xl font-bold">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.categories.1', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('2. Environment') }}</p>
                    </div>

                    <div class="absolute z-40 w-full h-20 bg-esg2 -mt-[5%] text-center grid place-content-center text-3xl font-bold">
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.1', ['color' => color(2), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg2 uppercase"> {{ __('Environment') }} </p>
                    </div>

                    <div class="grid grid-cols-3 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Environmental Policy')}}</label>
                                <div class="grow">
                                    @if($report['environmental_policy'])
                                        @include('icons.checkbox', ['color' => $report['environmental_policy'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Benefits from Green Bonds')}}</label>
                                <div class="grow">
                                    @if($report['green_bonds'])
                                        @include('icons.checkbox', ['color' => $report['green_bonds'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Community impact strategy')}}</label>
                                <div class="grow">
                                    @if($report['impact_strategy'])
                                        @include('icons.checkbox', ['color' => $report['impact_strategy'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mt-5 print:mt-0">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Monitoring of environmental impact through indicators')}}</label>
                                <div class="grow">
                                    @if($report['monitoring'])
                                        @include('icons.checkbox', ['color' => $report['monitoring'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Adherence to external initiatives or set of principles in the field of sustainability')}}</label>
                                <div class="grow">
                                    @if($report['adherence'])
                                        @include('icons.checkbox', ['color' => $report['adherence'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>

                    <div class="mt-5">
                        @php
                            $text = json_encode([__('Strategic Sustainable Development Goals')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" class="print:!mt-5">
                            <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                                <div class="grid grid-cols-6 gap-3">
                                    <div class="w-full">
                                        @include('icons.goals.1', ['class' => 'inline-block', 'color' => in_array(376, $charts['sdgs_top5']) ? '#EA1D2D' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.2', ['class' => 'inline-block', 'color' => in_array(377, $charts['sdgs_top5']) ? '#D19F2A' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.3', ['class' => 'inline-block', 'color' => in_array(378, $charts['sdgs_top5']) ? '#2D9A47' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.4', ['class' => 'inline-block', 'color' => in_array(379, $charts['sdgs_top5']) ? '#C22033' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.5', ['class' => 'inline-block', 'color' => in_array(380, $charts['sdgs_top5']) ? '#EF412A' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.6', ['class' => 'inline-block', 'color' => in_array(381, $charts['sdgs_top5']) ? '#00ADD8' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.7', ['class' => 'inline-block', 'color' => in_array(382, $charts['sdgs_top5']) ? '#FDB714' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.8', ['class' => 'inline-block', 'color' => in_array(383, $charts['sdgs_top5']) ? '#8F1838' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.9', ['class' => 'inline-block', 'color' => in_array(384, $charts['sdgs_top5']) ? '#F36E24' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.10', ['class' => 'inline-block', 'color' => in_array(385, $charts['sdgs_top5']) ? '#E01A83' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.11', ['class' => 'inline-block', 'color' => in_array(386, $charts['sdgs_top5']) ? '#F99D25' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.12', ['class' => 'inline-block', 'color' => in_array(387, $charts['sdgs_top5']) ? '#CD8B2A' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.13', ['class' => 'inline-block', 'color' => in_array(388, $charts['sdgs_top5']) ? '#48773C' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.14', ['class' => 'inline-block', 'color' => in_array(389, $charts['sdgs_top5']) ? '#007DBB' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.15', ['class' => 'inline-block', 'color' => in_array(390, $charts['sdgs_top5']) ? '#40AE49' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.16', ['class' => 'inline-block', 'color' => in_array(391, $charts['sdgs_top5']) ? '#00558A' : '#DCDCDC'])
                                    </div>

                                    <div class="w-full">
                                        @include('icons.goals.17', ['class' => 'inline-block', 'color' => in_array(392, $charts['sdgs_top5']) ? '#1A3668' : '#DCDCDC'])
                                    </div>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5 mb-5">
                        @include('icons.categories.1', ['color' => color(2), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg2 uppercase"> {{ __('Environment') }} </p>
                    </div>

                    <p class="text-lg font-bold text-esg2"> {{ __('Impacts on climate and pollution') }} </p>


                    <p class="text-xs font-bold text-esg8 mt-10 print:hidden"> {{ __('Atmospheric emissions ') }} </p>
                    <div class="grid grid-cols-3 gap-5">
                        @if($charts['co2_emissions'])
                            @php $text = json_encode([__('GHG Emissions ('.$report['co2_emissions']['unit'].')')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!mt-5 !h-[250px]">
                                <x-charts.bar id="co2_emissions" class="m-auto relative !h-full !w-full" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if($report['atmospheric_pollutants'])
                            @php $text = json_encode([__('Atmospheric pollutants'), __('('.$report['atmospheric_pollutants']['unit'].')')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!mt-5 !h-[250px]">
                                <x-charts.bar id="atmospheric_pollutants" class="m-auto relative !h-full !w-full" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if($report['ozone_depleting'])
                            @php $text = json_encode([__('Ozone depleting substances'), __('('.$report['ozone_depleting']['unit'].')')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!mt-5 !h-[250px]">
                                <x-charts.bar id="ozone_depleting" class="m-auto relative !h-full !w-full" />
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>

                    <div class="grid grid-cols-3 gap-5 mt-5 print:mt-0">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Policy or practice to reduce its GHG emissions')}}</label>
                                <div class="grow">
                                    @if($report['ghg_emissions'])
                                        @include('icons.checkbox', ['color' => $report['ghg_emissions'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Activities are part of sectors with high climate impact')}}</label>
                                <div class="grow">
                                    @if($report['climate_impact'])
                                        @include('icons.checkbox', ['color' => $report['climate_impact'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Activities in the fossil fuel sector')}}</label>
                                <div class="grow">
                                    @if($report['fossil_fuel'])
                                        @include('icons.checkbox', ['color' => $report['fossil_fuel'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        @if(isset($charts['energy_consumption']['value']))
                            @php $text = json_encode([__('Energy consumption')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!mt-5 !h-[250px]">
                                <x-charts.icon-number value="{{ $charts['energy_consumption']['value'] }}" unit="{{ $charts['energy_consumption']['unit'] }}">
                                    @include('icons.dashboard.gestao-energia', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if($report['energy_consumption_sources'])
                            @php
                                $text = json_encode([__('Energy consumption by sources')]);
                                $subpoint = json_encode([ ['color' => 'bg-[#0D9401]', 'text' => __('Renewable')], ['color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable')]]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!mt-5 !h-[250px]" type="flex" subpoint="{{ $subpoint }}">
                                <x-charts.donut id="energy_consumption_sources" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>
                </div>


                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5 mb-5">
                        @include('icons.categories.1', ['color' => color(2), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg2 uppercase"> {{ __('Environment') }} </p>
                    </div>

                    <div class="grid grid-cols-2 gap-5 print:-mt-10">
                        @if(!empty($report['non_renewable']))
                            @php $text = json_encode([__('Non-renewable sources')]); @endphp
                            <x-cards.card-dashboard-version1 class="!h-auto print:!mt-5" text="{{ $text }}">
                                <div class="mt-10">
                                    @foreach( explode("\n", $report['non_renewable'][1]) as $row)
                                        @if($row != '')
                                            <div class="flex">
                                                <div class="text-xl">
                                                    <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg2"></span>
                                                </div>
                                                <div class="pl-2 inline-block text-xs text-esg8/70">{{$row}}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </x-cards.card-dashboard-version1>
                        @endif

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Policy or practice to reduce energy consumption')}}</label>
                                <div class="grow">
                                    @if($report['reduce_energy'])
                                        @include('icons.checkbox', ['color' => $report['reduce_energy'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>

                    <p class="text-lg font-bold text-esg2 mt-10"> {{ __('Use of resources') }} </p>

                    <div class="grid grid-cols-2 gap-5">
                        @if(isset($charts['waste_generated']['value']))
                            @php $text = json_encode([__('Total waste generated')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}"  class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $charts['waste_generated']['value'] }}" unit="{{ $charts['waste_generated']['unit'] }}">
                                    @include('icons.dashboard.residues', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if($report['waste_treated'])
                            @php
                                $text = json_encode([__('Total waste treated or recycled')]);
                                $subpoint = json_encode([ ['color' => 'bg-[#0D9401]', 'text' => __('Treated or recycled')], ['color' => 'bg-[#99CA3C]', 'text' => __('Sent for disposal')]]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5" type="flex" subpoint="{{ $subpoint }}">
                                <x-charts.donut id="waste_treated" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if(isset($charts['hazardous_waste']['value']))
                            @php $text = json_encode([__('Total of hazardous waste and/or radioactive waste')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $charts['hazardous_waste']['value'] }}" unit="{{ $charts['hazardous_waste']['unit'] }}" >
                                    @include('icons.dashboard.trash', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>
                </div>

                <div class="pagebreak print:mt-20">

                    <p class="text-lg font-bold text-esg2 mt-10"> {{ __('Use of water resources') }} </p>

                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Organization located in an area of ​​high water stress')}}</label>
                                <div class="grow">
                                    @if($report['high_water_stress'])
                                        @include('icons.checkbox', ['color' => $report['high_water_stress'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Activities that include the exploitation of seas and/or oceans')}}</label>
                                <div class="grow">
                                    @if($report['exploration_seas'])
                                        @include('icons.checkbox', ['color' => $report['exploration_seas'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mt-5 print:mt-0">
                        @if(isset($charts['water_consumption']['value']))
                            @php  $text = json_encode([__('Consumption and use of water')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[300px] relative print:!mt-5">
                                <p class="text-esg8 font-medium text-5xl absolute w-full  text-center mt-24 -ml-4">{{ $charts['water_consumption']['value'] }} <span class="text-esg8 font-normal text-base">{{ $charts['water_consumption']['unit'] }}</span></p>
                                @include('icons.dashboard.liquid', ['color' => color(2)])
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if(isset($report['recycled_water']['value']))
                            @php $text = json_encode([__('Water recycled and/or reused')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[300px] print:!mt-5">
                                <x-charts.icon-number value="{{ $report['recycled_water']['value'] }}" unit="{{ $report['recycled_water']['unit'] }}">
                                    @include('icons.dashboard.recicle-water', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5 mb-5">
                        @include('icons.categories.1', ['color' => color(2), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg2 uppercase"> {{ __('Environment') }} </p>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Water discharge')}}</label>
                                <div class="grow">
                                    @if($report['water_discharge'])
                                        @include('icons.checkbox', ['color' => $report['water_discharge'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Monitoring of water emissions')}}</label>
                                <div class="grow">
                                    @if($report['water_emissions'])
                                        @include('icons.checkbox', ['color' => $report['water_emissions'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        @if($report['water_emissions_type'])
                            @php
                                $text = json_encode([__('Water emissions by type of parameter')]);
                                $subpoint = json_encode([ ['color' => 'bg-[#0D9401]', 'text' => __('Physico-chemical')], ['color' => 'bg-[#99CA3C]', 'text' => __('Undesirable substances')], ['color' => 'bg-[#02C6A1]', 'text' => __('Toxic substances')]]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5" type="flex" subpoint="{{ $subpoint }}">
                                <x-charts.donut id="water_emissions_type" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>

                    <p class="text-lg font-bold text-esg2 mt-10"> {{ __('Impacts on biodiversity and ecosystems') }} </p>

                    <div class="grid grid-cols-2 gap-5 mt-5 print:mt-0">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Activities with a negative impact on biodiversity')}}</label>
                                <div class="grow">
                                    @if($report['impact_biodiversity'])
                                        @include('icons.checkbox', ['color' => $report['impact_biodiversity'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Organization located in a sensitive area from the point of view of biodiversity')}}</label>
                                <div class="grow">
                                    @if($report['organization_sensitive_area'])
                                        @include('icons.checkbox', ['color' => $report['organization_sensitive_area'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                <div class="pagebreak mt-5 print:mt-20">
                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Activities related to the manufacture or sale of controversial weapons')}}</label>
                                <div class="grow">
                                    @if($report['manufacture_sale_control'])
                                        @include('icons.checkbox', ['color' => $report['manufacture_sale_control'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Activities included in the manufacture of pesticides and other agrochemical products')}}</label>
                                <div class="grow">
                                    @if($report['manufacture_pesticides'])
                                        @include('icons.checkbox', ['color' => $report['manufacture_pesticides'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Activities that contribute to soil degradation, desertification, artificialization and/or soil waterproofing')}}</label>
                                <div class="grow">
                                    @if($report['contribute_soil_degradation'])
                                        @include('icons.checkbox', ['color' => $report['contribute_soil_degradation'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Acting according to soil use practices or policies')}}</label>
                                <div class="grow">
                                    @if($report['soil_use_practices'])
                                        @include('icons.checkbox', ['color' => $report['soil_use_practices'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Forestry activities')}}</label>
                                <div class="grow">
                                    @if($report['forestry'])
                                        @include('icons.checkbox', ['color' => $report['forestry'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('New construction and/or major renovation works')}}</label>
                                <div class="grow">
                                    @if($report['new_construction'])
                                        @include('icons.checkbox', ['color' => $report['new_construction'] ? color(2) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        @if(is_numeric($report['construction_renovation']))
                            @php  $text = json_encode([__('Recovered, recycled and/or of biological origin materials used in construction or renovation works')]); @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[300px] print:!mt-5">
                                <x-charts.icon-number value="{{ $report['construction_renovation'] }}" unit="%">
                                    @include('icons.dashboard.recicle-water', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>
                </div>

                {{-- Social --}}
                <div class="pagebreak print:h-full mt-10 print:mt-0 hidden print:block">
                    <img src="{{ global_asset('images/dashboard/chapter_3_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-[31.8%] h-56 text-esg27 -mt-[45%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%] print:-mt-[180%] print:h-full print:z-50">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.categories.2', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('3. Social') }}</p>
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-[1000px] bg-black/50 text-center print:-mt-[135%] print:w-[96%] hidden print:block">
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-20 bg-esg1 -mt-[28%] text-center grid place-content-center text-3xl font-bold print:w-[96%] print:-mt-[10%]">
                    </div>
                </div>

                <div class="pagebreak print:h-full mt-10 print:mt-0 block print:hidden relative">
                    <img src="{{ global_asset('images/dashboard/chapter_3_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-full h-56 text-esg27 -mt-[80%] text-center grid place-content-center text-3xl font-bold">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.categories.2', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('3. Social') }}</p>
                    </div>

                    <div class="absolute z-40 w-full h-20 bg-esg1 -mt-[5%] text-center grid place-content-center text-3xl font-bold">
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.2', ['color' => color(1), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg1 uppercase"> {{ __('Social') }} </p>
                    </div>

                    <p class="text-lg font-bold text-esg1 mt-10"> {{ __('Contracted workers') }} </p>

                    <div class="grid grid-cols-2 gap-5">
                        @if($charts['gender_equility_employees'])
                            @php
                                $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                                $text = json_encode([__('Distribution by gender')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5" subpoint="{{ $subpoint }}" type="flex">
                                <x-charts.donut id="gender_equility_employees" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if($charts['gender_equality_executives'])
                            @php
                                $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                                $text = json_encode([__('Gender distribution in executive positions')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5" subpoint="{{ $subpoint }}" type="flex">
                                <x-charts.donut id="gender_equality_executives" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if($charts['gender_equality_leadership'])
                            @php
                                $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                                $text = json_encode([__('Distribution by gender in leadership positions')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5" subpoint="{{ $subpoint }}" type="flex">
                                <x-charts.donut id="gender_equality_leadership" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if(is_numeric($charts['layoffs']))
                            @php
                                $text = json_encode([__('Layoffs in the last 12 months')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $charts['layoffs'] }}">
                                    @include('icons.dashboard.employement', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>

                    <div class="grid grid-cols-3 gap-5 mt-5 print:mt-0">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Remuneration Policy')}}</label>
                                <div class="grow">
                                    @if($report['remuneration_policy'])
                                        @include('icons.checkbox', ['color' => $report['remuneration_policy'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Provision of data relating to your salary scale')}}</label>
                                <div class="grow">
                                    @if($report['provision_data_salary'])
                                        @include('icons.checkbox', ['color' => $report['provision_data_salary'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Occupational Health and Safety Policy (OSH)')}}</label>
                                <div class="grow">
                                    @if($report['osh'])
                                        @include('icons.checkbox', ['color' => $report['osh'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.2', ['color' => color(1), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg1 uppercase"> {{ __('Social') }} </p>
                    </div>

                    <div class="grid grid-cols-2 gap-5">

                        @if(is_numeric($report['accidents_at_work_during']))
                            @php
                                $text = json_encode([__('Accidents at work during the reporting period')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $report['accidents_at_work_during'] }}">
                                    @include('icons.dashboard.work-accident', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if(isset($charts['work_days_lost']['value']))
                            @php
                                $text = json_encode([__('Days lost due to injury, accident, death or illness')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $charts['work_days_lost']['value'] }}" >
                                    @include('icons.dashboard.work-accident', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif


                        @if(isset($report['gender_pay_gap']['value']))
                            @php
                                $text = json_encode([__('Gender pay gap')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $report['gender_pay_gap']['value'] }}" unit="{{ $report['gender_pay_gap']['unit'] }}">
                                    {{-- @includeFirst([tenant()->views . 'icons.dashboard.igualdade-salarial', 'icons.dashboard.igualdade-salarial'], ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50]) --}}
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if(is_numeric($report['contract_workers']))
                            @php
                                $text = json_encode([__('Contract workers earning the national minimum wage')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $report['contract_workers'] }}">
                                    @include('icons.dashboard.income-money', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Code of Ethics and Conduct')}}</label>
                                <div class="grow">
                                    @if($report['code_ethics_conduct'])
                                        @include('icons.checkbox', ['color' => $report['code_ethics_conduct'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Training on code of ethics and conduct topics')}}</label>
                                <div class="grow">
                                    @if($report['training_code_ethics'])
                                        @include('icons.checkbox', ['color' => $report['training_code_ethics'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.2', ['color' => color(1), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg1 uppercase"> {{ __('Social') }} </p>
                    </div>

                    <p class="text-lg font-bold text-esg1 mt-10"> {{ __('Subcontracted workers') }} </p>

                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Knowledge of the organization salary conditions')}}</label>
                                <div class="grow">
                                    @if($report['organization_salary'])
                                        @include('icons.checkbox', ['color' => $report['organization_salary'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Guarantee of Occupational Safety and Health (OSH) conditions')}}</label>
                                <div class="grow">
                                    @if($report['occupational_safety'])
                                        @include('icons.checkbox', ['color' => $report['occupational_safety'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        @if($report['distribution_gender'])
                            @php
                                $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                                $text = json_encode([__('Distribution by gender')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5" subpoint="{{ $subpoint }}" type="flex">
                                <x-charts.donut id="distribution_gender" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.2', ['color' => color(1), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg1 uppercase"> {{ __('Social') }} </p>
                    </div>

                    <p class="text-lg font-bold text-esg1 mt-10"> {{ __('Relationship with customers and suppliers') }} </p>
                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Customer data privacy policy')}}</label>
                                <div class="grow">
                                    @if($report['customer_data_privacy'])
                                        @include('icons.checkbox', ['color' => $report['customer_data_privacy'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Supplier selection policy')}}</label>
                                <div class="grow">
                                    @if($report['supplier_selection_policy'])
                                        @include('icons.checkbox', ['color' => $report['supplier_selection_policy'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.2', ['color' => color(1), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg1 uppercase"> {{ __('Social') }} </p>
                    </div>

                    <p class="text-lg font-bold text-esg1 mt-10"> {{ __('Affected communities') }} </p>
                    <div class="grid grid-cols-2 gap-5">
                        @if(is_numeric($report['incidents_discrimination']))
                            @php
                                $text = json_encode([__('Incidents of discrimination resulting in the application of sanctions')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5">
                                <x-charts.icon-number value="{{ $report['incidents_discrimination'] }}">
                                    @include('icons.dashboard.law', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                                </x-charts.icon-number>
                            </x-cards.card-dashboard-version1>
                        @endif

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Involvement in a case of human rights violation')}}</label>
                                <div class="grow">
                                    @if($report['case_violation_human'])
                                        @include('icons.checkbox', ['color' => $report['case_violation_human'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Due diligence process to identify, mitigate and combat negative human rights impacts')}}</label>
                                <div class="grow">
                                    @if($report['diligence_process_identify'])
                                        @include('icons.checkbox', ['color' => $report['diligence_process_identify'] ? color(1) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.2', ['color' => color(1), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg1 uppercase"> {{ __('Social') }} </p>
                    </div>

                    <p class="text-lg font-bold text-esg1 mt-10"> {{ __('Comunidades afetadas') }} </p>
                    <div class="grid grid-cols-2 gap-5">
                        @if($report['color_leadership_roles'])
                            @php
                                $text = json_encode([__('Pessoas de cor em cargos de liderança')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-auto print:!mt-5" type="flex">
                                <x-charts.donut id="color_leadership_roles" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        @php
                            $text = json_encode([__('Iniciativas voltadas para grupos minorizados')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-auto print:!mt-5">
                            <div class="flex items-center mr-4 mb-5 mt-10">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Pessoas com deficiência')}}</label>
                                <div class="ml-10 grow">
                                    @include( in_array(1330, $report['minority_groups']) ? 'icons.checkbox' : 'icons.close-checkbox', ['color' => in_array(1330, $report['minority_groups']) ? color(1) : color(7)])
                                </div>
                            </div>

                            <div class="flex items-center mr-4 mb-5">
                                <label for="checkbox-policy" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Mulheres')}}</label>
                                <div class="ml-10 grow">
                                    @include(in_array(1331, $report['minority_groups']) ? 'icons.checkbox' : 'icons.close-checkbox', ['color' => in_array(1331, $report['minority_groups']) ? color(1) : color(7)])
                                </div>
                            </div>

                            <div class="flex items-center mr-4 mb-5">
                                <label for="checkbox-pa" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Pessoas negras')}}</label>
                                <div class="ml-10 grow">
                                    @include(in_array(1332, $report['minority_groups']) ? 'icons.checkbox' : 'icons.close-checkbox', ['color' => in_array(1332, $report['minority_groups']) ? color(1) : color(7)])
                                </div>
                            </div>

                            <div class="flex items-center mr-4 mb-5">
                                <label for="checkbox-ethics" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('População originária')}}</label>
                                <div class="ml-10 grow">
                                    @include(in_array(1333, $report['minority_groups']) ? 'icons.checkbox' : 'icons.close-checkbox', ['color' => in_array(1333, $report['minority_groups']) ? color(1) : color(7)])
                                </div>
                            </div>

                            <div class="flex items-center mr-4 mb-5">
                                <label for="checkbox-environmental" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('LGBTQIAP+')}}</label>
                                <div class="ml-10 grow">
                                    @include(in_array(1334, $report['minority_groups']) ? 'icons.checkbox' : 'icons.close-checkbox', ['color' => in_array(1334, $report['minority_groups']) ? color(1) : color(7)])
                                </div>
                            </div>

                            <div class="flex items-center mr-4 mb-5">
                                <label for="checkbox-safety" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Faixas geracionais/etárias')}}</label>
                                <div class="ml-10 grow">
                                    @include(in_array(1335, $report['minority_groups']) ? 'icons.checkbox' : 'icons.close-checkbox', ['color' => in_array(1335, $report['minority_groups']) ? color(1) : color(7)])
                                </div>
                            </div>

                            <div class="flex items-center mr-4 mb-5">
                                <label for="checkbox-corruption" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Refugiados')}}</label>
                                <div class="ml-10 grow">
                                    @include(in_array(1336, $report['minority_groups']) ? 'icons.checkbox' : 'icons.close-checkbox', ['color' => in_array(1336, $report['minority_groups']) ? color(1) : color(7)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        @if($report['opportunity_targets_minority'])
                            @php
                                $text = json_encode([__('Metas de oportunidades para os grupos de minorias')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[250px] print:!mt-5" type="flex">
                                <div class="mt-12 text-xs text-esg8/80 font-normal">{{ $report['opportunity_targets_minority'] }}</div>
                            </x-cards.card-dashboard-version1>
                        @endif

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Programa de literacia para diversidade incluindo colaboradores e C-level')}}</label>
                                <div class="grow">
                                    @include( $report['diversity_literacy'] ? 'icons.checkbox' : 'icons.close-checkbox' , ['color' => $report['diversity_literacy'] ? color(1) : color(7)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                {{-- Governance --}}
                <div class="pagebreak print:h-full mt-10 print:mt-0 hidden print:block">
                    <img src="{{ global_asset('images/dashboard/chapter_4_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-[31.8%] h-56 text-esg27 -mt-[45%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%] print:-mt-[180%] print:h-full print:z-50">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.categories.2', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('4. Governance') }}</p>
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-[1000px] bg-black/50 text-center print:-mt-[135%] print:w-[96%] hidden print:block">
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-20 bg-esg3 -mt-[28%] text-center grid place-content-center text-3xl font-bold print:w-[96%] print:-mt-[10%]">
                    </div>
                </div>

                <div class="pagebreak print:h-full mt-10 print:mt-0 block print:hidden relative">
                    <img src="{{ global_asset('images/dashboard/chapter_4_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-full h-56 text-esg27 -mt-[80%] text-center grid place-content-center text-3xl font-bold">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.categories.2', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('4. Governance') }}</p>
                    </div>

                    <div class="absolute z-40 w-full h-20 bg-esg3 -mt-[5%] text-center grid place-content-center text-3xl font-bold">
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.3', ['color' => color(3), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg3 uppercase"> {{ __('governance') }} </p>
                    </div>


                    <p class="text-lg font-bold text-esg3 mt-10"> {{ __('Corporate culture and business conduct policies') }} </p>
                    <div class="grid grid-cols-2 gap-5">

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Web site')}}</label>
                                <div class="grow">
                                    @if($report['web_site'])
                                        @include('icons.checkbox', ['color' => $report['web_site'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Institutional presentation')}}</label>
                                <div class="grow">
                                    @if($report['institutional_presentation'])
                                        @include('icons.checkbox', ['color' => $report['institutional_presentation'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Organizational structure')}}</label>
                                <div class="grow">
                                    @if($report['organizational_structure'])
                                        @include('icons.checkbox', ['color' => $report['organizational_structure'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Preparation and/or presentation of annual reports')}}</label>
                                <div class="grow">
                                    @if($report['presentation_annual_reports'])
                                        @include('icons.checkbox', ['color' => $report['presentation_annual_reports'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        @if($report['constitution'])
                            @php $text = json_encode([__('Constitution')]); @endphp
                            <x-cards.card-dashboard-version1 class="!h-[250px] print:!mt-5" text="{{$text}}">
                                <div class="flex flex-col gap-5 items-center mr-4 mb-5 text-xl font-medium text-esg8 capitalize">
                                    {{ $report['constitution'] }}
                                </div>
                            </x-cards.card-dashboard-version1>
                        @endif

                        @if($charts['gender_high_governance_body'])
                            @php
                                $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                                $text = json_encode([__('Gender distribution in highest governance body')]);
                            @endphp
                            <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" type="flex" class="!h-[250px] print:!mt-5">
                                <x-charts.donut id="gender_high_governance_body" class="m-auto !h-[200px] !w-[200px]" />
                            </x-cards.card-dashboard-version1>
                        @endif

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('President as CEO')}}</label>
                                <div class="grow">
                                    @if($report['president_ceo'])
                                        @include('icons.checkbox', ['color' => $report['president_ceo'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Code of ethics and conduct for suppliers')}}</label>
                                <div class="grow">
                                    @if($report['code_conduct_suppliers'])
                                        @include('icons.checkbox', ['color' => $report['code_conduct_suppliers'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-20">
                    <div class="flex gap-5">
                        @include('icons.categories.3', ['color' => color(3), 'width' => 31, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg3 uppercase"> {{ __('governance') }} </p>
                    </div>


                    <p class="text-lg font-bold text-esg3 mt-10"> {{ __('Prevention and detection of corruption and bribery') }} </p>
                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Plano de prevenção de riscos de corrupção')}}</label>
                                <div class="grow">
                                    @if($report['corruption_risk'])
                                        @include('icons.checkbox', ['color' => $report['corruption_risk'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('Política para prevenir e tratar situações de conflitos de interesse')}}</label>
                                <div class="grow">
                                    @if($report['policy_to_prevent'])
                                        @include('icons.checkbox', ['color' => $report['policy_to_prevent'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>

                    <p class="text-lg font-bold text-esg3 mt-10"> {{ __('Management of the relationship with the value chain') }} </p>
                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('System for receiving complaints from workers (contractors and subcontractors) anonymously')}}</label>
                                <div class="grow">
                                    @if($report['complaints_from_workers'])
                                        @include('icons.checkbox', ['color' => $report['complaints_from_workers'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('System for receiving complaints from customers')}}</label>
                                <div class="grow">
                                    @if($report['complaints_from_customers'])
                                        @include('icons.checkbox', ['color' => $report['complaints_from_customers'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>

                        <x-cards.card-dashboard-version1 class="!h-[150px] print:!mt-5">
                            <div class="flex flex-col gap-5 items-center mr-4 mb-5">
                                <label for="checkbox-website" class="ml-4 font-encodesans text-xs font-normal text-esg8/80 w-full">{{__('System for receiving complaints from suppliers')}}</label>
                                <div class="grow">
                                    @if($report['complaints_from_suppliers'])
                                        @include('icons.checkbox', ['color' => $report['complaints_from_suppliers'] ? color(3) : color(7)])
                                    @else
                                        @include('icons.close-checkbox')
                                    @endif
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    </div>
                </div>

                {{-- Statement of Responsibility --}}
                <div class="pagebreak mt-10 print:mt-0 print:h-full hidden print:block">
                    <img src="{{ global_asset('images/dashboard/chapter_5_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-[31.8%] h-56 text-esg27 -mt-[45%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%] print:-mt-[180%] print:h-full print:z-50">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.notes1', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('5. Statement of Responsibility') }}</p>
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-[1000px] bg-black/50 text-center print:-mt-[135%] print:w-[96%] hidden print:block">
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-20 bg-esg3 -mt-[28%] text-center grid place-content-center text-3xl font-bold print:w-[96%] print:-mt-[10%]">
                    </div>
                </div>

                <div class="pagebreak mt-10 print:mt-0 print:h-full block print:hidden relative">
                    <img src="{{ global_asset('images/dashboard/chapter_5_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute w-full h-56 text-esg27 -mt-[80%] text-center grid place-content-center text-3xl font-bold print:w-[90.5%]">
                        <div class="grid justify-items-center mt-28 pb-16">@include('icons.notes1', ['width' => 80, 'height' => 100, 'color' => color(4)])</div>
                        <p class="text-4xl font-medium text-esg27">{{ __('5. Statement of Responsibility') }}</p>
                    </div>

                    <div class="absolute z-40 w-full h-20 bg-esg3 -mt-[6.5%] text-center grid place-content-center text-3xl font-bold print:w-[96%]">
                    </div>
                </div>

                <div class="pagebreak print:mt-20">
                    <div class="flex gap-5 mt-10 print:mt-0">
                        @include('icons.dashboard.notes1', ['color' => color(6), 'width' => 30, 'height' => 30])
                        <p class="font-encodesans text-base font-medium leading-10 text-esg6 uppercase"> {{ __('statement of responsability') }} </p>
                    </div>

                    <p class="text-esg8 font-normal text-xs mt-5">
                        @foreach ($report['statement_responsability'] ?? [] as $row)
                            {{ $row }}
                        @endforeach
                    </p>
                    <p class="text-esg8 font-bold text-xs mt-2"> {{ auth()->user()->name }} </p>
                    <p class="text-esg8 font-normal text-xs"> {{ __('Submitted on') }} {{ date('d/m/Y', strtotime($questionnaire['submitted_at'])) }} </p>
                </div>

                {{-- LAST --}}
                <div class="print:h-full  hidden print:block">
                    <img src="{{ global_asset('images/dashboard/back_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute z-40 w-[31.8%] h-[1070px] bg-esg6/90 text-center print:-mt-[135%] print:w-[96%] hidden print:block">
                    </div>

                    <div class="absolute z-40 w-[31.8%] h-20 -mt-[35%] text-center grid place-content-center text-3xl font-bold print:w-[96%] print:-mt-[15%]">
                        <div class="flex gap-10">
                            @include('logos.cmore9') @include('icons.logos.esg')
                        </div>
                    </div>
                </div>

                <div class="print:h-full block print:hidden relative">
                    <img src="{{ global_asset('images/dashboard/back_cover.jpg')}}" class="h-[1100px] w-[100%]">

                    <div class="absolute z-40 w-full h-[1100px] bg-esg6/90 text-center -mt-[90.5%]">
                    </div>

                    <div class="absolute z-40 w-full h-20 -mt-[10%] text-center grid place-content-center text-3xl font-bold">
                        <div class="flex gap-10">
                            @include('logos.cmore9') @include('icons.logos.esg')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
