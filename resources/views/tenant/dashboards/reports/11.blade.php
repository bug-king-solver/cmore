@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' =>'bg-esg4'])
@push('body')
    <style nonce="{{ csp_nonce() }}">
        @media print {
            .pagebreak {
                padding: 0px !important;
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
        }
        @page {
            size: A4 landscape; /* DIN A4 standard, Europe */
            margin: 0 !important;;
            padding: 0 !important;;
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

            actionPlan();

            // Radar chart
            radarChart(
                {!! json_encode($alignment_with_sustainability_principles['labels']) !!},
                {!! json_encode($alignment_with_sustainability_principles['data']) !!},
                'alignment_principles',
                [enviroment_color1, enviroment_color2]
            );

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

                @if ($waste_produced != null)
                    pieCharts(
                        {!! json_encode($waste_produced['labels']) !!},
                        {!! json_encode($waste_produced['data']) !!},
                        'waste_produced',
                        ['#008131', '#99CA3C'],
                        '{{ $waste_produced['unit'] ?? 't' }}'
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
                        null,
                        '',
                        'multi'
                    );
                @endif

                @if ($industry_sector != null)
                    barCharts(
                        {!! json_encode($industry_sector['labels']) !!},
                        {!! json_encode($industry_sector['data']) !!},
                        'suppliers_industry',
                        ["#06A5B4"],
                        '',
                        'single',
                        'y'
                    );
                @endif
        });

        // Common function for bar charts
        function barCharts(labels, data, id, barColor, unit = '', type = "single", view = 'x')
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
                indexAxis: view,
                layout: {
                    padding: {
                        top: 50,
                        right: view == 'y' ? 50 : 0,
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: (view == 'y' ? 'right' : 'top'),
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
                                            <div class="text-right text-sm text-esg8 leading-6"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value})</div>
                                        </div>
                                    `;
                                } else {
                                    html += `
                                    <div class="">
                                        <div class="text-center text-sm text-esg8 leading-5"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value} ${centertext})</div>
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

        // Common function for radar charts
        function radarChart(labels, data, id, color) {
            var options = {
                responsive: false,
                    scales: {
                        r: {
                            pointLabels: {
                                display:false,
                            },
                            ticks: {
                                display:false,
                                maxTicksLimit: 10

                            },
                            format : {
                                callback: function(value, index, ticks) {
                                    return value + '%';
                                }
                            },
                            min: 0,
                            max: 100
                        }
                    },

                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            displayColors: false,
                            titleColor: '#FFF',
                            callbacks: {
                                labelTextColor: function(context) {
                                    return '#FFF';
                                },
                                label: function(context) {
                                    return context.parsed.r + '%';
                                }
                            },
                        },
                        title: {
                            display: false,
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 0,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        }
                    },
                    animation: {
                        duration: 0
                    }
                };

                var config = {
                    type: 'radar',
                    data: {
                        labels: labels,
                        datasets: data
                    },
                    options: options,
                    //plugins: [extra]
                };

            return new Chart(document.getElementById(id), config);
        }

        // action plan
        function actionPlan() {
            var color_code = twConfig.theme.colors.esg7;
            @if ($action_plan)
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
        }
    </script>
@endpush

