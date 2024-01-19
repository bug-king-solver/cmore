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
                    [color_female, color_male, color_other]
                );
            @endif

            @if ($charts['gender_high_governance_body'])
                pieCharts(
                    {!! json_encode($charts['gender_high_governance_body']['labels']) !!},
                    {!! json_encode($charts['gender_high_governance_body']['series']) !!},
                    'gender_high_governance_body',
                    [color_female, color_male, color_other]
                );
            @endif

            @if ($charts['gender_equality_executives'])
                pieCharts(
                    {!! json_encode($charts['gender_equality_executives']['labels']) !!},
                    {!! json_encode($charts['gender_equality_executives']['series']) !!},
                    'gender_equality_executives',
                    [color_female, color_male, color_other]
                );
            @endif

            @if ($charts['gender_equality_leadership'])
                pieCharts(
                    {!! json_encode($charts['gender_equality_leadership']['labels']) !!},
                    {!! json_encode($charts['gender_equality_leadership']['series']) !!},
                    'gender_equality_leadership',
                    [color_female, color_male, color_other]
                );
            @endif

            @if($charts['outsourced_workers'])
                pieCharts(
                    {!! json_encode($charts['outsourced_workers']['labels']) !!},
                    {!! json_encode($charts['outsourced_workers']['series']) !!},
                    'outsourced_workers',
                    [color_female, color_male, color_other]
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
                    ["#008131", "#6AD794", "#98BDA6"]
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
        function pieCharts(labels, data, id, barColor)
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
                                return Math.round(value * 100 / sum) + '%';
                            },
                            font: {
                                weight: 'bold',
                                size: 15,
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

            <div class="flex justify-between items-center border-b border-esg7/50 mb-10 pb-5 print:hidden">
                <div class="text-base font-semibold text-esg8">
                    {{ $questionnaireinfo['company']['name'] }}
                </div>
                <div>
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]).'?print=true' }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium">{{ __('Ver relatório') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>

            <div class="mt-5 pagebreak">
                <div class="">
                    @php $text = json_encode([__('Achievements')]); @endphp
                    <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-auto">
                        <div class="grid grid-cols-2 self-center sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-10 print:grid-cols-6 mt-10">
                            <div class="mb-4" title="{{ __('Login to ESG Maturity') }}">
                                @include('icons.badges.badge', $charts['badges'][1] ? ['color' => color('esg5'), 'bgcolor' => '#F0F0F0'] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-7 absolute">
                                    @include('icons.badges.1', ! $charts['badges'][1] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Questionnaire submitted') }}">
                                @include('icons.badges.badge', $charts['badges'][2] ? ['color' => color('esg5')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.7rem] ml-7 absolute">
                                    @include('icons.badges.2', ! $charts['badges'][2] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Complaint Mechanisms') }}">
                                @include('icons.badges.badge', $charts['badges'][3] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-6 absolute">
                                    @include('icons.badges.3', ! $charts['badges'][3] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Master of politics') }}">
                                @include('icons.badges.badge', $charts['badges'][4] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-6 absolute">
                                    @include('icons.badges.4', ! $charts['badges'][4] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Master of reports') }}">
                                @include('icons.badges.badge', $charts['badges'][5] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-7 absolute">
                                    @include('icons.badges.5', ! $charts['badges'][5] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Environmentally conscious') }}">
                                @include('icons.badges.badge', $charts['badges'][6] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-[1.7rem] absolute">
                                    @include('icons.badges.6', ! $charts['badges'][6] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Social responsability') }}">
                                @include('icons.badges.badge', $charts['badges'][7] ? ['color' => color('esg1')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-[1.6rem] absolute">
                                    @include('icons.badges.7', ! $charts['badges'][7] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Customer oriented') }}">
                                @include('icons.badges.badge', $charts['badges'][8] ? ['color' => color('esg1')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-6 absolute">
                                    @include('icons.badges.8', ! $charts['badges'][8] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Gender equality') }}">
                                @include('icons.badges.badge' , $charts['badges'][9] ? ['color' => color('esg1')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-7 absolute">
                                    @include('icons.badges.9', ! $charts['badges'][9] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Select your suppliers') }}">
                                @include('icons.badges.badge', $charts['badges'][10] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-[1.7rem] absolute">
                                    @include('icons.badges.10', ! $charts['badges'][10] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Measure and monitor your environmental journey') }}">
                                @include('icons.badges.badge', $charts['badges'][11] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-[1.6rem] absolute">
                                    @include('icons.badges.11', ! $charts['badges'][11] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Attentive to the SDGs') }}">
                                @include('icons.badges.badge', $charts['badges'][12] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.9rem] ml-[1.52rem] absolute">
                                    @include('icons.badges.12', ! $charts['badges'][12] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Committed to your carbon footprint') }}">
                                @include('icons.badges.badge', $charts['badges'][13] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-6 absolute">
                                    @include('icons.badges.13', ! $charts['badges'][13] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Committed to your energy efficiency') }}">
                                @include('icons.badges.badge', $charts['badges'][14] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-6 absolute">
                                    @include('icons.badges.14', ! $charts['badges'][14] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Signatory / Adherence to global principles') }}">
                                @include('icons.badges.badge', $charts['badges'][15] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-[1.6rem] absolute">
                                    @include('icons.badges.15', ! $charts['badges'][15] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                            <div class="mb-4" title="{{ __('Evidence') }}">
                                @include('icons.badges.badge', $charts['badges'][16] ? ['color' => color('esg5')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                                <div class="-mt-[4.8rem] ml-[1.6rem] absolute">
                                    @include('icons.badges.16', ! $charts['badges'][16] ? ['color'=>'#AFAFAF'] : ['color'=> color(8)])
                                </div>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 mt-10 gap-5">
                    @if ($charts['action_plan'])
                        @php $text = json_encode([__('Action Plans - Prority Matrix')]); @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[550px]">
                            <div x-data="{showExtraLegend: false}" class="md:col-span-1 lg:p-5 xl:p-10 ">
                                <div @mouseover="showExtraLegend = true" @mouseover.away="showExtraLegend = false"  class="relative w-full">
                                    <div class="h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px]">
                                        <div></div>
                                        <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                        <div class="text-esg8 absolute left-[31px] top-[15px] rotate-90 text-4xl">
                                            @include('icons.arrow', ['class' => 'rotate-180', 'fill' => color(7)])
                                        </div>
                                        <div
                                            class="text-esg8 absolute left-[310px] top-[300px] sm:left-[465px] sm:top-[448px] text-4xl">
                                            @include('icons.arrow', ['fill' => color(7)])
                                        </div>
                                        <div x-show="showExtraLegend" class="absolute left-[50px] top-[60px] text-sm text-esg9">{{ __('Highly Recommended') }}</div>
                                        <div x-show="showExtraLegend" class="absolute left-[50px] bottom-[60px] text-sm text-esg9">{{ __('Recommended') }}</div>
                                        <div x-show="showExtraLegend" class="absolute right-[60px] top-[60px] text-sm text-esg9">{{ __('High Criticality') }}</div>
                                        <div x-show="showExtraLegend" class="absolute right-[60px] bottom-[60px] text-sm text-esg9">{{ __('High Priority') }}</div>
                                    </div>
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if ($charts['action_plan_table'])
                        @php $text = json_encode([__('Action Plans')]); @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-[550px]">
                            <div id="action_list" class="md:col-span-1 lg:p-5 xl:p-10 lg:mt-0 h-[450px] overflow-x-auto">

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
                                        @foreach ($charts['action_plan_table'] as $initiative)
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
                        </x-cards.card-dashboard-version1>
                    @endif
                </div>
            </div>

            <div class="mt-10 pagebreak">
                @php $text = json_encode([__('Documentation')]); @endphp
                <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 mt-10">
                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-website" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Website')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['website'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-policy" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Supplier Policy')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['supplier_policy'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-pa" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Institutional Presentation')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['institutional'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-ethics" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Code of Ethics and Supplier Conduct')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['code_of_ethics_supplier'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-environmental" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Environmental Policy')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['environmental_policy'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-safety" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Occupational Health and Safety Policy')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['health_and_safety_policy'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-corruption" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Corruption Risk Prevention Plan')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['corruption_risk'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-customer" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Customer Privacy Policy')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['customer_policy'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-policy" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Policy to prevent and deal with situations of conflict of interest')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['policy_to_prevent'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-ethics-conduct" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Code of Ethics and Conduct')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['code_of_ethics_conduct'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-emissions" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Policy for reducing emissions')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['reducing_emissions'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-mechanisms" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Complaint Mechanisms')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['complaint_mechanisms'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-energy-consumption" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Policy to reduce energy consumption')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['energy_consumption'] ? color(2) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2')
                            <label for="checkbox-energy-consumption" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Remuneration Policy')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => $charts['checkboxs']['remuneration_policy'] ? color(2) : color(7)])
                            </div>
                        </div>
                    </div>
                </x-cards.card-dashboard-version1>
            </div>

            {{-- Envviroment --}}
            <div class="mt-10 pagebreak print:mt-20">
                <div class="flex items-center">
                    @include('icons.categories.1')
                    <span class="font-encodesans text-lg font-bold leading-10 text-esg8 pl-3"> {{ __('Environment') }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 print:-mt-10 nonavoid">
                    @if($charts['co2_emissions'])
                        @php
                            $subpoint = json_encode([ [ 'color' => 'bg-[#008131]', 'text' => __('Scope 1') ], [ 'color' => 'bg-[#6AD794]', 'text' => __('Scope 2') ], [ 'color' => 'bg-[#98BDA6]', 'text' => __('Scope 3') ] ]);
                            $text = json_encode([__('GHG Emissions')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                            <x-charts.bar id="co2_emissions" class="m-auto relative !h-full !w-full" />
                        </x-cards.card-dashboard-version1>
                    @endif


                    @if(is_numeric($charts['energy_consumption']))
                        @php $text = json_encode([__('Annual Energy Consumption')]); @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}">
                            <x-charts.icon-number value="{{ $charts['energy_consumption'] }}" unit="KWh">
                                @include('icons.dashboard.gestao-energia', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                            </x-charts.icon-number>
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if($charts['water_consumption'])
                        @php
                            $subpoint = json_encode([ [ 'color' => 'bg-[#006CB7]', 'text' => __('Consumed water (m³)') ]]);
                            $text = json_encode([__('Annual Water Consumption')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" class="relative">
                            <p class="text-esg8 font-medium text-4xl absolute w-full  text-center mt-40 -ml-4">
                                <x-number :value="$charts['water_consumption']" />
                                <span class="text-esg8 font-normal text-base">m3</span>
                            </p>
                            @include('icons.dashboard.liquid', ['width'=>250, 'height'=>250])
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if(is_numeric($charts['waste_generated']))
                        @php
                            $subpoint = json_encode([ ['text' => __('Total waste (Ton)') ]]);
                            $text = json_encode([__('Amount of Waste Generated')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                            <x-charts.icon-number value="{{ $charts['waste_generated'] }}" unit="t">
                                @include('icons.dashboard.residues', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                            </x-charts.icon-number>
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if(is_numeric($charts['recycled_waste']))
                        @php
                            $subpoint = json_encode([ ['text' => __('Total waste (Ton)') ]]);
                            $text = json_encode([__('Amount of Treated or Recycled Waste')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                            <x-charts.icon-number value="{{ $charts['recycled_waste'] }}" unit="t">
                                @include('icons.dashboard.recicle-residue', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                            </x-charts.icon-number>
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if(is_numeric($charts['hazardous_waste']))
                        @php
                            $subpoint = json_encode([ ['text' => __('Total waste (Ton)') ]]);
                            $text = json_encode([__('Amount of hazardous waste and/or Radioactive Waste')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}">
                            <x-charts.icon-number value="{{ $charts['hazardous_waste'] }}" unit="t">
                                @include('icons.dashboard.trash', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                            </x-charts.icon-number>
                        </x-cards.card-dashboard-version1>
                    @endif

                    @php
                        $text = json_encode([__('Strategic Sustainable Development Goals')]);
                    @endphp
                    <x-cards.card-dashboard-version1 text="{{ $text }}" class="!h-auto">
                        <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                            <div class="grid grid-cols-4 md:grid-cols-6 gap-3 mt-10">
                                <div class="w-full">
                                    @include('icons.goals.1', ['class' => 'inline-block', 'color' => in_array(1, $charts['sdgs_top5']) || in_array(376, $charts['sdgs_top5']) ? '#EA1D2D' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.2', ['class' => 'inline-block', 'color' => in_array(2, $charts['sdgs_top5'])|| in_array(377, $charts['sdgs_top5']) ? '#D19F2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.3', ['class' => 'inline-block', 'color' => in_array(3, $charts['sdgs_top5']) || in_array(378, $charts['sdgs_top5']) ? '#2D9A47' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.4', ['class' => 'inline-block', 'color' => in_array(4, $charts['sdgs_top5']) || in_array(379, $charts['sdgs_top5']) ? '#C22033' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.5', ['class' => 'inline-block', 'color' => in_array(5, $charts['sdgs_top5']) || in_array(380, $charts['sdgs_top5']) ? '#EF412A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.6', ['class' => 'inline-block', 'color' => in_array(6, $charts['sdgs_top5']) || in_array(381, $charts['sdgs_top5']) ? '#00ADD8' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.7', ['class' => 'inline-block', 'color' => in_array(7, $charts['sdgs_top5']) || in_array(382, $charts['sdgs_top5']) ? '#FDB714' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.8', ['class' => 'inline-block', 'color' => in_array(8, $charts['sdgs_top5']) || in_array(383, $charts['sdgs_top5']) ? '#8F1838' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.9', ['class' => 'inline-block', 'color' => in_array(9, $charts['sdgs_top5']) || in_array(384, $charts['sdgs_top5']) ? '#F36E24' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.10', ['class' => 'inline-block', 'color' => in_array(10, $charts['sdgs_top5']) || in_array(385, $charts['sdgs_top5']) ? '#E01A83' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.11', ['class' => 'inline-block', 'color' => in_array(11, $charts['sdgs_top5']) || in_array(386, $charts['sdgs_top5']) ? '#F99D25' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.12', ['class' => 'inline-block', 'color' => in_array(12, $charts['sdgs_top5']) || in_array(387, $charts['sdgs_top5']) ? '#CD8B2A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.13', ['class' => 'inline-block', 'color' => in_array(13, $charts['sdgs_top5']) || in_array(388, $charts['sdgs_top5']) ? '#48773C' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.14', ['class' => 'inline-block', 'color' => in_array(14, $charts['sdgs_top5']) || in_array(389, $charts['sdgs_top5']) ? '#007DBB' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.15', ['class' => 'inline-block', 'color' => in_array(15, $charts['sdgs_top5']) || in_array(390, $charts['sdgs_top5']) ? '#40AE49' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.16', ['class' => 'inline-block', 'color' => in_array(16, $charts['sdgs_top5']) || in_array(391, $charts['sdgs_top5']) ? '#00558A' : '#DCDCDC'])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.17', ['class' => 'inline-block', 'color' => in_array(17, $charts['sdgs_top5']) || in_array(392, $charts['sdgs_top5']) ? '#1A3668' : '#DCDCDC'])
                                </div>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1>
                </div>
            </div>

            {{-- Social --}}
            <div class="mt-10 pagebreak print:mt-20">
                <div class="flex items-center">
                    @include('icons.categories.2')
                    <span class="font-encodesans text-lg font-bold leading-10 text-esg8 pl-3"> {{ __('Social') }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 print:-mt-10 nonavoid">

                    @if($charts['gender_equility_employees'])
                        @php
                            $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                            $text = json_encode([__('Gender Equality - Employees')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" type="flex">
                            <x-charts.donut id="gender_equility_employees" class="m-auto !h-[300px] !w-[300px]" />
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if($charts['outsourced_workers'])
                        @php
                            $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                            $text = json_encode([__('Gender Equality - Outsourced workers')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" type="flex">
                            <x-charts.donut id="outsourced_workers" class="m-auto !h-[300px] !w-[300px]" />
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if($charts['gender_equality_executives'])
                        @php
                            $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                            $text = json_encode([__('Gender Equality - Executives')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" type="flex">
                            <x-charts.donut id="gender_equality_executives" class="m-auto !h-[300px] !w-[300px]" />
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if($charts['gender_equality_leadership'])
                        @php
                            $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                            $text = json_encode([__('Gender Equality - Leadership')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" type="flex">
                            <x-charts.donut id="gender_equality_leadership" class="m-auto !h-[300px] !w-[300px]" />
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if(is_numeric($charts['layoffs']))
                        @php
                            $text = json_encode([__('Number of layoffs in last the 12 months')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}">
                            <x-charts.icon-number value="{{ $charts['layoffs'] }}">
                                @include('icons.dashboard.employement', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                            </x-charts.icon-number>
                        </x-cards.card-dashboard-version1>
                    @endif

                    @if(is_numeric($charts['work_days_lost']))
                        @php
                            $text = json_encode([__('Number of work days lost due to injuries, accidents, death or illness')]);
                        @endphp
                        <x-cards.card-dashboard-version1 text="{{ $text }}">
                            <x-charts.icon-number value="{{ $charts['work_days_lost'] }}" unit="dias">
                                @include('icons.dashboard.work-accident', ['class' => 'inline-block ml-2', 'width' => 50, 'height' => 50])
                            </x-charts.icon-number>
                        </x-cards.card-dashboard-version1>
                    @endif
                </div>
            </div>

            {{-- Governance --}}
            <div class="mt-10 print:mt-20">
                <div class="flex items-center">
                    @include('icons.categories.3')
                    <span class="font-encodesans text-lg font-bold leading-10 text-esg8 pl-3"> {{ __('Governance') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 mb-20 nonavoid">
                @php $text = json_encode([__('Annual reporting')]); @endphp
                <x-cards.card-dashboard-version1 text="{{ $text }}">
                    <div class="grid grid-cols-1 md:grid-cols-1 mt-10">
                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2', ['color' => color(3)])
                            <label for="checkbox-website" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Financial report')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => array_key_exists(129, $charts['annual_reporting']) ? color(3) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2', ['color' => color(3)])
                            <label for="checkbox-policy" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Sustainability report')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => array_key_exists(130, $charts['annual_reporting']) ? color(3) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2', ['color' => color(3)])
                            <label for="checkbox-pa" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Activities Report')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => array_key_exists(131, $charts['annual_reporting']) ? color(3) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2', ['color' => color(3)])
                            <label for="checkbox-ethics" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Sales Report')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => array_key_exists(132, $charts['annual_reporting']) ? color(3) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2', ['color' => color(3)])
                            <label for="checkbox-environmental" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Customer Satisfaction Report')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => array_key_exists(133, $charts['annual_reporting']) ? color(3) : color(7)])
                            </div>
                        </div>

                        <div class="flex items-center mr-4 mb-5">
                            @include('icons.notes-v2', ['color' => color(3)])
                            <label for="checkbox-safety" class="ml-4 font-encodesans text-base font-normal text-esg8 w-9/12">{{__('Employee Satisfaction Report')}}</label>
                            <div class="ml-10 grow">
                                @include('icons.checkbox', ['color' => array_key_exists(134, $charts['annual_reporting']) ? color(3) : color(7)])
                            </div>
                        </div>
                    </div>
                </x-cards.card-dashboard-version1>

                @if($charts['gender_high_governance_body'])
                    @php
                        $subpoint = json_encode([ [ 'color' => 'bg-[#21A6E8]', 'text' => __('Men') ], [ 'color' => 'bg-[#C5A8FF]', 'text' => __('Woman') ], [ 'color' => 'bg-[#02C6A1]', 'text' => __('Other') ] ]);
                        $text = json_encode([__('Highest Governance Body by Gender')]);
                    @endphp
                    <x-cards.card-dashboard-version1 text="{{ $text }}" subpoint="{{ $subpoint }}" type="flex">
                        <x-charts.donut id="gender_high_governance_body" class="m-auto !h-[300px] !w-[300px]" />
                    </x-cards.card-dashboard-version1>
                @endif
            </div>
        </div>
    </div>
@endsection
