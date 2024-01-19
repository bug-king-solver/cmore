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
            size: A4; /* DIN A4 standard, Europe */
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
            <x-report.vertical.home url="/images/report/startup/vertical/1.png">
                <div class="flex p-14 h-full flex-col justify-between m-auto border-b-[20px] border-b-esg5 w-full">
                    <div class="flex justify-center">
                        @include('icons.logos.cmore')
                    </div>

                    <div class="mt-10">
                        <p class="text-7xl text-esg5">2022</p>

                        <p class="text-4xl font-extrabold text-esg8 mt-5">{{ __('Sustainability Report') }}</p>

                        <p class="text-2xl text-esg8 mt-10">{{ __('ESG – Environmental, Social and Governance') }}</p>
                    </div>
                </div>
            </x-report.vertical.home>

            {{-- Page 2 --}}
            <x-report.vertical.pagewithimage url="/images/report/startup/vertical/2.png"  header="true">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5 text-center">{{ __('Company') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('overview') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 3 --}}
            <x-report.vertical.page title="{{ __('company overview') }}">
                <div class="grid grid-cols-1 gap-5">
                    <div class="">
                        <div class="">
                            <p class="text-lg font-bold text-black">{{ __('Name') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $report['company']->name }}</p>
                        </div>

                        <div class="mt-2">
                            <p class="text-lg font-bold text-black">{{ __('Business sector') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $report['business_sector']->name }}</p>
                        </div>
                        <div class="mt-2">
                            <p class="text-lg font-bold text-black">{{ __('Headquarters') }}</p>
                            <p class="text-base text-esg8 mt-2">
                                @foreach($report['country'] as $row)
                                   {{ $row['name'] }}
                                @endforeach
                            </p>
                        </div>

                        <div class="mt-2">
                            <p class="text-lg font-bold text-black">{{ __('NIPC/NIF') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $report['company']->vat_number }}</p>
                        </div>

                        <div class="mt-2">
                            <p class="text-lg font-bold text-black">{{ __('Report period') }}</p>
                            <p class="text-base text-esg8 mt-2">  {{ date('Y-m-d', strtotime($questionnaire->from)) }} {{ __('to') }} {{ date('Y-m-d', strtotime($questionnaire->to)) }}</p>
                        </div>

                        <div class="mt-2">
                            <p class="text-lg font-bold text-black">{{ __('Total of colaborators') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $report['colaborators']['value'] ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <p class="text-lg text-black font-bold">{{ __('Financial data') }}</p>
                        <div class="mt-2 h-4 bg-esg7/20 border-b border-b-esg5"></div>

                        <div class="grid grid-cols-1 gap-2">
                            <div class="mt-2 flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Revenue') }}</p>
                                <p class="text-base text-black ">{{ $report['annual_revenue']['value'] }} <span class="">{{ $report['annual_revenue']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Net revenue') }}</p>
                                <p class="text-base text-black ">{{ $report['annual_net_revenue']['value'] }} <span class="">{{ $report['annual_net_revenue']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Net profit or loss') }}</p>
                                <p class="text-base text-black ">{{ $report['net_profit_loss']['value'] }} <span class="">{{ $report['net_profit_loss']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('EBIT') }}</p>
                                <p class="text-base text-black ">{{ $report['ebit']['value'] }} <span class="">{{ $report['ebit']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Value of current liabilities') }}</p>
                                <p class="text-base text-black ">{{ $report['liabilities']['value'] }} <span class="">{{ $report['liabilities']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Value of total assets') }}</p>
                                <p class="text-base text-black ">{{ $report['total_assets']['value'] }} <span class="">{{ $report['total_assets']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Value of total assets') }}</p>
                                <p class="text-base text-black ">{{ $report['total_debt']['value'] }} <span class="">{{ $report['total_debt']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Expenses from HRs activities') }}</p>
                                <p class="text-base text-black ">{{ $report['hr_activity']['value'] }} <span class="">{{ $report['hr_activity']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Expenses from raw materials’ activities') }}</p>
                                <p class="text-base text-black ">{{ $report['activity_raw_matrial']['value'] }} <span class="">{{ $report['activity_raw_matrial']['unit'] ?? '€' }}</span></p>
                            </div>

                            <div class="flex justify-between border-b border-b-esg7/40 pb-2">
                                <p class="text-base text-black ">{{ __('Interest expenses') }}</p>
                                <p class="text-base text-black ">{{ $report['intrest_expanse']['value'] }} <span class="">{{ $report['intrest_expanse']['unit'] ?? '€' }}</span></p>
                            </div>
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
            </x-report.vertical.page>

            {{-- Page 4 --}}
            <x-report.vertical.pagewithimage url="/images/report/startup/vertical/3.png"  header="true">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5 text-center">{{ __('SUSTAINABILITY') }}</p>
                    <p class="text-6xl text-esg8 mt-3 uppercase text-center">{{ __('PRINCIPLES') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 5 --}}
            <x-report.vertical.page title="{{ __('SUSTAINABILITY PRINCIPLES') }}">
                <div class="py-6 ">
                    <p class="text-base font-medium text-black uppercase">{{ __('Alignment with sustainability principles') }}</p>
                    <div class="grid grid-cols-1 gap-5">
                        <div class="w-5/12 m-auto print:w-8/12">
                            <div class="relative">
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

                        <div class="flex justify-center">
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
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 6 --}}
            <x-report.vertical.pagewithimage url="/images/report/startup/vertical/4.png" header="true" border="border-b-esg2">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg2 text-center">{{ __('environment') }}</p>
                    <p class="text-6xl text-esg8 mt-3 uppercase text-center">{{ __('performance') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 7 --}}
            <x-report.vertical.page title="{{ __('ENVIRONMENT') }}">
                <div class="grid grid-cols-1 gap-5">
                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Carbon intensity')]) }}"
                            class="!h-min !shadow-none border border-esg7/40" contentplacement="none">
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
                            class="!h-min !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$energy_intensity['value']" :unit="$energy_intensity['unit'] ?? 'MWh / €'" :isNumber=true />
                                </div>
                                <div class="-mt-7">
                                    @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="w-6/12 print:!w-8/12">
                            <x-cards.card-dashboard-version1-withshadow  text="{{ json_encode([__('Energy consumption')]) }}"
                                type="none" class="!h-min !shadow-none border border-esg7/40" contentplacement="justify-center">
                                @if ($energy_consumption != null)
                                    <x-charts.donut id="energy_consumption" class="m-auto !h-[180px] !w-[180px] print:!h-[100px] print:!w-[100px]"
                                        legendes="true" />
                                @else
                                    <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                @endif
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

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

                    <div class="grid grid-cols-2 gap-5">
                        @php
                            $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Scope 1')], ['color' => 'bg-[#6AD794]', 'text' => __('Scope 2')], ['color' => 'bg-[#98BDA6]', 'text' => __('Scope 3')]]);
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG emission by category')]) }}" subpoint="{{ $subpoint }}"
                            class="!h-min !shadow-none border border-esg7/40" titleclass="!uppercase !font-normal !text-esg16 !text-base"
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
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 8 --}}
            <x-report.vertical.page title="{{ __('ENVIRONMENT') }}">
                <div class="py-5">
                    <div class="grid grid-cols-1 gap-5">
                        <p class="text-base font-medium text-black uppercase">{{ __('WATER RESOURCES IMPACT') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water intensity')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$water_consumed['value']" :unit="$water_consumed['unit'] ?? 'm3 / €'" :isNumber=true />
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.enviroment.water', ['color' => color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            <p class="text-base font-medium text-black uppercase">{{ __('Use of water resources') }}</p>
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
                        </div>

                        <div class="">
                            <p class="text-base font-medium text-black uppercase">{{ __('Impacts on biodiversity and ecosystems') }}</p>
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

                        <div class="w-6/12 print:w-8/12">
                            <x-cards.card-dashboard-version1-withshadow text="{!! __('Waste produced') !!}"
                                type="flex" class="!h-auto  !shadow-none border border-esg7/40" contentplacement="none">
                                @if ($waste_produced != null)
                                    <x-charts.donut id="waste_produced" class="m-auto !h-[180px] !w-[180px] print:!h-[100px] print:!w-[100px]" legendes="true"/>
                                @else
                                    <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                @endif
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 9 --}}
            <x-report.vertical.pagewithimage url="/images/report/startup/vertical/5.png"  header="true" border="border-b-esg1">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg1 text-center">{{ __('Social') }}</p>
                    <p class="text-6xl text-esg8 mt-3 uppercase text-center">{{ __('performance') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 10 --}}
            <x-report.vertical.page title="{{ __('Social') }}" footerborder="border-t-esg1">
                <div class="py-6">
                    <div class="grid grid-cols-1 gap-5">
                        <p class="text-base font-medium text-black uppercase">{{ __('CHARACTERIZATION OF WORKERS') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            @php
                                $subpoint = json_encode([['color' => 'bg-[#F90]', 'text' => __('Men')], ['color' => 'bg-[#FBB040]', 'text' => __('Woman')], ['color' => 'bg-[#FFDDAB]', 'text' => __('Other')]]);
                            @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender distribution')]) }}"
                                subpoint="{{ $subpoint }}" class="!h-auto !shadow-none border border-esg7/40">
                                @if ($gender_distribution != null)
                                    <x-charts.bar id="gender_distribution" class="m-auto relative !h-full !w-full" />
                                @else
                                    <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                @endif
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
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

                        <div class="">
                            <p class="text-base font-medium text-black uppercase">{{ __('WORKERS SATISFACTION AND CONDITIONS') }}</p>
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

                        <div class="grid grid-cols-2 gap-5">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Pay gap gender')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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

                        <div class="">
                            <p class="text-base font-medium text-black uppercase">{{ __('SAFETY AND HEALTH') }}</p>
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
            </x-report.vertical.page>

            {{-- Page 11 --}}
            <x-report.vertical.page title="{{ __('Social') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <div>
                        <p class="text-base font-medium text-black uppercase">{{ __('TRAINING FOR WORKERS') }}</p>
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

                    <div class="grid grid-cols-2 gap-5 ">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Training and CAPACITY DEVELOPMENT')]) }}" class="!h-auto !shadow-none border border-esg7/40"
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
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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

                    <div>
                        <p class="text-base font-medium text-black uppercase">{{ __('SOCIAL ACTIONS') }}</p>
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

                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('CORPORATE SOCIAL RESPONSIBILITY ACTIVITIES')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40"
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
            </x-report.vertical.page>

            {{-- Page 12 --}}
            <x-report.vertical.pagewithimage url="/images/report/startup/vertical/6.png"  header="true" border="border-b-esg3">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg3 text-center">{{ __('governance') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('performance') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 13 --}}
            <x-report.vertical.page title="{{ __('GOVERNANCE') }}">
                <div class="py-6 grid grid-cols-1 gap-5">
                    <div>
                        <p class="text-base font-medium text-black uppercase">{{ __('STRUCTURE') }}</p>

                        <p class="text-xs font-medium text-esg16 uppercase mt-5">{{ __('mission and values') }}</p>
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

                    <div>
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

                    <div>
                        <p class="text-xs font-medium text-esg16 uppercase">{{ __('PURPOSE') }}</p>

                        <p class="text-sm text-esg8 mt-4">{{ $purpose['value'] }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-esg16 uppercase">{{ __('Highest governance body of the organisation constituted and structured') }}</p>
                        <div>
                            @foreach ($member as $row)
                                @if ($row['status'])
                                    <div class="w-full">
                                        <div class="">
                                            <p class="text-xs font-medium my-5">
                                                {{ $row['label'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div>
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

                    <div class="w-6/12 print:!w-8/12">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Gender distribution of members og the highest governance body')]) }}"
                            type="flex" contentplacement="none" class="!h-auto !shadow-none border border-esg7/40">
                            @if ($high_governance_body != null)
                                <x-charts.donut id="gender_high_governance_body" class="!h-[180px] !w-[180px] print:!h-[100px] print:!w-[100px]"
                                    legendes="true" />
                            @else
                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                            @endif
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 14 --}}
            <x-report.vertical.page title="{{ __('GOVERNANCE') }}">
                <div class="py-6">
                    <div class="grid grid-cols-1 gap-5">
                        <div class="">
                            <p class="text-base font-medium text-black uppercase">{{ __('Governance body') }}</p>
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
                            <p class="text-base font-medium text-black uppercase">{{ __('Ethics and conduct') }}</p>
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
            </x-report.vertical.page>

            {{-- Page 15 --}}
            <x-report.vertical.page title="{{ __('GOVERNANCE') }}">
                <div class="py-6">
                    <div class="grid grid-cols-1 gap-5">
                        <div class="">
                            <p class="text-base font-medium text-black uppercase">{{ __('Policies') }}</p>
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
                            <p class="text-base font-medium text-black uppercase">{{ __('Risks assessment') }}</p>
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
            </x-report.vertical.page>

            {{-- Page 16 --}}
            <x-report.vertical.page title="{{ __('GOVERNANCE') }}">
                <div class="py-6">
                    <div class="grid grid-cols-1 gap-5">
                        <div class="grid grid-cols-2 gap-5">
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Suppliers by INDUSTRY SECTOR')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40">

                                @if ($industry_sector != null)
                                    <x-charts.bar id="suppliers_industry" class="m-auto relative !h-full !w-full" />
                                @else
                                    <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                @endif
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Number of suppliers by industry sector')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('SUPPLIERS ASSESSED FOR ENVIRONMENT IMPACTS')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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
                                text="{{ json_encode([__('SUPPLIERS ASSESSED FOR SOCIAL IMPACTS')]) }}" class="!h-auto !shadow-none border border-esg7/40"
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
                        </div>

                        <div>
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

                        <div class="grid grid-cols-2 gap-5">
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Expenses arising from activities associated with suppliers')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$report['expenses_suppliers']['value']" :unit="$report['expenses_suppliers']['unit'] ?? '%'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.money-hand', [
                                            'color' => color(3),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Payment to local suppliers / total amount paid to all suppliers')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$report['payment_suppliers']['value']" :unit="$report['payment_suppliers']['unit'] ?? '%'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.money-hand', [
                                            'color' => color(3),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 17 --}}
            <x-report.vertical.page title="{{ __('GOVERNANCE') }}">
                <div class="py-6">
                    <div class="grid grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Annual revenue')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value=" $annual_revenue['value']" :unit=" $annual_revenue['unit'] ?? '€'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Annual net revenue')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$annual_net_revenue['value']" :unit="$annual_net_revenue['unit'] ?? '€'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Earnings before interest and taxes')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['ebit']['value']" :unit="$report['ebit']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Liabilities')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['liabilities']['value']" :unit="$report['liabilities']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Total assets')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['total_assets']['value']" :unit="$report['total_assets']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Capital expenditure')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['capital_expenditure']['value']" :unit="$report['capital_expenditure']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Number of available shares')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['share']['value']" :unit="$report['share']['unit'] ?? __('shares')"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.share', [
                                        'color' => color(3),
                                        'width' => '49',
                                        'height' => '47'
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Amount interest expenses')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['intrest_expanse']['value']" :unit="$report['intrest_expanse']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Net profit or loss')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['net_profit_loss']['value']" :unit="$report['net_profit_loss']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Value of equity')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['equity']['value']" :unit="$report['equity']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Expenses from HRs activities')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['hr_activity']['value']" :unit="$report['hr_activity']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Expenses from raw materials’ activities')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['activity_raw_matrial']['value']" :unit="$report['activity_raw_matrial']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Total value of debt')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['total_debt']['value']" :unit="$report['total_debt']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Net debt')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$report['net_debt']['value']" :unit="$report['net_debt']['unit'] ?? '%'"/>
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.11.money-hand', [
                                        'color' => color(3),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 18 --}}
            <x-report.vertical.page title="{{ __('GOVERNANCE') }}">
                <div class="py-6">
                    <div class="grid grid-cols-1 gap-5">
                        <p class="text-base font-medium text-black uppercase">{{ __('FINANCIAL INFORMATION') }}</p>
                        <div class="grid grid-cols-2 gap-5">
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Closing price per share')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$report['closing_price_share']['value']" :unit="$report['closing_price_share']['unit'] ?? '€'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.money-hand', [
                                            'color' => color(3),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('VALUE OF WEIGHTED AVERAGE COST OF CAPITAL')]) }}"
                                class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$report['avg_cost_capital']['value']" :unit="$report['avg_cost_capital']['unit'] ?? '€'"/>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.11.money-hand', [
                                            'color' => color(3),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            <p class="text-base font-medium text-black uppercase">{{ __('Periodic reports') }}</p>
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

                        <div>
                            <p class="text-base font-medium text-black uppercase">{{ __('Additional information provided by the company') }}</p>
                            <p class="text-sm text-esg8 mt-3">{{ $report['aditional_info'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 19 --}}
            <x-report.vertical.pagewithimage url="/images/report/startup/vertical/7.png" header="true" border="border-b-esg5">
                <div class="">
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('statement of ') }}</p>
                    <p class="text-6xl font-extrabold uppercase text-esg5 text-center">{{ __('responsability') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 20 TODO --}}
            <x-report.vertical.page title="{{ __('STATEMENT OF RESPONSABILITY') }}">
                <div class="py-5">
                    <div class="pb-44">
                        <div class="col-span-1">
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
            </x-report.vertical.page>

            {{-- Page 21 DONE--}}
            <x-report.vertical.page lastpage="true" noheader="true" nofooter="true">
                <img src="/images/report/startup/vertical/8.png" class="w-full print:h-full print:w-full "/>
            </x-report.vertical.page>
        </div>
    </div>
@endsection