@section('content')
    <div class="px-4 lg:px-0">
        <div class="mt-10 print:hidden">
            <div class="w-full flex justify-between">
                <div class="">
                    <a href="{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]) }}"
                        class="text-esg5 w-fit text-2xl font-bold flex flex-row gap-2 items-center">
                        @include('icons.back', [
                            'color' => color(5),
                            'width' => '20',
                            'height' => '16',
                        ])
                        {{ __(' Dashboard') }}
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 print:hidden !border-esg5" @click="window.print()">
                        <x-slot name="buttonicon">
                            @includeFirst([tenant()->views . 'icons.download', 'icons.download'], ['class' => 'inline',
                            'color' => '#FFFFFF'  ])
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium">{{ __('Imprimir') }}</span>
                    </x-buttons.btn-icon-text>

                    <x-buttons.btn-icon-text class="!bg-esg4 !text-esg16 border-esg16  print:hidden" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]) }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="normal-case text-sm font-medium">{{ __('Voltar') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            {{-- Page 1 --}}
            <x-report.pagewithimage url="/images/report/startup/page_1.png">
                <div class="flex p-14 h-full flex-col justify-between">
                    <div class="">
                        @include('icons.logos.cmore')
                    </div>

                    <div class="">
                        <p class="text-7xl text-esg5">2022</p>

                        <p class="text-5xl font-extrabold text-esg8 mt-5 w-6/12">{{ __('Sustainability Report') }}</p>

                        <p class="text-2xl text-esg8 mt-5">{{ __('ESG – Environmental, Social and Governance') }}</p>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 2 --}}
            <x-report.pagewithimage url="/images/report/startup/page_2.png" footer="true" header="true">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('Company') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('overview') }}</p>
                </div>
            </x-report.page>

            {{-- Page 3  TODO --}}
            <x-report.page title="{{ __('company overview') }}">
                <div class="grid grid-cols-3 print:grid-cols-3 gap-10 py-5">
                    <div class="">
                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Name') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['company']->name }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-black">{{ __('Business sector') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['business_sector']->name }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-lg font-bold text-black">{{ __('Headquarters') }}</p>
                            <p class="text-base text-esg8 mt-4">
                                @foreach($report['country'] as $row)
                                   {{ $row['name'] }}
                                @endforeach
                            </p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-black">{{ __('NIPC/NIF') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['company']->vat_number }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-black">{{ __('Report period') }}</p>
                            <p class="text-base text-esg8 mt-4">  {{ date('Y-m-d', strtotime($questionnaire->from)) }} {{ __('to') }} {{ date('Y-m-d', strtotime($questionnaire->to)) }}</p>
                        </div>

                        {{-- TODO --}}
                        <div class="mt-4">
                            <p class="text-lg font-bold text-black">{{ __('Total of colaborators') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['colaborators']['value'] ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-span-2 mt-5">
                        <p class="text-lg text-black font-bold">{{ __('Financial data') }}</p>
                        <div class="mt-2 h-4 bg-esg7/20 border-b border-b-esg5"></div>

                        <div class="grid grid-cols-2 gap-5">
                            <div class="">
                                <div class="mt-2">
                                    <p class="text-base text-black ">{{ __('Revenue') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['annual_revenue']['value'] }} <span class="font-normal text-2xl ">{{ $report['annual_revenue']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('Net revenue') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['annual_net_revenue']['value'] }} <span class="font-normal text-2xl ">{{ $report['annual_net_revenue']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('Net profit or loss') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['net_profit_loss']['value'] }} <span class="font-normal text-2xl ">{{ $report['net_profit_loss']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('EBIT') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['ebit']['value'] }} <span class="font-normal text-2xl ">{{ $report['ebit']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('Value of current liabilities') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['liabilities']['value'] }} <span class="font-normal text-2xl ">{{ $report['liabilities']['unit'] ?? '€' }}</span></p>
                                </div>
                            </div>

                            <div class="">
                                <div class="mt-2">
                                    <p class="text-base text-black ">{{ __('Value of total assets') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['total_assets']['value'] }} <span class="font-normal text-2xl ">{{ $report['total_assets']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('Value of total assets') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['total_debt']['value'] }} <span class="font-normal text-2xl ">{{ $report['total_debt']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('Expenses from HRs activities') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['hr_activity']['value'] }} <span class="font-normal text-2xl ">{{ $report['hr_activity']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('Expenses from raw materials’ activities') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['activity_raw_matrial']['value'] }} <span class="font-normal text-2xl ">{{ $report['activity_raw_matrial']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="mt-3">
                                    <p class="text-base text-black ">{{ __('Interest expenses') }}</p>
                                    <p class="text-4xl font-bold text-esg5 mt-2">{{ $report['intrest_expanse']['value'] }} <span class="font-normal text-2xl ">{{ $report['intrest_expanse']['unit'] ?? '€' }}</span></p>
                                </div>

                                <div class="">
                                    <x-report.table.table class="!border-t-esg5">
                                        @foreach($report['listed_company'] as $row)
                                            <x-report.table.tr>
                                                <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                                <x-report.table.td>
                                                    @if ($row['status'])
                                                        @include('icons.checkbox', ['color' =>  color(5)])
                                                    @else
                                                        @include('icons.checkbox-no')
                                                    @endif
                                                </x-report.table.td>
                                            </x-report.table.tr>
                                        @endforeach
                                    </x-report.table.table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 4 --}}
            <x-report.pagewithimage url="/images/report/startup/page_4.png" footer="true" header="true">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('SUSTAINABILITY') }}</p>
                    <p class="text-6xl text-esg8 mt-3 uppercase">{{ __('PRINCIPLES') }}</p>
                </div>
            </x-report.page>

            {{-- Page 5 --}}
            <x-report.page title="{{ __('SUSTAINABILITY PRINCIPLES') }}">
                <div class="py-6 print:py-0">
                    <p class="text-lg font-bold text-black">{{ __('Alignment with sustainability principles') }}</p>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="">
                            <div class="mt-10">
                                <div class="flex items-top gap-5">
                                    <div class="text-base font-medium text-right w-48 text-esg5">
                                        {{ __('Principled business') }}</div>
                                    <div class=""> @include('icons.right-double-arrow', ['color' => color(5)]) </div>
                                    <div class="text-sm text-esg8 w-80">
                                        {{ __('Alignment with Ten Principles on Human Rights, Labour, Environment and Anti-Corruption') }}
                                    </div>
                                </div>

                                <div class="flex items-top gap-5 mt-3">
                                    <div class="text-base font-medium text-right w-48 text-esg5">
                                        {{ __('Strengthening society') }}</div>
                                    <div class=""> @include('icons.right-double-arrow', ['color' => color(5)]) </div>
                                    <div class="text-sm text-esg8 w-80">
                                        {{ __('Taking action and collaborating with other to advance global challenges') }}
                                    </div>
                                </div>

                                <div class="flex items-top gap-5 mt-3">
                                    <div class="text-base font-medium text-right w-48 text-esg5">
                                        {{ __('Leadership commitment') }}</div>
                                    <div class=""> @include('icons.right-double-arrow', ['color' => color(5)]) </div>
                                    <div class="text-sm text-esg8 w-80">
                                        {{ __('Assure long-term change through the company`s leadership') }}</div>
                                </div>

                                <div class="flex items-top gap-5 mt-3">
                                    <div class="text-base font-medium text-right w-48 text-esg5">
                                        {{ __('Reporting progress') }}</div>
                                    <div class=""> @include('icons.right-double-arrow', ['color' => color(5)]) </div>
                                    <div class="text-sm text-esg8 w-80">
                                        {{ __('Commit at the highest level and assure transparency and credibility') }}
                                    </div>
                                </div>

                                <div class="flex items-top gap-5 mt-3">
                                    <div class="text-base font-medium text-right w-48 text-esg5">{{ __('Local action') }}
                                    </div>
                                    <div class=""> @include('icons.right-double-arrow', ['color' => color(5)]) </div>
                                    <div class="text-sm text-esg8 w-80">
                                        {{ __('Viewing sustainability through local perspective') }}</div>
                                </div>
                            </div>
                            <div class="mt-12">
                                <img src="/images/report/startup/page_5.png">
                            </div>
                        </div>

                        <div class="">
                            {{-- <x-charts.chartjs id="alignment_principles" class="m-auto relative !h-full !w-full" /> --}}
                            <div class="col-span-3 relative">
                                @foreach($alignment_with_sustainability_principles['labels'] as $keyLabels => $labeles)
                                    <div class="absolute flex flex-col justify-center items-center startup5-{{$loop->index}}">
                                        <div class="text-center gap-5">
                                            <div class="text-xs text-esg8 font-medium mb-2 text-center">{{$labeles}}</div>
                                        </div>
                                        <div class="w-40">
                                            <div class="flex flex-wrap items-center justify-center gap-1">
                                                @foreach($alignment_with_sustainability_principles['data'] as $keyLabels2 => $datasets)
                                                    <span class="inline-flex items-center rounded text-esg2 px-2.5 py-1 text-xs font-semibold" style="background-color: {{$datasets['backgroundLightColor']}}; color: {{$datasets['mainColor']}}">{{ $datasets['data'][$keyLabels] }}%</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="m-auto my-24">
                                    <x-charts.radar id="alignment_principles" class="m-auto" width="200" height="370" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 6 --}}
            <x-report.pagewithimage url="/images/report/startup/page_6.png" footer="true" header="true" footerborder="border-t-esg2">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg2">{{ __('environment') }}</p>
                    <p class="text-6xl text-esg8 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 7 --}}
            <x-report.page title="{{ __('Climate IMPACT') }}" footerborder="border-t-esg2">
                <div class="py-6">
                    <div class="grid grid-cols-3 gap-5">
                        <div class="">
                            <div class="">
                                <p class="text-lg font-bold text-black">{{ __('Carbon intensity') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-3xl font-bold text-esg2 mt-2">{{ $carbon_intensity['value'] }} <span class="text-2xl font-bold text-esg2"> {{ $carbon_intensity['unit'] ?? 'm3 €' }}</label>
                            </div>

                            <div class="mt-8">
                                <p class="text-lg font-bold text-black">{{ __('Energy intensity') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-3xl font-bold text-esg2 mt-2">{{ $energy_intensity['value'] }} <span class="text-2xl font-bold text-esg2">{{ $energy_intensity['unit'] ?? 'MWh / €' }}</span></label>
                            </div>
                        </div>

                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Energy consumption') }}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                type="flex" class="!h-auto !shadow-none" contentplacement="justify-center">
                                @if ($energy_consumption != null)
                                    <x-charts.donut id="energy_consumption" class="m-auto !h-[180px] !w-[180px]"
                                        legendes="true" grid="1"/>
                                @else
                                    <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                @endif
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="mt-12">
                            <img src="/images/report/startup/page_7.png">
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 8 --}}
            <x-report.page title="{{ __('Climate IMPACT') }}" footerborder="border-t-esg2">
                <div class="py-6">
                    <div class="grid grid-cols-2">
                        <div class="">
                            <img src="/images/report/startup/page_8.png">
                        </div>

                        <div class="">
                            <div class="">
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach($report['gas'] as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(2)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('GHG Emissions') }}</p>
                                <x-cards.card-dashboard-version1-withshadow
                                    class="!h-auto !shadow-none" titleclass="!uppercase !font-normal !text-esg16 !text-base"
                                    contentplacement="none">
                                    @if ($ghg != null)
                                        <div class="pt-10">
                                            <x-charts.bar id="ghg_emissin" class="m-auto relative !h-full !w-full" />
                                        </div>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 9 --}}
            <x-report.page title="{{ __('Climate IMPACT') }}" footerborder="border-t-esg2" bgimage="/images/report/startup/page_9.png">
                <div class="py-5">
                    <div class="grid grid-cols-2 gap-10 mt-4 pb-52">
                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Use of water resources') }}</p>

                            <x-report.table.table class="!border-t-esg2">
                                @foreach($report['water_stress'] as $row)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(2)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Water intensity') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $water_intensity['value'] }} <span class="text-2xl font-bold text-esg2"> {{ $water_intensity['unit'] ?? 'm3 / €' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Water consumed') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $water_consumed['value'] }} <span class="text-2xl font-bold text-esg2"> {{ $water_consumed['unit'] ?? 'm3' }}</span></label>
                            </div>

                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 10 --}}
            <x-report.page title="{{ __('Biodiversity and ecosystems') }}" footerborder="border-t-esg2">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Impacts on biodiversity and ecosystems') }}</p>
                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach($report['impacts_biodiversity_ecosystems'] as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(2)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="">
                            <img src="/images/report/startup/page_10.png">
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 11 --}}
            <x-report.pagewithrightimage title="{{ __('RESOURCES USE IMPACT') }}" footerborder="border-t-esg2" bgimage="/images/report/startup/page_11.png">
                <div class="py-5">
                    <div class="pb-60">
                        <p class="text-lg font-bold text-black mb-4">{{ __('Waste produced') }}</p>

                        <div class="">
                            <x-cards.card-dashboard-version1-withshadow
                                type="flex" class="!h-auto !shadow-none" contentplacement="none">
                                @if ($waste_produced != null)
                                    <x-charts.donut id="waste_produced" class="m-auto !h-[180px] !w-[180px]" legendes="true" grid="1"/>
                                @else
                                    <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                @endif
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>
            </x-report.pagewithrightimage>

            {{-- Page 12 --}}
            <x-report.pagewithimage url="/images/report/startup/page_12.png" footer="true" header="true" footerborder="border-t-esg1">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg1">{{ __('Social') }}</p>
                    <p class="text-6xl text-esg8 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 13 --}}
            <x-report.page title="{{ __('Characterization of workers') }}" footerborder="border-t-esg1">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="">
                            <div>
                                <p class="text-lg font-bold text-black">{{ __('Distribution by gender') }}</p>
                                <x-cards.card-dashboard-version1-withshadow class="!h-min !shadow-none">
                                    @if ($gender_distribution != null)
                                        <x-charts.bar id="gender_distribution" class="m-auto relative !h-full !w-full" />

                                        <div class="flex justify-center gap-5">
                                            @php
                                                $subpoint = json_encode([
                                                        [ 'color' => 'bg-[#FF9900]', 'text' => __('Man') ],
                                                        [ 'color' => 'bg-[#FBB040]', 'text' => __('Woman') ],
                                                        [ 'color' => 'bg-[#FFDDAB]', 'text' => __('Other') ]
                                                    ]);
                                            @endphp
                                            @foreach(json_decode(htmlspecialchars_decode($subpoint), true) as $value)
                                                <div class="flex items-center">
                                                    @if (isset($value['color']))
                                                        <div class="">
                                                            <span class="w-3 h-3 relative rounded-full inline-block {{ $value['color'] ?? '#FFFFFF' }}"></span>
                                                        </div>
                                                    @endif
                                                    <div class="{{ (isset($value['color'])) ? 'p-2' : '' }} inline-block text-sm text-esg8/70">
                                                        @php $data = explode(":", $value['text']) @endphp
                                                        @if (isset($data[1]))
                                                            {{ $data[0] }}: <b>{{ $data[1] }}</b>
                                                        @else
                                                            {{ $value['text'] ?? '' }}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($management_positions as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(1)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach

                                    @foreach($local_community as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(1)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="">
                            <img src="/images/report/startup/page_13.png">
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 14 --}}
            <x-report.pagewithrightimage title="{{ __('Workers satisfaction and CONDITIONS') }}" footerborder="border-t-esg1" bgimage="/images/report/startup/page_14.png">
                <div class="py-6 print:py-0">
                    <div class="pb-44">
                        <div class="">
                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($perfomance as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(1)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Pay Gap') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $pay_gap['value'] }} <span class="text-2xl font-bold text-esg1">{{ $pay_gap['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Turnover') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2"> {{ $turnover['value'] }} <span class="text-2xl font-bold text-esg1">{{ $turnover['unit'] ?? '%' }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.pagewithrightimage>

            {{-- Page 15 --}}
            <x-report.pagewithrightimage title="{{ __('safety and health') }}" footerborder="border-t-esg1" bgimage="/images/report/startup/page_15.png">
                <div class="py-5">
                    <div class="pb-60">
                        <div class="">
                            <x-report.table.table class="!border-t-esg1">
                                @foreach($osh as $row)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(1)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>
                        </div>
                    </div>
                </div>
            </x-report.pagewithrightimage>

            {{-- Page 16 --}}
            <x-report.pagewithrightimage title="{{ __('safety and health') }}" footerborder="border-t-esg1" bgimage="/images/report/startup/page_16.png">
                <div class="py-5">
                    <div class="pb-20">
                        <div class="col-span-2">
                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($report['traning_capcity'] as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(1)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Training and capacity development') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $capasity_development['value'] }} <span class="text-2xl font-bold text-esg1">{{ __($capasity_development['unit'] ?? 'hours') }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Ethics and conduct') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2"> {{ $ethic_conduct['value'] }} <span class="text-2xl font-bold text-esg1">{{ __($ethic_conduct['unit'] ?? 'hours') }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Social issues') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2"> {{ $social_issues['value'] }} <span class="text-2xl font-bold text-esg1">{{ __($social_issues['unit'] ?? 'hours') }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.pagewithrightimage>

            {{-- Page 17 --}}
            <x-report.pagewithleftimage title="{{ __('SOCIAL ACTIONS') }}" footerborder="border-t-esg1" bgimage="/images/report/startup/page_17.png">
                <div class="py-5">
                    <div class="pb-60">
                        <div class="col-span-2">
                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($report['social_action'] as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(1)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Amount allocated to corporate social responsibility activities') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $social_activity['value'] }} <span class="text-2xl font-bold text-esg1">{{ $social_activity['unit'] ?? '€' }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.pagewithleftimage>

            {{-- Page 18 --}}
            <x-report.pagewithimage url="/images/report/startup/page_18.png" footer="true" header="true" footerborder="border-t-esg3">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg3">{{ __('governance') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 19 --}}
            <x-report.page title="{{ __('STRUCTURE') }}" footerborder="border-t-esg3">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="">
                            <div>
                                <p class="text-lg font-bold text-black">{{ __('Mission and Values') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($mission as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(3)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($goal_issue as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(3)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Purpose') }}</p>
                                <p class="text-sm text-esg8 mt-4">{{ $purpose['value'] }}</p>
                            </div>
                        </div>

                        <div class="">
                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Highest governance body') }}</p>
                                <div class="mt-4">
                                    @foreach ($member as $row)
                                        @if ($row['status'])
                                            <div class="w-full mt-5 pb-3">
                                                <div class="">
                                                    <p class="text-2xl font-bold text-esg3">
                                                        {{ $row['label'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['corporate'] as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(3)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Gender distribution in top management') }}</p>

                                <div class="mt-4">
                                    <x-cards.card-dashboard-version1-withshadow
                                        type="flex" contentplacement="none" class="!h-full !shadow-none">
                                        @if ($high_governance_body != null)
                                            <x-charts.donut id="gender_high_governance_body" class="!h-[180px] !w-[180px]" legendes="true" />
                                        @else
                                            <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                        @endif
                                    </x-cards.card-dashboard-version1-withshadow>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 20 --}}
            <x-report.page title="{{ __('PRACTICES') }}" footerborder="border-t-esg3">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Governance body') }}</p>
                            <x-report.table.table class="!border-t-esg3">
                                @foreach($governance_body as $row)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>
                        </div>

                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Ethics and conduct') }}</p>
                            <x-report.table.table class="!border-t-esg3">
                                @foreach($governance_ethic as $row)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 21 --}}
            <x-report.page title="{{ __('PRACTICES') }}" footerborder="border-t-esg3">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Policies') }}</p>
                            <x-report.table.table class="!border-t-esg3">
                                @foreach($policy as $row)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>
                        </div>

                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Risks assessment') }}</p>
                            <x-report.table.table class="!border-t-esg3">
                                @foreach($risk as $row)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($row['status'])
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 22 --}}
            <x-report.page title="{{ __('RELATIONSHIP WITH SUPPLIERS') }}" footerborder="border-t-esg3">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Number of suppliers by industry sector') }}</p>
                            <div>
                                <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none">
                                    @if ($industry_sector != null)
                                        <x-charts.bar id="suppliers_industry" class="m-auto relative !h-full !w-full" />
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Total number of suppliers') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $number_suppliers_industry_sector['value'] }} <span class="text-2xl font-bold text-esg3">{{ $number_suppliers_industry_sector['unit'] ?? '%' }}</span></label>
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <p class="text-lg font-bold text-black">{{ __('Suppliers') }}</p>

                                <div class="mt-4">
                                    <p class="text-base font-normal text-black">{{ __('Assessed for environmental impacts') }}</p>
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $enviroment_impact['value'] }} <span class="text-2xl font-bold text-esg3">{{ $enviroment_impact['unit'] ?? '%' }}</span></label>
                                </div>

                                <div class="mt-4">
                                    <p class="text-base font-normal text-black">{{ __('Assessed for social impacts') }}</p>
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $social_impact['value'] }} <span class="text-2xl font-bold text-esg3">{{ $social_impact['unit'] ?? '%' }}</span></label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($supplier as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(3)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="">
                                <div class="mt-4">
                                    <p class="text-base font-normal text-black">{{ __('Expenses arising from activities associated with suppliers') }}</p>
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['expenses_suppliers']['value'] }} <span class="text-2xl font-bold text-esg3">{{ $report['expenses_suppliers']['unit'] ?? '%' }}</span></label>
                                </div>

                                <div class="mt-4">
                                    <p class="text-base font-normal text-black">{{ __('Payment to local suppliers / total amount paid to all suppliers') }}</p>
                                    <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['payment_suppliers']['value'] }} <span class="text-2xl font-bold text-esg3">{{ $report['payment_suppliers']['unit'] ?? '%' }}</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 23 --}}
            <x-report.page title="{{ __('RELATIONSHIP WITH SUPPLIERS') }}" footerborder="border-t-esg3">
                <div class="py-6">
                    <div class="grid grid-cols-3 gap-10">
                        <div class="">
                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Annual revenue') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2"> {{ $annual_revenue['value'] }} <span class="text-2xl font-normal text-esg3">{{ $annual_revenue['unit'] ?? '€' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Annual net revenue') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $annual_net_revenue['value'] }} <span class="text-2xl font-normal text-esg3">{{ $annual_net_revenue['unit'] ?? '€' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Earnings before interest and taxes') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['ebit']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['ebit']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Liabilities') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['liabilities']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['liabilities']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Total assets') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['total_assets']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['total_assets']['unit'] ?? '%' }}</span></label>
                            </div>

                        </div>

                        <div class="">
                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Capital expenditure') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['capital_expenditure']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['capital_expenditure']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Number of available shares') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['share']['value'] }} <span class="text-2xl font-normal text-esg3">{{ __($report['share']['value'] ?? 'shares') }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Amount interest expenses') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['intrest_expanse']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['intrest_expanse']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Net profit or loss') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['net_profit_loss']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['net_profit_loss']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Value of equity') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['equity']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['equity']['unit'] ?? '%' }}</span></label>
                            </div>
                        </div>

                        <div class="">
                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Expenses from HRs activities') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['hr_activity']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['hr_activity']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Expenses from raw materials’ activities') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['activity_raw_matrial']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['activity_raw_matrial']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Total value of debt') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['total_debt']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['total_debt']['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Net debt') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['net_debt']['value'] }} <span class="text-2xl font-normal text-esg3">{{ $report['net_debt']['unit'] ?? '%' }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 24 --}}
            <x-report.page title="{{ __('FINANCIAL INFORMATION') }}" footerborder="border-t-esg3">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-10">
                        <div class="">
                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Closing price per share') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2"> {{ $report['closing_price_share']['value'] }} <span class="text-2xl font-bold text-esg3">{{ $report['closing_price_share']['unit'] ?? '€' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base font-normal text-black">{{ __('Closing price per share') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2">{{ $report['avg_cost_capital']['value'] }} <span class="text-2xl font-bold text-esg3">{{ $report['avg_cost_capital']['unit'] ?? '€' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <img src="/images/report/startup/page_24.png">
                            </div>
                        </div>

                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Periodic reports') }}</p>
                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($pridict_report as $row)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $row['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($row['status'])
                                                    @include('icons.checkbox', ['color' =>  color(3)])
                                                @else
                                                    @include('icons.checkbox-no')
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-lg font-bold text-black">{{ __('Additional information provided by the company') }}</p>
                                <p class="text-sm text-esg8 mt-3">{{ $report['aditional_info'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 25 --}}
            <x-report.pagewithimage url="/images/report/startup/page_25.png" footer="true" header="true" footerborder="border-t-esg5">
                <div class="">
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('statement of ') }}</p>
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('responsability') }}</p>
                </div>
            </x-report.page>

            {{-- Page 26 TODO --}}
            <x-report.pagewithrightimage title="{{ __('social') }}" footerborder="border-t-esg5" bgimage="/images/report/startup/page_26.png">
                <div class="py-5">
                    <div class="pb-44">
                        <div class="col-span-2">
                            <div class="mt-4">
                                {{-- Page 26 TODO :: ? --}}
                                <p class="text-base text-esg8">{{ __('Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam elementum sollicitudin dui, et semper eros faucibus in. Morbi arcu ex, facilisis ut dolor eu, dignissim viverra elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec dapibus, libero vitae tincidunt luctus, ex erat pulvinar arcu, in venenatis magna massa sed neque. Aliquam ac nunc gravida, maximus elit id,') }}</p>
                            </div>

                            <div class="mt-8">
                                <p class="text-base font-bold text-esg8">{{ auth()->user()->name }}</p>
                                <p class="text-base text-esg8">{{ __('Submitted on') }} {{ date('Y-m-d', strtotime($questionnaire->submitted_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.pagewithrightimage>

            {{-- Page 27 --}}
            <x-report.page footerborder="border-t-esg3" lastpage="true" noheader="true" nofooter="true">
                <div >
                    <div class="h-80 grid place-content-end justify-center pb-10">
                        <div class="flex items-center gap-10">
                            @include('icons.logos.cmore-v2')
                            @include('icons.logos.cmore-v1')
                        </div>
                    </div>

                    <div class="h-80 bg-esg5 w-full">
                    </div>
                </div>
             </x-report.page>
        </div>
    </div>
@endsection
