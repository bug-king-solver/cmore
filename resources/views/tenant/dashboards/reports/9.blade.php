@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' =>'bg-esg4'])
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
        }
        @page {
            size: A4 landscape; /* DIN A4 standard, Europe */
            margin: 0;
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

                @if ($outsource_workers != null)
                    pieCharts(
                        {!! json_encode($outsource_workers['labels']) !!},
                        {!! json_encode($outsource_workers['data']) !!},
                        'outsourced_workers',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
                    );
                @endif

                @if ($report['top_management_outsource'] != null)
                    pieCharts(
                        {!! json_encode($report['top_management_outsource']['labels']) !!},
                        {!! json_encode($report['top_management_outsource']['data']) !!},
                        'top_outsourced_workers',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
                    );
                @endif

                @if ($report['middle_management'] != null)
                    pieCharts(
                        {!! json_encode($report['middle_management']['labels']) !!},
                        {!! json_encode($report['middle_management']['data']) !!},
                        'middle_outsourced_workers',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
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

                @if ($report['minimum_wage'])
                    pieCharts(
                        {!! json_encode($report['minimum_wage']['labels']) !!},
                        {!! json_encode($report['minimum_wage']['data']) !!},
                        'minimum_wage',
                        [social_female, social_male, social_other],
                        '{{ __('workers') }}'
                    );
                @endif

                @if ($report['leaving_organisation'])
                    pieCharts(
                        {!! json_encode($report['leaving_organisation']['labels']) !!},
                        {!! json_encode($report['leaving_organisation']['data']) !!},
                        'leaving_organisation',
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

                barCharts(
                    {!! json_encode(isset($report['gross_earning']['labels']) ? $report['gross_earning']['labels'] : []) !!},
                    {!! json_encode(isset($report['gross_earning']['data']) ? $report['gross_earning']['data'] : []) !!},
                    'average_gross_earnings',
                    ["#FBB040"]
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
            {{-- Page 1 DONE--}}
            <x-report.pagewithimage url="/images/report/screen/page1.png">
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

            {{-- Page 2 DONE--}}
            <x-report.pagewithimage url="/images/report/screen/page2.png" footer="true" header="true">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('Company') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('overview') }}</p>
                </div>
            </x-report.page>

            {{-- Page 3 DONE --}}
            <x-report.page title="{{ __('company overview') }}">
                <div class="grid grid-cols-3 print:grid-cols-3 gap-10 py-5">
                    <div class="">
                        <div class="">
                            <p class="text-lg font-bold text-esg8">{{ __('Name') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['company']->name ?? '' }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Business sector') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['business_sector']->name ?? '' }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Headquarters') }}</p>
                            <p class="text-base text-esg8 mt-4">
                                @foreach($report['country'] as $row)
                                   {{ $row['name'] }}
                                @endforeach
                            </p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('NIPC/NIF') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['company']->vat_number }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Report period') }}</p>
                            <p class="text-base text-esg8 mt-4">  {{ date('Y-m-d', strtotime($questionnaire->from)) }} {{ __('to') }} {{ date('Y-m-d', strtotime($questionnaire->to)) }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Total of workers') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $report['colaborators']['value'] }}</p>
                        </div>
                    </div>

                    <div class="">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Financial information') }}</p>

                        <x-report.table.table>
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Annual revenue') }}</x-report.table.td>
                                <x-report.table.td>{{ $annual_revenue['unit'] ?? '€' }} {{ $annual_revenue['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Annual net revenue') }}</x-report.table.td>
                                <x-report.table.td>{{ $annual_net_revenue['unit'] ?? '€' }} {{ $annual_net_revenue['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Earnings before interest and taxes (EBIT)') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['ebit']['unit'] ?? '€' }} {{ $report['ebit']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Liabilities') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['liabilities']['unit'] ?? '€' }} {{ $report['liabilities']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Total assets') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['total_asset']['unit'] ?? '€' }} {{ $report['total_asset']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Expenses arising from activities associated with human resources') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['expense_activity']['unit'] ?? '€' }} {{ $report['expense_activity']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Expenditure for the reporting period for activities associated with raw materials') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['reporting_period_activities']['unit'] ?? '€' }} {{ $report['reporting_period_activities']['value'] }}</x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>

                    <div class="pt-7">
                        <x-report.table.table>
                            <x-report.table.tr>
                                <x-report.table.td>{{ __('Capital expenditure') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['capital_expenditure']['unit'] ?? '€' }} {{ $report['capital_expenditure']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td>{{ __('Total value of organisation`s debt') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['organisations_debt']['unit'] ?? '€' }} {{ $report['organisations_debt']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td>{{ __('Net debt') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['net_debt']['unit'] ?? '€' }} {{ $report['net_debt']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td>{{ __('Amount of interest expenses') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['interest_expenses']['unit'] ?? '€' }} {{ $report['interest_expenses']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td>{{ __('Net profit or loss') }}</x-report.table.td>
                                <x-report.table.td>{{ $report['profit_loss']['unit'] ?? '€' }} {{ $report['profit_loss']['value'] }}</x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td>{{ __('Listed company') }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($report['listed_company'])
                                        @include('icons.checkbox', ['color' =>  color(5)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 4 DONE--}}
            <x-report.pagewithimage url="/images/report/screen/page4.png" footer="true" header="true">
                <div class="">
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('ESG’s') }}</p>
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('scores') }}</p>
                </div>
            </x-report.page>

            {{-- Page 5 DONE --}}
            <x-report.page title="{{ __('ESG’s scores') }}">
                <div class="py-5">
                    <p class="text-lg font-bold text-esg8 uppercase">{{ __('Readiness level') }}</p>
                    <div class="grid grid-cols-2 print:grid-cols-2 gap-10 mt-4">
                        <div class="grid justify-center">

                            @if ($readiness['current_level'] == 5)
                                @include('icons.dashboard.9.readiness.5')
                            @else
                                @include('icons.dashboard.9.readiness.' . ($readiness['current_level'] === 0 ? 1 : $readiness['current_level'] + 1) )
                            @endif

                            @if ($readiness['current_level'] === 0)
                                <p class="text-4xl font-bold text-esg5 text-center mt-4">{{ __('Awareness ready') }}</p>

                                <p class="text-base text-esg8 mt-4">{{ __('The organisation is committed to the core themes of the ESG pillars and has defined and implemented a set of basic policies relating to them.') }}</p>
                                <p class="text-base text-esg8 mt-4">{{ __('The organisation has the necessary foundations to ensure its future growth and development, and a commitment to acquiring additional knowledge is recommended.') }}</p>
                            @endif

                            @if ($readiness['current_level'] == 1)
                                <p class="text-4xl font-bold text-esg5 text-center mt-4">{{ __('Knowledge Ready') }}</p>

                                <p class="text-base text-esg8 mt-4">{{ __('The organisation is committed to implementing a set of training programmes, practices and processes that ensure the implementation of the policies defined by the organisation in the areas of health and safety at work, ethics and conduct, human rights, anti-corruption and conflicts of interest.') }}</p>
                                <p class="text-base text-esg8 mt-4">{{ __('There is a clear effort to improve the organisations conditions with regard to ESG themes, and there is room for progress.') }}</p>
                            @endif

                            @if ($readiness['current_level'] == 2)
                                <p class="text-4xl font-bold text-esg5 text-center mt-4">{{ __('Performance Ready') }}</p>
                                <p class="text-base text-esg8 mt-4">{{ __('The organisation monitors and communicates its performance in relation to a set of indicators relating to the three ESG pillars. It has implemented a strategy for receiving complaints in the event of non-compliance or irregularities, reflecting its commitment to stakeholders.') }}</p>
                                <p class="text-base text-esg8 mt-4">{{ __('The organisations performance is oriented towards transparency, credibility and evolution and, as such, continuous investment in ESG issues is encouraged.') }}</p>
                            @endif

                            @if ($readiness['current_level'] == 3)
                                <p class="text-4xl font-bold text-esg5 text-center mt-4">{{ __('Maturity Ready') }}</p>

                                <p class="text-base text-esg8 mt-4">{{ __('The organisation is highly committed to complying with and respecting the ESG pillars and has begun to define and implement strategies that contribute to reducing, mitigating and preventing their impacts.') }}</p>
                                <p class="text-base text-esg8 mt-4">{{ __('It is notable for its external collaborations in the field of sustainability and for its contribution to sustainable development objectives within the scope of its activity.') }}</p>
                                <p class="text-base text-esg8 mt-4">{{ __('The efforts made by the organisation give it credibility and reputation, setting an example for others.') }}</p>
                            @endif

                            @if ($readiness['current_level'] == 4 || $readiness['current_level'] == 5)
                                <p class="text-4xl font-bold text-esg5 text-center mt-4">{{ __('Ready for the next step') }}</p>

                                <p class="text-base text-esg8 mt-4">{{ __('This means that you are more than ready to try out the in-depth questionnaire.') }}</p>
                            @endif
                        </div>

                        <div class="">
                            <p class="text-base text-esg8 mb-4"> {{ __('Achievements') }} </p>

                            @if ($readiness['current_level'] === 0)
                                <div class="flex flex-col items-start gap-5 mt-9">
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.master_policies', (! $readiness['level1'][0] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]) )
                                        <p class="text-base text-esg8 uppercase">{{ __('Master of Policies') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.highest_governance', (! $readiness['level1'][1] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Highest Governance Body constitution') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.suppliers', (! $readiness['level1'][2] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Highest Governance Body constitution') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.corruption_conflict', (! $readiness['level1'][3] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Commited to corruption and conflict of interests prevention') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($readiness['current_level'] == 1)
                                <div class="flex flex-col items-start gap-5 mt-9">
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level2.1', (! $readiness['level2'][0] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Committed to the best practices') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level2.2', (! $readiness['level2'][1] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Committed to the ethic and conduct') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level2.3', (! $readiness['level2'][2] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Committed to the policies implementation') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level2.4', (! $readiness['level2'][3] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Committed to Due Diligence') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level2.5', (! $readiness['level2'][4] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Disclosure of the policies') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($readiness['current_level'] == 2)
                                <div class="flex flex-col items-start gap-5 mt-9">
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level3.1', (! $readiness['level3'][0] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Complaint Mechanisms') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level3.2', (! $readiness['level3'][1] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Master of Reports') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level3.3', (! $readiness['level3'][2] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Environmentally Conscious') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level3.4', (! $readiness['level3'][3] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Customer Oriented') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level3.5', (! $readiness['level3'][4] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Gender Equality') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($readiness['current_level'] == 3)
                                <div class="flex items-start gap-5 mt-9">
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level4.1', (! $readiness['level4'][0] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Attentive to SDGs') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level4.2', (! $readiness['level4'][1] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Committed to your carbon footprint') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level4.3', (! $readiness['level4'][2] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Committed to your energy efficiency') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level4.4', (! $readiness['level4'][3] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Adherence to global principles') }}</p>
                                    </div>
                                    <div class="flex items-center gap-5">
                                        @include('icons.dashboard.9.badge.level4.5', (! $readiness['level4'][4] ? ['color' => color(7), 'width' => 60, 'height' => 60] : [ 'color' => color(5), 'width' => 60, 'height' => 60]))
                                        <p class="text-base text-esg8 uppercase">{{ __('Social Responsibility') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($readiness['current_level'] == 4 || $readiness['current_level'] == 5)
                                <div class="flex items-center gap-5 mt-9">
                                    @include('icons.dashboard.9.badge.level5.goal')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 6 DONE--}}
            <x-report.page title="{{ __('ESG’s scores') }}">
                <div class="py-5">
                    <p class="text-lg font-bold text-esg8 uppercase">{{ __('Priority matrix') }}</p>
                    <div class="grid grid-cols-2 print:grid-cols-2 gap-10 mt-4">
                        <div class="">
                            @if ($action_plan)
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
                            @endif
                        </div>
                        <div class="">
                            @if ($action_plan_table)
                                <div id="action_list" class="md:col-span-1 lg:p-5 xl:p-10 lg:mt-0 print:p-2 h-auto overflow-x-auto">
                                    <table class="text-esg8/70 font-encodesans w-full table-auto">
                                        <tbody class="font-medium">
                                            @foreach ($action_plan_table as $initiative)
                                                <tr class="text-xs action_plan_tr print:!pt-2 action_plan_{{ $loop->index + 1 }}">
                                                    <td class="p-2 print:p-0 text-3xl font-extrabold text-esg5">{{ sprintf("%02d", $loop->index + 1) }}</td>
                                                    <td class="p-2 print:p-0 !pl-4 text-sm text-esg8">{{ $initiative->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 7 DONE--}}
            <x-report.pagewithimage url="/images/report/screen/page7.png" footer="true" header="true" footerborder="border-t-esg2">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg2">{{ __('environment') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 8 DONE--}}
            <x-report.page title="{{ __('environment') }}" footerborder="border-t-esg2 !text-esg4" bgimage="/images/report/screen/page8_bg.png">
                <div class="py-5">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4 pb-40">
                        <div class="">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Atmospheric pollutants') }}</p>

                            <x-report.table.table class="!border-t-esg2">
                                <x-report.table.tr>
                                    <x-report.table.td>{{ __('Air emissions monitoring') }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($report['air_emission'])
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            </x-report.table.table>

                            <div class="mt-4">
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Atmospheric Pollutants')]) }}" class="!h-auto !shadow-none !bg-transparent !p-0">
                                    <x-charts.bar id="atmospheric_pollutants" class="m-auto relative !h-full !w-full" unit="{{ $atmospheric_pollutants['unit'] ?? 'kg' }}"/>
                                    <div class="flex gap-5">
                                        @php
                                            $subpoint = json_encode([
                                                    [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $atmospheric_pollutants['labels'][0] ],
                                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $atmospheric_pollutants['labels'][1]]
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
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>

                        <div class="">
                            <p class="text-base text-esg8 mb-4">{{ __('Ozone depleting substances') }}</p>

                            <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none !bg-transparent !p-0">
                                <x-charts.bar id="ozone_layer" class="m-auto relative !h-full !w-full" unit="{{ $ozone_layer_depleting['unit'] ?? 'kg' }}" />

                                <div class="flex gap-5">
                                    @php
                                        $subpoint = json_encode([
                                                [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $ozone_layer_depleting['labels'][0] ],
                                                [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $ozone_layer_depleting['labels'][1] ]
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
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="">
                            <p class="text-base text-esg8 mb-4">{{ __('Scope 1 GHG emissions') }}</p>
                            <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none !bg-transparent !p-0">
                                <x-charts.bar id="co2_emissions" class="m-auto relative !h-full !w-full " unit="{{ $GHG_emission['unit'] ?? 't CO2 eq' }}" />

                                <div class="flex gap-5">
                                    @php
                                        $subpoint = json_encode([
                                                [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $GHG_emission['labels'][0] ],
                                                [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $GHG_emission['labels'][1] ]
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
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 8.1 DONE--}}
            <x-report.page title="{{ __('environment') }}" footerborder="border-t-esg2 !text-esg4">
                <div class="py-5">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="col-span-2">
                            <div class="grid grid-cols-2 print:grid-cols-2 gap-10 mt-4">
                                <div class="">
                                    <p class="text-base text-esg8 mb-4">{{ __('Scope 2 GHG emissions') }}</p>
                                    <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none !bg-transparent !p-0">
                                        <x-charts.bar id="co2_emissions2" class="m-auto relative !h-full !w-full" unit="{{ $GHG_emission2['unit'] ?? 't CO2 eq' }}" />

                                        <div class="flex gap-5">
                                            @php
                                                $subpoint = json_encode([
                                                        [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $GHG_emission2['labels'][0] ],
                                                        [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $GHG_emission2['labels'][1] ]
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
                                    </x-cards.card-dashboard-version1-withshadow>
                                </div>

                                <div class="">
                                    <p class="text-base text-esg8 mb-4">{{ __('Scope 3 GHG emissions') }}</p>
                                    <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none !bg-transparent !p-0">
                                        <x-charts.bar id="co2_emissions3" class="m-auto relative !h-full !w-full " unit="{{ $GHG_emission3['unit'] ?? 't CO2 eq' }}" />

                                        <div class="flex gap-5">
                                            @php
                                                $subpoint = json_encode([
                                                        [ 'color' => 'bg-[#008131]', 'text' => __('Baseline year: ') . $GHG_emission3['labels'][0] ],
                                                        [ 'color' => 'bg-[#99CA3C]', 'text' => __('Reporting period: ') . $GHG_emission3['labels'][1] ]
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
                                    </x-cards.card-dashboard-version1-withshadow>
                                </div>

                                <div class="col-span-2">
                                    <img src="/images/report/screen/page8-1_bg.png" >
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Energy consumption') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach($report['energy_consumption'] as $row)
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
                                <p class="text-base text-esg8">{{ __('Energy intensity') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $energy_intensity['value'] }} <span class="text-2xl font-bold text-esg2">{{ $energy_intensity['unit'] ?? 'MWh / €' }}</span></label>
                            </div>

                            <div class="">
                                <x-cards.card-dashboard-version1-withshadow  type="none" class="!h-auto shadow-none !bg-transparent !p-0" >
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="text-center w-full">
                                            @if ($energy_consumption_baseline != null)
                                                <label class="text-xs font-medium text-esg8"> {{ __('Baseline year: ') . $energy_consumption_baseline_year['value'] }} </label>
                                                <x-charts.donut id="energy_consumption_baseline" class="m-auto !h-[170px] !w-[170px] print:!h-[130px] print:!w-[130px] mt-2" />
                                                <div class="grid content-center mt-5" id="energy_consumption_baseline-legend"></div>
                                            @else
                                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                            @endif
                                        </div>

                                        <div class="text-center  w-full">
                                            @if ($energy_consumption_reporting != null)
                                                <label class="text-xs font-medium text-esg8"> {{ __('Reporting period: ') . $energy_consumption_reporting_year }} </label>
                                                <x-charts.donut id="energy_consumption_reporting" class="m-auto !h-[170px] !w-[170px] print:!h-[130px] print:!w-[130px] mt-2" />
                                                <div class="grid content-center mt-5" id="energy_consumption_reporting-legend"></div>
                                            @else
                                                <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex justify-center gap-5">
                                        @php
                                            $subpoint = json_encode([
                                                    [ 'color' => 'bg-[#008131]', 'text' => __('Renewable') ],
                                                    [ 'color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable') ]
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
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 9 DONE--}}
            <x-report.page title="{{ __('environment') }}" footerborder="border-t-esg2">
                <div class="py-5">
                    <div class="grid grid-cols-2 print:grid-cols-2 gap-10 mt-4">
                        <div>
                            <div class="">
                                <p class="text-lg font-bold text-esg8 uppercase mb-4">{{ __('Waste management') }}</p>

                                <x-report.table.table class="!border-t-esg2">
                                    @foreach($report['waste_management'] as $row)
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
                                <p class="text-base text-esg8 mb-4">{{ __('Total waste generated (in the reporting period)') }}</p>

                                <x-cards.card-dashboard-version1-withshadow type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none">
                                    @if ($waste_produced != null)
                                        <x-charts.donut id="waste_produced" class="m-auto !h-[180px] !w-[180px] print:!h-[150px] print:!w-[150px]" legendes="true" grid="2"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>

                        <div class="">
                            <img src="/images/report/screen/page9_bg.png">
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 10 DONE--}}
            <x-report.page title="{{ __('environment') }}" footerborder="border-t-esg2" bgimage="/images/report/screen/page10_bg.png">
                <div class="py-5">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4 pb-72">
                        <div class="">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Use of water resources') }}</p>

                            <x-report.table.table class="!border-t-esg2">
                                @foreach($water_consumption as $row)
                                    @if ($loop->iteration < 4)
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
                                    @endif
                                @endforeach
                            </x-report.table.table>

                        </div>

                        <div class="">
                            <p class="text-base text-esg8 mb-6">{{ __('Wastewater discharge licence') }}</p>

                            <x-report.table.table class="!border-t-esg2">
                                @foreach($water_consumption as $row)
                                    @if ($loop->iteration > 3)
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
                                    @endif
                                @endforeach
                            </x-report.table.table>
                        </div>

                        <div class="">
                            <p class="text-base text-esg8 mb-6">{{ __('Water intensity') }}</p>
                            <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $water_intensity['value'] }} <span class="text-2xl font-bold text-esg2">{{ $water_intensity['unit'] ?? 'm3 / €' }}</span></label>
                        </div>
                    </div>
                </div>
            </x-report.page>

             {{-- Page 11 DONE--}}
             <x-report.page title="{{ __('environment') }}" footerborder="border-t-esg2">
                <div class="py-5">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="">
                            <p class="text-lg font-bold text-esg8">{{ __('Impacts on Biodiversity and Ecosystems') }}</p>
                            <x-report.table.table class="!border-t-esg2">
                                @foreach($biodiversity_impact as $row)
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
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Total number of operations of the organisation in the reporting period') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $report['operations_reporting_period']['value'] }}</label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Operations in or adjacent to protected areas and/or areas rich in biodiversity') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $report['adjacent_protected_areas']['value'] }}</label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Operations located in sensitive, protected or high biodiversity value areas, outside environmentally protected areas') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $report['strategy_address_impacts']['value'] }} <span class="text-2xl font-bold text-esg16">({{ $report['strategy_address_impacts_percentage']['value'] }}{{ $report['strategy_address_impacts_percentage']['unit'] ?? '%' }})</span></label>
                            </div>
                        </div>

                        <div class="">
                            <img src="/images/report/screen/page11_bg.png">
                        </div>
                    </div>
                </div>
             </x-report.page>

             {{-- Page 12 DONE--}}
             <x-report.pagewithleftimage title="{{ __('environment') }}" footerborder="border-t-esg2" bgimage="/images/report/screen/page12_bg.png">
                <div class="py-5">
                    <div class="">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Organisation Activities Impact') }}</p>
                        <x-report.table.table class="!border-t-esg2">
                            @foreach($organization_activity_impact as $row)
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
             </x-report.pagewithleftimage>

             {{-- Page 13 DONE --}}
             <x-report.pagewithrightimage title="{{ __('environment') }}" footerborder="border-t-esg2" bgimage="/images/report/screen/page13_bg.png">
                <div class="py-5">
                    <div class="">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Raw-Materials Consumption') }}</p>

                        <x-report.table.table class="!border-t-esg2">
                            @foreach($report['row_matrials'] as $row)
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
                            <p class="text-base text-esg8">{{ __('Total building materials used') }}</p>
                            <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">{{ $total_building_matrial_used['value'] }} <span class="text-2xl font-bold text-esg2">{{ $total_building_matrial_used['unit'] ?? 't' }}</span></label>
                        </div>

                        <div class="mt-4">
                            <p class="text-base text-esg8">{{ __('Recovered, recycled and/or of biological origin materials') }}</p>
                            <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg2 mt-2">
                                {{ $biological_origin_matrials['value'] }}
                                <span class="text-2xl font-bold text-esg2">{{ $biological_origin_matrials['unit'] ?? 't' }}</span>
                                <span class="text-2xl font-bold text-esg16">({{ $report['biological_origin_matrials_percentage']['value'] }}{{ $report['biological_origin_matrials_percentage']['unit'] ?? '%' }})</span>
                            </label>
                        </div>
                    </div>
                </div>
             </x-report.pagewithrightimage>

             {{-- Page 14 DONE--}}
            <x-report.pagewithimage url="/images/report/screen/page14.png" footer="true" header="true" footerborder="border-t-esg1">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg1">{{ __('Social') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 15 DONE--}}
            <x-report.page title="{{ __('Social') }}" footerborder="border-t-esg1">
                <div class="py-5 print:py-0">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-5 mt-4">
                        <div class="">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Workers of the Organisation') }}</p>

                            <div class="mt-2">
                                <p class="text-base text-esg8">{{ __('Total of contracted and subcontracted workers') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $report['subcontract_workers']['value'] }} <span class="text-2xl font-bold text-esg1">{{ __('workers') }}</span></label>
                            </div>

                            <div class="mt-2">
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($report['workers'] as $row)
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

                            <div>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Subcontracted workers')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($outsource_workers != null)
                                        <x-charts.donut id="outsourced_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1" />
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>

                        <div class="">
                            <div>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Middle management (subcontracted workers)')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($report['middle_management'] != null)
                                        <x-charts.donut id="middle_outsourced_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Top management (subcontracted workers)')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($report['top_management_outsource'] != null)
                                        <x-charts.donut id="top_outsourced_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>

                        <div class="">
                            <div>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted workers')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($contract_workers != null)
                                        <x-charts.donut id="contracted_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Middle management (contracted workers)')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($middle_management != null)
                                        <x-charts.donut id="middle_management" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 16 DONE--}}
            <x-report.page title="{{ __('Social') }}" footerborder="border-t-esg1">
                <div class="py-5">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-5 mt-4">
                        <div class="">
                            <div class="">
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Top management (contracted workers)')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($top_management != null)
                                        <x-charts.donut id="top_management" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div class="">
                                <p class="text-lg font-bold uppercase text-esg8 mb-4">{{ __('Workers conditions') }}</p>

                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($report['worker_condition'] as $row)
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
                            <div class="">
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted workers receiving the national minimum wage by gender')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($report['minimum_wage'] != null)
                                        <x-charts.donut id="minimum_wage" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div class="mt-4">
                                <img src="/images/report/screen/page16_bg.png">
                            </div>

                        </div>

                        <div class="">
                            <div class="">
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Workers Leaving the organisation, by gender')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent" contentplacement="none" titleclass="!normal-case">
                                    @if ($report['leaving_organisation'] != null)
                                        <x-charts.donut id="leaving_organisation" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div class="">
                                <p class="text-base text-esg8 mb-2">{{ __('Turnover') }}</p>
                                <p class="text-base text-esg8/50">{{ __('Workers who left the organisation in the reporting period / Workers in the organisation in the reporting period') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $worker_turnover['value'] }} <span class="text-2xl font-bold text-esg1">{{ $worker_turnover['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($report['salary_scale'] as $row)
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
                </div>
            </x-report.page>

            {{-- Page 17 DONE--}}
            <x-report.page title="{{ __('Social') }}" footerborder="border-t-esg1">
                <div class="py-5 print:py-0">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-5 mt-4">
                        <div class="">
                            <div>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Average gross earnings per hour ')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0">
                                    <x-charts.bar id="average_gross_earnings" class="m-auto relative !h-full !w-full" unit="{{ $report['gross_earning']['unit'] ?? '€' }}"/>
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Gender pay gap') }}</p>
                                <p class="text-base text-esg8/50">{{ __('(Average gross earnings per hour (€/h) for men - Average gross earnings per hour (€/h) for women) / Average gross earnings per hour (€/h) for men') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $gender_paygap['value'] }} <span class="text-2xl font-bold text-esg1">{{ $gender_paygap['unit'] ?? '%' }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Total gross annual remuneration including prize money of the highest paid individual') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">
                                    {{ $report['annual_remuneration']['value'] }}
                                    <span class="text-2xl font-bold text-esg1">{{ $report['annual_remuneration']['unit'] ?? '€' }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="">
                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Average annual gross total remuneration including bonus money of all contracted workers (excluding the highest paid individual)') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">
                                    {{ $report['gross_remuneration']['value'] }}
                                    <span class="text-2xl font-bold text-esg1">{{ $report['gross_remuneration']['unit'] ?? '€' }}</span>
                                </label>
                            </div>

                            <div class="mt-8">
                                <p class="text-lg uppercase font-bold text-esg8">{{ __('Safety and health') }}</p>

                                <x-report.table.table class="!border-t-esg1">
                                    @foreach($report['safety_health'] as $row)
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
                                <p class="text-base text-esg8">{{ __('Accidents at work during the reporting period') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $numberof_accident['value'] }} <span class="text-2xl font-bold text-esg1">{{ __('accidents') }}</span></label>
                            </div>
                        </div>

                        <div class="">
                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Days lost due to injury, accident, death or illness') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $day_lost_by_accident['value'] }} <span class="text-2xl font-bold text-esg1">{{ __('days') }}</span></label>
                            </div>

                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Practices in place to promote the safety and health of contracted workers') }}</p>
                                @foreach($report['practices_safety_health'] as $row)
                                    @if ($row['status'])
                                        <div class="flex items-baseline gap-3 ">
                                            <div class="mt-3">
                                                <div class="w-3 h-3 rounded-full bg-esg1"></div>
                                            </div>
                                            <div> {{ $row['label'] }} </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 18 DONE --}}
            <x-report.pagewithrightimage title="{{ __('environment') }}" footerborder="border-t-esg1" bgimage="/images/report/screen/page18_bg.png">
                <div class="py-5">
                    <div class="">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Training for the Workers') }}</p>

                        <div class="mt-4">
                            <x-report.table.table class="!border-t-esg1">
                                @foreach($traning_for_works as $row)
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
                            <p class="text-base text-esg8">{{ __('Hours in training and capacity development') }}</p>
                            <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg1 mt-2">{{ $number_of_hours['value'] }} <span class="text-2xl font-bold text-esg1">{{ __('hours') }}</span></label>
                        </div>
                    </div>
                </div>
            </x-report.pagewithrightimage>

            {{-- Page 19 DONE--}}
            <x-report.pagewithimage url="/images/report/screen/page19.png" footer="true" header="true" footerborder="border-t-esg3">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg3">{{ __('governance') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            {{-- Page 20 DONE--}}
            <x-report.page title="{{ __('governance') }}" footerborder="border-t-esg3">
                <div class="py-5 print:py-0">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-5 mt-4">
                        <div class="">
                            <div>
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Institutional') }}</p>

                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['institutional'] as $row)
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

                            <div class="mt-8">
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Highest Governance Body') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['highest_governer_body'] as $row)
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
                                <p class="text-base text-esg8">{{ __('Highest governance body of the organisation constituted and structured') }}</p>

                                @if ($no_executive_members)
                                    <p class="text-2xl font-bold text-esg3">{{__('Board of Directors with no Executive Members')}}</p>
                                @endif

                                @if ($with_executive_members)
                                    <p class="text-2xl font-bold text-esg3 mt-2">{{__('Board of Directors with Executive Members')}}</p>
                                @endif

                                @if ($mixed)
                                    <p class="text-2xl font-bold text-esg3 mt-2">{{__('Mixed')}}</p>
                                @endif

                                @if ($management_board)
                                    <p class="text-2xl font-bold text-esg3 mt-2">{{__('Management Board')}}</p>
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <div class="mt-4">
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Members of the highest governance body')]) }}"
                                    type="flex" class="!h-auto !shadow-none !bg-transparent !p-0 " titleclass="!normal-case">
                                    @if ($high_governance_body)
                                        <x-charts.donut id="gender_high_governance_body" class=" !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    @else
                                        <p class="text-center pt-10 w-full">{{ __('Data not found!') }}</p>
                                    @endif
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>

                            <div class="mt-8">
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Impacts on Communities') }}</p>

                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['impacts_communities'] as $row)
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

                        <div class="">
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Initiatives undertaken by the organisation in regards to the community') }}</p>

                                @foreach($report['initiatives_undertaken'] as $row)
                                    @if ($row['status'])
                                        <div class="flex items-baseline gap-3">
                                            <div> <div class="w-3 h-3 rounded-full bg-esg3"></div> </div>
                                            <div>{{ $row['label'] }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <img src="/images/report/screen/page20_bg.png">
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 21 DONE--}}
            <x-report.page title="{{ __('governance') }}" footerborder="border-t-esg3">
                <div class="py-5 print:py-0">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-5 mt-4">
                        <div class="">
                            <div>
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Significant Incidents') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $significant_incidents[0]['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($significant_incidents[0]['status'])
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Amount of the fines imposed') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2"> {{ $amount_fines_imposed['value'] }} <span class="text-2xl font-bold text-esg3">{{ $amount_fines_imposed['unit'] ?? '€'}}</span></label>
                            </div>

                            <div class="mt-8">
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Highest Governance Body') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $significant_incidents[1]['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if ($significant_incidents[1]['status'])
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>

                            <div class="mt-4">
                                <p class="text-base text-esg8">{{ __('Incidents of discrimination that have occurred') }}</p>
                                <label for="checkbox-website" class="font-encodesans text-4xl font-bold text-esg3 mt-2"> {{ $incidents_discrimination['value'] }} <span class="text-2xl font-bold text-esg3">{{ __('incidents') }}</span></label>
                            </div>
                        </div>

                        <div class="">
                            <div >
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Complaints') }}</p>

                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['complaints'] as $row)
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

                            <div class="mt-8">
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Anual Reporting') }}</p>

                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['anual_reporting'] as $row)
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
                                @foreach($annual_reporting as $row)
                                    @if ($row['status'])
                                        <div class="flex items-center w-full">
                                            <div class="w-2 h-2 rounded-full bg-esg3"></div>
                                            <div class="ml-3">
                                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Policies implemented') }}</p>

                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['policies_implemented'] as $row)
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
                                @foreach($report['policies_implemented_option'] as $row)
                                    @if ($row['status'])
                                        <div class="flex items-center w-full">
                                            <div class="w-2 h-2 rounded-full bg-esg3"></div>
                                            <div class="ml-3">
                                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 22 DONE--}}
            <x-report.page title="{{ __('governance') }}" footerborder="border-t-esg3">
                <div class="py-5">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-5 mt-4">
                        <div class="">
                            <div>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['prevention_policy'] as $row)
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
                                <p class="text-lg font-bold uppercase text-esg8 my-8">{{ __('Relations with suppliers') }}</p>

                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['relations_suppliers'] as $row)
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
                                @foreach($report['supplier_policy'] as $row)
                                    @if ($loop->iteration < 6)
                                        @if ($row['status'])
                                            <div class="flex items-baseline w-full mt-2">
                                                <div class="w-4"><div class="w-2 h-2 rounded-full bg-esg3"></div></div>
                                                <div class="ml-3">
                                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="">
                            <div class="">
                                @foreach($report['supplier_policy'] as $row)
                                    @if ($loop->iteration > 6)
                                        @if ($row['status'])
                                            <div class="flex items-baseline w-full mt-2">
                                                <div class="w-4"><div class="w-2 h-2 rounded-full bg-esg3"></div></div>
                                                <div class="ml-3">
                                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $row['label'] }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-8">
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach($report['supplier_code_ethics'] as $row)
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

                        <div class="">
                           <img src="/images/report/screen/page22_bg.png">
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 23 DONE--}}
            <x-report.page title="{{ __('governance') }}" footerborder="border-t-esg3">
                <div class="py-5">
                    <div class="grid grid-cols-2 print:grid-cols-2 gap-5 mt-4">
                        <div class="">
                            <p class="text-lg font-bold uppercase text-esg8 my-8">{{ __('Sustainable Development Goals') }}</p>

                            <div class="grid grid-cols-5 print:grid-cols-5 gap-3 mt-10 ">
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

                        <div class="">
                            <p class="text-lg font-bold uppercase text-esg8 my-8">{{ __('Sustainable Development Goals') }}</p>

                            @if ($logos['status'])

                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Signatory or involved in external sustainability-related initiatives or principles') }}</x-report.table.td>
                                        <x-report.table.td>
                                            @if (true)
                                                @include('icons.checkbox', ['color' =>  color(3)])
                                            @else
                                                @include('icons.checkbox-no')
                                            @endif
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>

                                <div class="grid grid-cols-3 print:grid-cols-3 items-center gap-12 py-8">
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
                            @endif
                        </div>
                    </div>
                </div>
            </x-report.page>

             {{-- Page 24 DONE--}}
             <x-report.page title="{{ __('governance') }}" footerborder="border-t-esg3" lastpage="true" noheader="true" nofooter="true">
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
