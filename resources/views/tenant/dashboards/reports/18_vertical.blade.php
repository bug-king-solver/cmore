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
            actionPlan();
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
                [social_female, social_male, social_other]
            );

            barCharts(
                {!! json_encode([__('Female'), __('Male'), __('Other')]) !!},
                {!! json_encode([$female_hourly_earnings, $male_hourly_earnings, $other_hourly_earnings]) !!},
                'hourly_earnings',
                ['{{ color(1) }}']
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
            // Bar charts
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

        // action plan
        function actionPlan() {
            var color_code = twConfig.theme.colors.esg7;
            @if ($actionPlan)
                var data = {!! $actionPlan['series'] !!};
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
                            min: {!! $actionPlan['xaxis']['min'] !!},
                            max: {!! $actionPlan['xaxis']['max'] !!}
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
                            min: {!! $actionPlan['yaxis']['min'] !!},
                            max: {!! $actionPlan['yaxis']['max'] !!}
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
    <div class="px-4 lg:px-0 print:px-0">
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

        <div class="max-w-7xl mx-auto print:w-full">
            {{-- Page 1 DONE--}}
            <x-report.vertical.home url="/images/report/esg_simple/vertical/1.png">
                <div class="flex p-14 h-full flex-col justify-between m-auto border-b-[20px] border-b-esg5 w-full">
                    <div class="flex justify-center">
                        @include('icons.logos.cmore')
                    </div>

                    <div class="mt-10">
                        <p class="text-7xl text-esg5">2023</p>

                        <p class="text-4xl font-extrabold text-esg8 mt-5">{{ __('Sustainability Report') }}</p>

                        <p class="text-2xl text-esg8 mt-10">{{ __('ESG – Environmental, Social and Governance') }}</p>
                    </div>
                </div>
            </x-report.vertical.home>

            {{-- Page 2 --}}
            <x-report.vertical.pagewithimage url="/images/report/esg_simple/vertical/2.png" header="true">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5 text-center">{{ __('Company') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('overview') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 3  --}}
            <x-report.vertical.page title="{{ __('company overview') }}">
                <div class="grid grid-cols-1 gap-5 py-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="">
                            <p class="text-xs font-medium text-esg8">{{ __('Name') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $questionnaire->company()->first()->name ?? '' }}</p>
                        </div>

                        <div class="">
                            <p class="text-xs font-medium text-esg8">{{ __('Business sector') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $questionnaire->company->business_sector()->first()->name ?? '' }}</p>
                        </div>
                        <div class="">
                            <p class="text-xs font-medium text-esg8">{{ __('Headquarters') }}</p>
                            <p class="text-base text-esg8 mt-2">
                                @foreach(getCountriesWhereIn([$questionnaire->company->country]) as $row)
                                   {{ $row['name'] }}
                                @endforeach
                            </p>
                        </div>

                        <div class="">
                            <p class="text-xs font-medium text-esg8">{{ __('NIPC/NIF') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $questionnaire->company()->first()->vat_number }}</p>
                        </div>

                        <div class="">
                            <p class="text-xs font-medium text-esg8">{{ __('Report period') }}</p>
                            <p class="text-base text-esg8 mt-2">  {{ date('Y-m-d', strtotime($questionnaire->from)) }} {{ __('to') }} {{ date('Y-m-d', strtotime($questionnaire->to)) }}</p>
                        </div>

                        <div class="">
                            <p class="text-xs font-medium text-esg8">{{ __('Total of workers') }}</p>
                            <p class="text-base text-esg8 mt-2">{{ $charts['report']['categories']['colaborators']['indicators']['value'] ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="">
                        <p class="text-base font-medium text-esg16 uppercase">{{ __('Financial information') }}</p>
                        <div>
                            <x-report.table.table margin="!mt-2">
                                @foreach($charts['report']['categories']['financial_information']['indicators'] as $indicator)
                                    <x-report.table.tr>
                                        <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                        <x-report.table.td>{{ $indicator['unit_default'] ?? '€' }} {{ $indicator['value'] }}</x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach

                                @foreach($charts['report']['categories']['financial_information_extra']['indicators'] as $indicator)
                                    <x-report.table.tr>
                                        <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                        <x-report.table.td>{{ $indicator['unit_default'] ?? '€' }} {{ $indicator['value'] }}</x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach

                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $charts['report']['categories']['listed_company']['name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($charts['report']['categories']['listed_company']['indicators'][0]['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(5)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>

                                @if ($charts['report']['categories']['listed_company']['indicators'][0]['value'])
                                    @foreach($charts['report']['categories']['financial_information_extra2']['indicators'] as $indicator)
                                        <x-report.table.tr>
                                            <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                            <x-report.table.td>{{ $indicator['unit_default'] ?? '€' }} {{ $indicator['value'] }}</x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                @endif
                            </x-report.table.table>
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 4 --}}
            <x-report.vertical.pagewithimage url="/images/report/esg_simple/vertical/3.png" header="true" >
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5 text-center">{{ __('ESG’s') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('scores') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 5  --}}
            <x-report.vertical.page title="{{ __('ESG’s scores') }}">
                <div class="py-5">
                    <p class="text-lg font-medium text-esg8 uppercase">{{ __('Priority matrix') }}</p>
                    <div class="grid grid-cols-1 gap-5">
                        <div class="">
                            @if ($actionPlan)
                                <div x-data="{showExtraLegend: false}">
                                    <div @mouseover="showExtraLegend = true" @mouseover.away="showExtraLegend = false"  class="relative w-full">
                                        <div class="h-[350px] w-[350px]">
                                            <div></div>
                                            <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                            <div class="text-esg8 absolute left-[31px] top-[15px] rotate-90 text-4xl">
                                                @include('icons.arrow', ['class' => 'rotate-180', 'fill' => color(7)])
                                            </div>
                                            <div
                                                class="text-esg8 absolute left-[310px] top-[300px] text-4xl">
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
                            <p class="text-lg font-medium text-esg8 uppercase">{{ __('ACTION PLAN') }}</p>
                            @if ($action_plan_table)
                                <div id="action_list" class="mt-4">
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
            </x-report.vertical.page>

            {{-- Page 6  TODO --}}
            {{-- <x-report.page title="{{ __('ESG’s scores') }}">

            </x-report.page> --}}

            {{-- Page 7 TODO --}}
            {{--<x-report.page title="{{ __('ESG’s scores') }}">
                <div class="grid grid-cols-2 gap-5">
                    <div class="py-5 "></div>
                    <div>
                        <img src="/images/report/esg_simple/7.png">
                    </div>
                </div>
            </x-report.page> --}}

            {{-- Page 8 --}}
            <x-report.vertical.pagewithimage url="/images/report/esg_simple/vertical/4.png" header="true" border="border-b-esg2">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg2 text-center">{{ __('environment') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('performance') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            {{-- Page 9  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <div>
                        <p class="text-base font-medium text-esg8 uppercase">{!! __('mitigation and adaptation') !!}</p>
                        <p class="text-xs font-medium mt-4 uppercase text-esg16">{!! __('Policies and targets') !!}</p>

                        <x-report.table.table margin="!mt-1" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['climate_change']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-xs font-medium mt-4 uppercase text-esg16 mt-4">{!! __('Climate change mitigation and adaptation targets disclosed by the organisation') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['climate_change_mitigation']['indicators'][0]['value'] ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium mt-4 uppercase text-esg16">{!! __('Transition plan') !!}</p>

                        <x-report.table.table margin="!mt-1" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['transition_plan']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-xs font-medium mt-4 uppercase text-esg16 mt-4">{!! __('Actions implemented for climate change mitigation and adaption disclosed by the organisation') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['action_plan_climate_change_mitigation']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-xs font-medium mt-4 uppercase text-esg16 mt-4">{!! __('Resources used to implement those actions') !!}</p>
                        @foreach($charts['report']['categories']['resource_for_action']['indicators'] as $indicator)
                            @if ($indicator['value'] == 'yes')
                                <x-list.bullet
                                    class="mt-4"
                                    bgcolor="bg-esg2"
                                    title="{{ $indicator['indicator_name'] }}" />
                            @endif
                        @endforeach
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 10  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <div>
                        <p class="text-base font-medium text-esg8 uppercase">{!! __('climate-related risks and/or opportunities') !!}</p>
                        <p class="text-xs font-medium mt-4 uppercase text-esg16">{!! __('Analysis and risk/opportunity identification') !!}</p>

                        <x-report.table.table margin="!mt-1" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['analysis_risk_opportunity']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-xs font-medium mt-4 uppercase text-esg16">{!! __('Types of risks identified in this analysis') !!}</p>
                        @foreach($charts['report']['categories']['type_of_risk']['indicators'] as $indicator)
                            @if ($indicator['value'] == 'yes')
                                <x-list.bullet
                                    class="mt-4"
                                    bgcolor="bg-esg2"
                                    title="{{ $indicator['indicator_name'] }}" />
                            @endif
                        @endforeach
                    </div>

                    <div>
                        <p class="text-xs font-medium mt-4 uppercase text-esg16">{!! __('Risk/opportunity management') !!}</p>
                        <x-report.table.table margin="!mt-1" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['risk_opportunity_management']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-xs font-medium mt-4 uppercase text-esg16">{!! __('Climate-related risks with the potential to affect the organisation/business financially or strategically') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['risk_strategically']['indicators'][0]['value'] ?? '-' }}</p>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 11  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Energy Consumption') !!}</p>
                    <div class="grid grid-cols-2 gap-5">
                        @php $chart = $charts['environment']['categories']['energy_intensity']; @endphp

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Energy intensity')]) }}"
                            class="!h-min !shadow-none border border-esg7/40" contentplacement="none">
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
                            text="{{ json_encode([__('energy consumption')]) }}" type="none" class="!h-min !shadow-none border border-esg7/40"
                            subpoint="{{ $subpoint }}" contentplacement="none">
                            @php $chart = $charts['environment']['categories']['energy_consumption']['categories']; @endphp
                            <div class="mb-8">
                                <x-charts.donut id="energy_consumption_baseline"
                                    class="m-auto !h-[180px] !w-[180px] print:!h-[10    0px] print:!w-[10   0px] mt-5" legendes="true" x-init="$nextTick(() => {
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

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Green House Gas Emissions (GHG) ') !!}</p>
                    <div class="grid grid-cols-2 gap-5">
                        @php
                            $subpoint = json_encode([['color' => 'bg-esg2', 'text' => __('Reporting period: 2020')]]);
                            $chart = $charts['environment']['categories']['ghg_emissions_chart']['categories'];
                            $subinfo = json_encode([['value' => formatNumber(array_sum(array_column($chart, 'total'))), 'unit' => 't CO2 eq', 'color' => 'text-[' . color(2) . ']']]);

                            $unit = $charts['environment']['categories']['ghg_emissions_chart']['categories']['scope_1']['indicators'][0]['unit_default'] ?? '';
                        @endphp
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG emissions by category')]) }}" subpoint="{{ $subpoint }}"
                            class="!h-auto !shadow-none border border-esg7/40">
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

                        <div class="grid grid-cols-1 gap-5">
                            <div class="!h-auto !shadow-none border border-esg7/40 p-4 rounded-md">
                                <p class="text-xs font-medium uppercase text-esg16">{!! __('Main sources of scope 1 emissions') !!}</p>
                                <div class="flex justify-between gap-2 mt-4 items-center">
                                    <p class="text-xs text-esg8">{!! __('Biogenic CO2 emissions derived from biomass burning or biodegradation') !!}</p>
                                    <x-cards.cards-value-unit
                                        xclass="font-encodesans font-medium text-4xl text-esg2"
                                        xunitClass="text-base text-esg2"
                                        :value="$charts['report']['categories']['co2_emission']['total']"
                                        :unit="$charts['report']['categories']['co2_emission']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>
                            </div>

                            <div class="!h-auto !shadow-none border border-esg7/40 p-4 rounded-md">
                                <p class="text-xs font-medium uppercase text-esg16">{!! __('Main sources of scope 3 emissions') !!}</p>
                                <div class="flex justify-between gap-2 mt-4 items-center">
                                    <p class="text-xs text-esg8">{!! __('Biogenic CO2 emissions derived from biomass burning or biodegradation throughout the value chain') !!}</p>
                                    <x-cards.cards-value-unit
                                        xclass="font-encodesans font-medium text-4xl text-esg2"
                                        xunitClass="text-base text-esg2"
                                        :value="$charts['report']['categories']['co2_emission1']['total']"
                                        :unit="$charts['report']['categories']['co2_emission1']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium mt-4 uppercase text-esg16">{!! __('GHG emissions removed/stored') !!}</p>
                        <x-report.table.table margin="!mt-1" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['ghg_emission']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-9/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td class="w-3/12 text-right">
                                        <p class="flex items-center gap-1 text-sm text-esg8">
                                            <span>{{ $indicator['value']  ?? 0 }}</span>
                                            <span>{{ $indicator['unit_default'] ?? 't CO2 eq' }}</span>
                                        </p>
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 12  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Water and marine resources') !!}</p>

                    <div class="grid grid-cols-2 gap-5">
                        @php $chart = $charts['environment']['categories']['water_and_marine_resources']['categories']; @endphp

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water consumed')]) }}"
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
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
                            class="!h-auto !shadow-none border border-esg7/40" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$chart['water_treated']['total']" :unit="$chart['water_treated']['indicators'][0]['unit_default'] ?? ''" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.18.save-water', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Water resources management strategy') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['analysis_risk_opportunity']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>


                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Main results of the work developed with the stakeholders resulting from the definition of the water resources management strategy') !!}</p>
                    <p class="text-sm text-esg16">{{ $charts['report']['categories']['work_develop_water_resource']['indicators'][0]['value'] ?? '-' }}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['organization_policy']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Topics covered in the policy, strategy or plan to address water resource impacts') !!}</p>
                    @foreach($charts['report']['categories']['policy_topics_cover']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg2"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['goal_defination']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 13  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('The objectives and targets include the prevention and control of') !!}</p>
                    @foreach($charts['report']['categories']['objective_targets']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg2"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('risks and opportunities related to water resources') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Analysis and risk/opportunity identification') !!}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['risk_identification']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Description of the process used for identification, assessment and management of risks and opportunities') !!}</p>
                    <p class="text-sm">{{ $charts['report']['categories']['process_used_identification']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risk/opportunity management') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['risk_opportunity_management']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Metrics to measure and manage water-related risks and opportunities disclosed') !!}</p>
                    <p class="text-sm">{{ $charts['report']['categories']['metrics_measure_opportunities']['indicators'][0]['value'] ?? '-' }}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['has_capital']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="!h-auto !shadow-none border border-esg7/40 p-4 rounded-md flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium uppercase text-esg16">{!! __('Capital made available (monetary value), i.e. capital expenditure, financing or investment implemented, in response') !!}</p>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg8"
                                    :value="$charts['report']['categories']['capital_made_available']['total']"
                                    :unit="$charts['report']['categories']['capital_made_available']['indicators'][0]['unit_default'] ?? '€'" :isNumber=true />
                            </div>
                            <div>
                                @include('icons.dashboard.8.money-hand', ['color' => color(2)])
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 14  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risk/opportunity effects') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['risk_opportunity_effects']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risks related to water resources with potential to affect the organisation financially or strategically') !!}</p>
                    <p class="text-sm">{{ $charts['report']['categories']['risk_organisation_financially']['indicators'][0]['value'] ?? '-' }}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['opportunities_water_potential_benefit']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Opportunities with the potential to benefit the organisation/business financially or strategically') !!}</p>
                    <p class="text-sm">{{ $charts['report']['categories']['opportunities_potential_benefit']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risk/opportunity policies') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['risk_opportunity_policies']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Highest hierarchical level responsible for the implementation of the policy') !!}</p>

                    @foreach($charts['report']['categories']['body']['indicators'] as $indicator)
                        @if($indicator['indicator_id'] == '4097' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Governing Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4099' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Management Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4100' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Supervisory Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4101' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Other') !!}</p>
                        @endif
                    @endforeach

                </div>
            </x-report.vertical.page>

            {{-- Page 15  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Biodiversity Impact') !!}</p>
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Transition plan') !!}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['transition_plan']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Year of implementation of transition plan for climate change') !!}</p>
                    <x-cards.cards-value-unit
                        xclass="font-encodesans font-medium text-xs text-esg8"
                        xunitClass="text-base text-esg2"
                        :value="$charts['report']['categories']['transition_plan_year']['total']"  />

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['business_development_strategy']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Highest hierarchical level responsible for approving the plan') !!}</p>
                    @foreach($charts['report']['categories']['body_approving_plan']['indicators'] as $indicator)
                        @if($indicator['indicator_id'] == '4115' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Governing Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4116' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Management Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4117' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Supervisory Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4118' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Other') !!}</p>
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Activities performance and oportunities') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['activity_performance']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Opportunities with the potential to benefit the organization/business financially or strategically') !!}</p>
                    @foreach($charts['report']['categories']['opportunities_with_the_potential_benefit']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg2"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach
                </div>
            </x-report.vertical.page>

            {{-- Page 16  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Biodiversity policies') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['biodiversity_policies']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Topics covered in the policy, strategy or plan for addressing impacts on biodiversity and ecosystems') !!}</p>
                    @foreach($charts['report']['categories']['biodiversity_policies_ecosystems']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg2"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Mitigation objectives and action plans') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['mitigation_objectives_action_plans']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 16.1  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Funds and sources to finance the actions defined by the organisation') !!}</p>
                    <p class="text-sm text-esg16">{{ $charts['report']['categories']['funds_finance_action']['indicators'][0]['value'] ?? '-' }}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['operation_protected_area']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('operations in or near protected areas') !!}</p>
                    <div class="">
                        <div class="flex justify-between gap-5 py-2 border-b border-b-esg7/40">
                            <p class="text-xs text-esg8">{!! __('Total number of operations of the organisation') !!}</p>
                            <p class="text-xs text-esg8 w-32 text-right"><span>{{ $charts['report']['categories']['total_number_operation_organisation']['total'] }}</span> <span class="text-xs text-esg16">{!! __('operations') !!}</span></p>
                        </div>

                        <div class="flex justify-between gap-5 py-2 border-b border-b-esg7/40">
                            <p class="text-xs text-esg8">{!! __('Number of operations in or adjacent to protected areas and/or areas rich in biodiversity') !!}</p>
                            <p class="text-xs text-esg8 w-32 text-right"><span>{{ $charts['report']['categories']['operations_adjacent']['total'] }}</span> <span class="text-xs text-esg16">{!! __('operations') !!}</span></p>
                        </div>

                        <div class="flex justify-between gap-5 py-2 border-b border-b-esg7/40">
                            <p class="text-xs text-esg8">{!! __('Number of the organisation`s operations located in sensitive, protected or high biodiversity value areas, outside environmentally protected areas') !!}</p>
                            <p class="text-xs text-esg8 w-32 text-right"><span>{{ $charts['report']['categories']['operations_located_sensitive']['total'] }}</span> <span class="text-xs text-esg16">{!! __('operations') !!}</span></p>
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 16.2  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('land use') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Practices and policies') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['practices_policies']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Topics covered in the policy, strategy or plan for addressing impacts on land use') !!}</p>
                    @foreach($charts['report']['categories']['addressing_impacts_land_use']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg2"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Mitigation objectives and action plans') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['mitigation_objectives_action_plans']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Funds and sources to finance the actions defined by the organisation') !!}</p>
                    <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['funds_sources_finance']['indicators'][0]['value'] ?? '-' }}</p>
                </div>
            </x-report.vertical.page>

            {{-- Page 17 --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Deforestation') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Practices and policies') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['deforestation_policies']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Topics covered in the policy, strategy or plan for addressing impacts on land use') !!}</p>
                    @foreach($charts['report']['categories']['deforestation_topic_covered']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg2"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Mitigation objectives and action plans') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['deforestation_mitigation_objectives_action_plans']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Funds and sources to finance the actions defined by the organisation') !!}</p>
                    <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['deforestation_finance']['indicators'][0]['value'] ?? '-' }}</p>
                </div>
            </x-report.vertical.page>

            {{-- Page 18  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('risks and opportunities related to deforestation') !!}</p>
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Analysis and risk/opportunity identification') !!}</p>
                    <x-report.table.table class="!border-t-esg2" margin="!-mt-3">
                        @foreach($charts['report']['categories']['analysis_risk_opportunity']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Process for identifying, assessing, and managing risks and opportunities related to deforestation') !!}</p>
                    <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['risk_opportunity_deforestation']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risk/opportunity management') !!}</p>
                    <x-report.table.table class="!border-t-esg2" margin="!-mt-3">
                        @foreach($charts['report']['categories']['risk_opportunity_management']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16>{!! __('Risks and opportunities identified') !!}</p>
                    @foreach($charts['report']['categories']['risk_opportunity_identified']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg2"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <x-report.table.table class="!border-t-esg2" margin="!-mt-3">
                        @foreach($charts['report']['categories']['metrics_measure_manage_risks']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Metrics to measure and manage risks and opportunities related to deforestation disclosed by the organisation') !!}</p>
                    <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['metrics_measure_manage_risks_answer']['indicators'][0]['value'] ?? '-' }}</p>

                    <x-report.table.table class="!border-t-esg2" margin="!mt-2">
                        @foreach($charts['report']['categories']['has_capital_available']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <div class="grid grid-cols-2 gap-5 mt-5">
                        <div class="rounded-md border border-esg7/40 p-4 flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium uppercase text-esg16">{!! __('Capital made available (monetary value), i.e. capital expenditure, financing or investment implemented, in response to deforestation-related risks and opportunities.') !!}</p>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg8"
                                    :value="$charts['report']['categories']['has_capital_available_value']['total']"
                                    :unit="$charts['report']['categories']['has_capital_available_value']['indicators'][0]['unit_default'] ?? '€'" :isNumber=true />
                            </div>
                            <div>
                                @include('icons.dashboard.8.money-hand', ['color' => color(2)])
                            </div>
                        </div>
                    </div>

                    <x-report.table.table class="!border-t-esg2" margin="!mt-5">
                        @foreach($charts['report']['categories']['eforestation_related_opportunity']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 19  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Deforestation related opportunities with the potential to affect the organisation/business financially or strategically') !!}</p>
                    <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['eforestation_related_opportunity_value']['indicators'][0]['value'] ?? '-' }}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['oraganization_policy_deforestation']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Highest hierarchical level responsible for the implementation of the policy') !!}</p>
                    @foreach($charts['report']['categories']['body_approving_plan_deforest']['indicators'] as $indicator)
                        @if($indicator['indicator_id'] == '4115' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Governing Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4116' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Management Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4117' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Supervisory Body') !!}</p>
                        @elseif($indicator['indicator_id'] == '4118' && $indicator['value'] == 'yes')
                            <p class="text-xs">{!! __('Other') !!}</p>
                        @endif
                    @endforeach

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Raw-Materials Consumption') !!}</p>

                    <div class="grid grid-cols-2 gap-5 mt-5">
                        <div class="rounded-md border border-esg7/40 p-4 flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium uppercase text-esg16">{!! __('Hazardous Waste') !!}</p>
                                @php $chart = $charts['environment']['categories']['water_and_marine_resources']['categories']; @endphp
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg8"
                                    :value="$chart['hazardous_waste']['total']" :unit="$chart['hazardous_waste']['indicators'][0]['unit_default'] ?? 't'" :isNumber=true />
                            </div>
                            <div>
                                @include('icons.dashboard.trash', ['color' => color(2)])
                            </div>
                        </div>
                    </div>

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('organisation Activities Impact') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['oraganization_activity_impact']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 20  --}}
            <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Specific sectors') !!}</p>
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Activities related to the organisation') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['oraganization_activity_related']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                        <x-report.table.tr>
                            <x-report.table.td class="w-10/12">{!! __('Average value in tons of CO2 per passenger-km') !!}</x-report.table.td>
                            <x-report.table.td>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg16"
                                    :value="$charts['report']['categories']['average_value_tons_CO2']['total']"
                                    :unit="$charts['report']['categories']['average_value_tons_CO2']['indicators'][0]['unit_default'] ?? 't CO2'"
                                    :isNumber=true />
                            </x-report.table.td>
                        </x-report.table.tr>

                        <x-report.table.tr>
                            <x-report.table.td class="w-10/12">{!! __('Average in gCO2/MJ') !!}</x-report.table.td>
                            <x-report.table.td>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg16"
                                    :value="$charts['report']['categories']['average_gCO2']['total']"
                                    :unit="$charts['report']['categories']['average_gCO2']['indicators'][0]['unit_default'] ?? 'gCO2/MJ'"
                                    :isNumber=true />
                            </x-report.table.td>
                        </x-report.table.tr>

                        <x-report.table.tr>
                            <x-report.table.td class="w-10/12">{!! __('Average percentage of high-carbon technologies') !!}</x-report.table.td>
                            <x-report.table.td>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg16"
                                    :value="$charts['report']['categories']['average_percentage']['total']"
                                    :unit="$charts['report']['categories']['average_percentage']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                            </x-report.table.td>
                        </x-report.table.tr>
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Activity description') !!}</p>
                    <p class="text-sm text-esg16">{{ $charts['report']['categories']['activity_description']['indicators'][0]['value'] ?? '-' }}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['oraganisation_automobile_sector']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                        <x-report.table.tr>
                            <x-report.table.td class="w-10/12">{!! __('Average value in tons of CO2 per passenger-km') !!}</x-report.table.td>
                            <x-report.table.td>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg16"
                                    :value="$charts['report']['categories']['avg_value_tons_co2_passenger_km']['total']"
                                    :unit="$charts['report']['categories']['avg_value_tons_co2_passenger_km']['indicators'][0]['unit_default'] ?? 't CO2'"
                                    :isNumber=true />
                            </x-report.table.td>
                        </x-report.table.tr>
                        <x-report.table.tr>
                            <x-report.table.td class="w-10/12">{!! __('Average in gCO2/MJ') !!}</x-report.table.td>
                            <x-report.table.td>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg16"
                                    :value="$charts['report']['categories']['high_carbon']['total']"
                                    :unit="$charts['report']['categories']['high_carbon']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                            </x-report.table.td>
                        </x-report.table.tr>
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Activity description') !!}</p>
                    <p class="text-sm text-esg16">{{ $charts['report']['categories']['oraganisation_automobile_sector_description']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Other activities') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                        @foreach($charts['report']['categories']['other_activity']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(2)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 20.1 :: EXTRA --}}
            {{-- <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <div>
                        <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['oraganisation_automobile_sector']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-lg mt-4">{!! __('Activity description') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['energy_sector_value']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-lg mt-4">{!! __('Average value in tons of CO2 per MWh') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['energy_sector_co2']['total']"
                            :isNumber=true />

                        <p class="text-lg mt-4">{!! __('Average percentage of high-carbon technologies (oil, gas, coal)') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['energy_sector_high_carbon']['total']"
                            :unit="$charts['report']['categories']['energy_sector_high_carbon']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                    </div>

                    <div>
                        <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['oil_sector']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-lg mt-4">{!! __('Activity description') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['oil_sector_value']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-lg mt-4">{!! __('Average value in tons of CO2 per GJ') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['oil_sector_co2']['total']"
                            :isNumber=true />

                        <p class="text-lg mt-4">{!! __('Average percentage of high-carbon technologies') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['oil_sector_high_carbon']['total']"
                            :unit="$charts['report']['categories']['oil_sector_high_carbon']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                    </div>

                    <div>
                        <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['iron_steel_sector']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-lg mt-4">{!! __('Activity description') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['iron_steel_sector_value']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-lg mt-4">{!! __('Average value in tons of CO2 per ton produced') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['iron_steel_sector_co2']['total']"
                            :isNumber=true />

                        <p class="text-lg mt-4">{!! __('Average percentage of high-carbon technologies') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['iron_steel_sector_high_carbon']['total']"
                            :unit="$charts['report']['categories']['iron_steel_sector_high_carbon']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                    </div>
                </div>
            </x-report.vertical.page> --}}

            {{-- Page 20.2 :: EXTRA --}}
            {{-- <x-report.vertical.page title="{{ __('environment') }}">
                <div class="py-5 grid grid-cols-3 gap-5">
                    <div>
                        <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['industry_sector']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-lg mt-4">{!! __('Activity description') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['industry_sector_value']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-lg mt-4">{!! __('Average value in tons of CO2 per GJ') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['industry_sector_co2']['total']"
                            :isNumber=true />

                        <p class="text-lg mt-4">{!! __('Average percentage of high-carbon technologies') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['industry_sector_high_carbon']['total']"
                            :unit="$charts['report']['categories']['industry_sector_high_carbon']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                    </div>

                    <div>
                        <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['production_sector']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-lg mt-4">{!! __('Activity description') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['production_sector_value']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-lg mt-4">{!! __('Average percentage of high-carbon technologies') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['production_sector_co2']['total']"
                            :isNumber=true />

                        <p class="text-lg mt-4">{!! __('Average percentage of high-carbon technologies') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['production_sector_high_carbon']['total']"
                            :unit="$charts['report']['categories']['production_sector_high_carbon']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                    </div>

                    <div>
                        <x-report.table.table margin="!-mt-3" class="!border-t-esg2">
                            @foreach($charts['report']['categories']['aviation_sector']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(2)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-lg mt-4">{!! __('Activity description') !!}</p>
                        <p class="text-sm text-esg16 mt-4">{{ $charts['report']['categories']['aviation_sector_value']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-lg mt-4">{!! __('Average percentage of sustainable jet fuels') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['aviation_sector_co2']['total']"
                            :isNumber=true />

                        <p class="text-lg mt-4">{!! __('Average value in tons of CO2 per passenger-km') !!}</p>
                        <x-cards.cards-value-unit
                            xclass="font-encodesans font-medium text-4xl text-esg2"
                            xunitClass="text-base text-esg2"
                            :value="$charts['report']['categories']['aviation_sector_high_carbon']['total']"
                            :unit="$charts['report']['categories']['aviation_sector_high_carbon']['indicators'][0]['unit_default'] ?? '%'" :isNumber=true />
                    </div>
                </div>
            </x-report.vertical.page> --}}

            {{-- Page 21 --}}
            <x-report.vertical.pagewithimage url="/images/report/esg_simple/vertical/5.png" header="true" border="border-b-esg1">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg1 text-center">{{ __('Social') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('performance') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            @php $social = $charts['report']['categories']['social']['categories']; @endphp
            {{-- Page 22  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Workers of the organisation') !!}</p>

                    <div class="rounded-md border border-esg7/40 p-4 w-6/12 flex justify-between items-center gap-4">
                        <div>
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Total of contracted and subcontracted workers') !!}</p>
                            <x-cards.cards-value-unit
                                xclass="font-encodesans font-medium text-base text-esg8"
                                xunitClass="text-xs text-esg16"
                                :value="$social['total_contracted_subcontracted_workers']['total']"
                                :unit="__('workers')" :isNumber=true />
                        </div>
                        <div>
                            @include('icons.dashboard.9.workers', [ 'color' => color(1) ])
                        </div>
                    </div>

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('contracted WORKERS') !!}</p>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Workers by gender') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                type="flex" class="!h-auto !p-0 !bg-transparent !shadow-none" contentplacement="justify-center">
                                <x-charts.donut id="contracted_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[100px] print:!w-[100px]"
                                    legendes="true" />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Gender distribution of contracted workers that left the organization in the last 12 months') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                type="flex" class="!h-auto !p-0 !bg-transparent !shadow-none" contentplacement="justify-center">
                                <x-charts.donut id="left_worker_in_12_month"
                                    class="m-auto !h-[180px] !w-[180px] print:!h-[100px] print:!w-[100px]"
                                    legendes="true"
                                    x-init="$nextTick(() => {
                                        pieCharts([
                                                '{{ __('Female') }}', '{{ __('Male') }}', '{{ __('Other') }}'
                                            ],
                                            [
                                                {{ $social['percentage_contract_workers_left_ina_year']['categories']['female']['total'] }},
                                                {{ $social['percentage_contract_workers_left_ina_year']['categories']['male']['total'] }},
                                                {{ $social['percentage_contract_workers_left_ina_year']['categories']['other']['total'] }},
                                            ],
                                            'left_worker_in_12_month',
                                            ['#FBB040', '#FF9900', '#FFDDAB']
                                        );
                                    })" />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Minorities and local community') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['local_community']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Hourly Earnings Variation (Total)') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                class="!h-auto !p-0 !bg-transparent !shadow-none">
                                @php
                                    $unit = $charts['social']['categories']['workers']['categories']['hourly_earnings_variation']['indicators'][0]['unit_default'] ?? '';
                                @endphp
                                <x-charts.bar id="hourly_earnings" class="m-auto relative !h-full !w-full"
                                    unit="{{ $unit }}" />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="rounded-md border border-esg7/40 p-4 h-min">
                            <div class="flex justify-between items-center">
                                <div class="">
                                    <p class="text-lg mt-4">{!! __('Gender Pay gap') !!}</p>
                                    <x-cards.cards-value-unit
                                        xclass="font-encodesans font-medium text-base text-esg8"
                                        xunitClass="text-xs text-esg16"
                                        :value="$charts['social']['categories']['workers']['categories']['gender_pay_gap']['total']"
                                        :unit="$charts['social']['categories']['workers']['categories']['gender_pay_gap']['unit'] ?? '%'" />
                                </div>
                                <div>
                                    @include('icons.dashboard.8.money-hand', [ 'color' => color(1) ])
                                </div>
                            </div>
                            <p class="text-xs text-esg16">{!! __('(Average gross earnings per hour (€/h) for men - Average gross earnings per hour (€/h) for women) / Average gross earnings per hour (€/h) for men') !!}</p>
                        </div>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 23  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Lowest wage received by contracted workers by gender') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                type="flex" class="!h-auto !p-0 !bg-transparent !shadow-none" contentplacement="justify-center">
                                <x-charts.bar id="low_wage"
                                    class="m-auto relative !h-full !w-full" x-init="$nextTick(() => {
                                        barCharts([
                                                '{{ $social['low_wage']['categories']['female']['name'] }}',
                                                '{{ $social['low_wage']['categories']['male']['name'] }}',
                                                '{{ $social['low_wage']['categories']['other']['name'] }}',
                                            ],
                                            [
                                                {{ $social['low_wage']['categories']['female']['total'] }},
                                                {{ $social['low_wage']['categories']['male']['total'] }},
                                                {{ $social['low_wage']['categories']['other']['total'] }},
                                            ],
                                            'low_wage',
                                            ['{{ color(1) }}']
                                        );
                                    })"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-esg16">{!! __('Local minimum wage') !!}</p>
                        <x-report.table.table margin="!mt-1" class="!border-t-esg1">
                            @foreach($social['local_minimum_weage']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
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
                        <p class="text-xs font-medium uppercase text-esg16">{!! __('Justification for the existence of contracted workers receiving less than the local minimum wage') !!}</p>
                        <p class="text-sm text-esg16">{{ $social['justification_minimum_weage']['indicators'][0]['value'] ?? '-' }}</p>

                        <p class="text-xs font-medium uppercase text-esg16">{!! __('Occupational safety and health (OSH)') !!}</p>
                        <x-report.table.table margin="!mt-1" class="!border-t-esg1">
                            @foreach($social['osh']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
                                            @include('icons.checkbox', ['color' =>  color(1)])
                                        @else
                                            @include('icons.checkbox-no')
                                        @endif
                                    </x-report.table.td>
                                </x-report.table.tr>
                            @endforeach
                        </x-report.table.table>

                        <p class="text-xs font-medium uppercase text-esg16 mt-5">{!! __('Groups of workers, activities or workplaces not covered by the OSH system and the motives for their exclusion') !!}</p>
                        <p class="text-sm text-esg16">{{ $social['group_worker_osh']['indicators'][0]['value'] ?? '-' }}</p>
                    </div>

                    <div>
                        <x-report.table.table margin="!mt-1" class="!border-t-esg1">
                            @foreach($social['osh_risk']['indicators'] as $indicator)
                                <x-report.table.tr>
                                    <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                    <x-report.table.td>
                                        @if ($indicator['value'] == 'yes')
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
            </x-report.vertical.page>

            {{-- Page 24  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('subcontracted WORKERS') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['subcontracted_workers']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Contracted workers') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow type="flex" class="!h-auto !p-0 !bg-transparent !shadow-none"
                                contentplacement="justify-center">
                                <x-charts.donut id="outsourced_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[100px] print:!w-[100px]"
                                    legendes="true" />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['remuneration_organization']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Subcontracted workers conditions, health and safety') !!}</p>
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Compliance with Occupational Health and Safety (OHS) standards are the responsibility of the reporting organisation') !!}</p>
                    <p class="text-sm text-esg16">
                        @if ($social['outsource_workers']['indicators'][0]['value'] == 'yes')
                            {!! __('No, it is the responsibility of the entity with whom the worker has an employment contract and we request evidence of compliance with OSH standards') !!}
                        @else
                            -
                        @endif
                    </p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Subcontracted workers are covered by social security against loss of income') !!}</p>
                    <p class="text-sm text-esg16">
                        @if ($social['outsource_workers']['indicators'][0]['value'] == 'yes')
                            {!! __('The organisation does not have knowledge') !!}
                        @else
                            -
                        @endif
                    </p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['policies_practices']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 25  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Workers rights') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Collective agreements and representatives') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['collective_agreements']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Agreements celebrated with the labour representatives regarding respect for the human rights of workers') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['agreements_celebrated']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('organisations impacts on their own workers') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Inclusion and affirmative actions') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['affirmative_actions']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Commitments related to inclusion and/or affirmative action for people from groups at particular risk of vulnerability in its own workforce') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['commitments_affirmative']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Promotion of diversity') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['promotion_diversity']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Actions to promote diversity, equity and inclusion') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['action_promote_diversity']['indicators'][0]['value'] ?? '-' }}</p>
                </div>
            </x-report.vertical.page>

            {{-- Page 26  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Communication channels and complaints') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['communication_complaints']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="rounded-md border border-esg7/40 p-4 flex justify-between items-center">
                            <div>
                                <p class="text-xs font-medium uppercase text-esg16">{!! __('Number of incidents of discrimination that have occurred, in the reporting period') !!}</p>
                                <x-cards.cards-value-unit
                                    xclass="font-encodesans font-medium text-base text-esg8"
                                    xunitClass="text-xs text-esg16"
                                    :value="$social['number_incidents']['indicators'][0]['value']"
                                    :unit="__('incidents')" :isNumber=true />
                            </div>
                            <div>
                                @include('icons.dashboard.9.discrimination', [
                                    'color' => color(1),
                                ])
                            </div>
                        </div>
                    </div>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risks and/or opportunities assessment') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['risks_opportunities_assessment']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Negative impacts, risks and/or opportunities on workers identified') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['negative_impacts']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Prevention, mitigation and remediation of impacts') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['prevention_mitigation']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Processes to cooperate with its workers in the prevention, mitigation and remediation of those negative impacts and/or risks that it causes or contributes to and/or, in the case of opportunities, the actions planned to pursue them') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['processes_cooperate']['indicators'][0]['value'] ?? '-' }}</p>
                </div>
            </x-report.vertical.page>

            {{-- Page 27  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risks and impact management') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['organisation_risks_impact']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Topics covered in the policy(ies) for managing impacts, risks and opportunities related to workers') !!}</p>
                    @foreach($social['topic_covered_policy']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg1"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Risks and impact management') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['stackholder_risk']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Implementation procedures to ensure prevention, mitigation and action in cases of discrimination') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['procedures_ensure_prevention']['indicators'][0]['value'] ?? '-' }}</p>
                </div>
            </x-report.vertical.page>

            {{-- Page 27.1  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Migrant workers') !!}</p>
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Migrant workers condition') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['migrant_workers_condition']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Motive for migrant workers being paid less than the local minimum wage') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['motive_migrant_workers']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Communication channels and complaints') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['communication_channels']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 28  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Consumers and End-Users') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Satisfaction and impacts') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['satisfaction_impacts']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Processes to cooperate with consumers and/or end-users to prevent, mitigate and remedy negative impacts it causes or contributes to') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['remedy_negative_impacts']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Banned products or services') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['banned_products_services']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Affected Communities') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Impacts assessment and identification') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['impacts_assessment']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Negative impacts identified on local communities') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['negative_impacts_identified']['indicators'][0]['value'] ?? '-' }}</p>
                </div>
            </x-report.vertical.page>

            {{-- Page 29  --}}
            <x-report.vertical.page title="{{ __('social') }}" >
                <div class="grid grid-cols-1 gap-5 py-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Communication channels and complaints') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['communication_channels_complaints']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Agreement or commitment with representatives of affected communities with regard to respecting the communities human rights') !!}</p>
                    <p class="text-sm text-esg16">{{ $social['representatives']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Policies and investments') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg1">
                        @foreach($social['policies_investments']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(1)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Type of investments made in the community') !!}</p>
                    @foreach($social['investments_made_community']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg1"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach
                </div>
            </x-report.vertical.page>

            {{-- Page 30 --}}
            <x-report.vertical.pagewithimage url="/images/report/esg_simple/vertical/6.png" header="true" border="border-b-esg3">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg3 text-center">{{ __('governance') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase text-center">{{ __('performance') }}</p>
                </div>
            </x-report.vertical.pagewithimage>

            @php $governance = $charts['report']['categories']['governance']['categories']; @endphp
            {{-- Page 31  --}}
            <x-report.vertical.page title="{{ __('governance') }}">
                @php
                    $chart = $charts['governance']['categories']['structure']['categories']['governance_body_structure']['indicators'];
                    $isConstitutedAndStructured = strtolower($chart[0]['value']) == 'yes';
                @endphp
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Structure of the Highest Governance Body') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Constitution and distribution') !!}</p>
                    <p class="text-xs">
                        @if ($isConstitutedAndStructured)
                            @foreach (array_slice($chart, 1) as $indicator)
                                @if (!is_null($indicator['value']))
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
                                @endif
                            @endforeach
                        @else
                            {{ __('Not Constituted and Structured') }}
                        @endif
                    </p>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-lg mt-4">{!! __('Gender distribution') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                class="!h-auto !p-0 !bg-transparent !shadow-none"
                                type="flex" contentplacement="justify-center">
                                <x-charts.donut id="gender_high_governance_body" class=" !h-[180px] !w-[180px] print:!h-[100px] print:!w-[100px]"
                                    legendes="true" />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['high_governance_body']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Role of the chair of the highest governance body in the management of the organisation and the reason for this accumulation of functions') !!}</p>
                    <p class="text-sm text-esg16">{{ $governance['high_governance_body_text']['indicators'][0]['value'] ?? '-' }}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Role and mandate') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['role_mandate']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Duration of each mandate') !!}</p>
                    @foreach($governance['each_mandate']['indicators'] as $indicator)
                        @if ($indicator['indicator_id'] == 4682 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 mt-4 font-bold">{!! __('Less than 3 years') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4683 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 mt-4 font-bold">{!! __('Between 3 and 5 years') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4684 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 mt-4 font-bold">{!! __('More than 5 years') !!}</p>
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Process of members selection') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['members_selection']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 32  --}}
            <x-report.vertical.page title="{{ __('governance') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Criteria used to select and appoint the members of the highest governance body of the organisation') !!}</p>
                    @foreach($governance['criteria_members_selection']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg3"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Frequency of the highest governance body meetings') !!}</p>
                    @foreach($governance['meeting']['indicators'] as $indicator)
                        @if ($indicator['indicator_id'] == 4697 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 font-bold">{!! __('Weekly') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4696 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 font-bold">{!! __('Fortnightly') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4691 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 font-bold">{!! __('Monthly') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4692 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 font-bold">{!! __('Bimonthly') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4693 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 font-bold">{!! __('Quarterly') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4694 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 font-bold">{!! __('Semiannually') !!}</p>
                        @elseif ($indicator['indicator_id'] == 4695 && $indicator['value'] == 'yes')
                            <p class="text-xl text-esg3 font-bold">{!! __('Annually') !!}</p>
                        @endif
                    @endforeach

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['ethics_compliance_esg']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 33  --}}
            <x-report.vertical.page title="{{ __('governance') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Institutional policies') !!}</p>

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['code_conduct_ethics']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Topics included in the code of conduct or ethics or in the organisations policies') !!}</p>
                    @foreach($governance['topic_code_conduct_ethics']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg3"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Policies availability and training') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['policies_availability']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 34  --}}
            <x-report.vertical.page title="{{ __('governance') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Reporting mechanism') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['code_conduct_ethics']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Issues that can be reported through the whistleblowing mechanism') !!}</p>
                    @foreach($governance['whistleblowing']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg3"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Complaints received') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['complaints_received']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Number of complaints filed in the reporting period') !!}</p>
                    <x-cards.cards-value-unit
                        xclass="font-encodesans font-medium text-base text-esg8"
                        xunitClass="text-xs text-esg16"
                        :value="$governance['no_complaints_received']['total']"
                        :unit="__('complaints')" :isNumber=true />
                </div>
            </x-report.vertical.page>

            {{-- Page 34  --}}
            <x-report.vertical.page title="{{ __('governance') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Issues that can be reported through the whistleblowing mechanism') !!}</p>
                    @foreach($governance['whistleblowing_mechanism']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg3"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Complaints mitigation') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['initiatives_changes']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-base font-medium text-esg8 uppercase">{!! __('Corruption and Bribery Prevention') !!}</p>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Policies') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['policies']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Number of members of the highest governance body that have been informed of the policy') !!}</p>
                    <x-cards.cards-value-unit
                        xclass="font-encodesans font-medium text-base text-esg8"
                        xunitClass="text-xs text-esg16"
                        :value="$governance['member_highest_governance']['total']"
                        :unit="__('members')" :isNumber=true />

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Policies availability') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['policies_availability']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page 35  --}}
            <x-report.vertical.page title="{{ __('governance') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Prevention mechanism and investigation') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['mechanism_investigation']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Process for reporting research findings to each of the following bodies') !!}</p>
                    @foreach($governance['reporting_research_bodies']['indicators'] as $indicator)
                        @if ($indicator['indicator_id'] == 5138 && $indicator['value'] == 'yes')
                            <p class="text-xs text-esg16">{!! __('Governing Body') !!}</p>
                        @elseif ($indicator['indicator_id'] == 5138 && $indicator['value'] == 'yes')
                            <p class="text-xs text-esg16">{!! __('Management Body') !!}</p>
                        @elseif ($indicator['indicator_id'] == 5138 && $indicator['value'] == 'yes')
                            <p class="text-xs text-esg16">{!! __('Supervisory Body') !!}</p>
                        @endif
                    @endforeach

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Reporting mechanisms') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['organisation_reporting_mechanism']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <div class="grid grid-cols-2 gap-5">
                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Cases of corruption and/or bribery reported') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                class="!h-auto !p-0 !bg-transparent !shadow-none">
                                <x-charts.bar id="cases_corruption"
                                    class="m-auto relative !h-full !w-full"
                                    x-init="$nextTick(() => {
                                        barCharts([
                                                '{{ __('Corruption') }}', '{{ __('Bribery') }}'
                                            ],
                                            [
                                                {{ $reported_corruption }},
                                                {{ $reported_bribery }}
                                            ],
                                            'cases_corruption',
                                            ['{{ color(3) }}']
                                        );
                                    })"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="rounded-md border border-esg7/40 p-4">
                            <p class="text-xs font-medium uppercase text-esg16">{!! __('Confirmed cases of corruption and/or bribery') !!}</p>
                            <x-cards.card-dashboard-version1-withshadow
                                class="!h-auto !p-0 !bg-transparent !shadow-none">
                                <x-charts.bar id="cases_corruption_bribery"
                                    class="m-auto relative !h-full !w-full"
                                    x-init="$nextTick(() => {
                                        barCharts([
                                                '{{ __('Corruption') }}', '{{ __('Bribery') }}'
                                            ],
                                            [
                                                {{ $confirmed_corruption }},
                                                {{ $confirmed_bribery }}
                                            ],
                                            'cases_corruption_bribery',
                                            ['{{ color(3) }}']
                                        );
                                    })"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Convictions') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['convictions']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('confirmed cases of corruption and/or bribery in which contracts with business partners were terminated or not renewed as a result of violations related to corruption and/or bribery') !!}</p>

                    <div class="flex justify-between items-center border-b border-b-esg7/40 py-4">
                        <p class="text-xs text-esg8">{!! __('Corruption') !!}</p>
                        <p class="text-xs text-esg8 flex items-center gap-1">
                            <x-cards.cards-value-unit
                                xclass="font-encodesans font-medium text-base text-esg8"
                                xunitClass="text-xs text-esg16"
                                :value="$governance['case_contract']['total']"
                                :unit="__('cases')" :isNumber=true />
                        </p>
                    </div>
                    <div class="flex justify-between items-center border-b border-b-esg7/40 py-4">
                        <p class="text-xs text-esg8">{!! __('Bribery') !!}</p>
                        <p class="text-xs text-esg8 flex items-center gap-1">
                            <x-cards.cards-value-unit
                                xclass="font-encodesans font-medium text-base text-esg8"
                                xunitClass="text-xs text-esg16"
                                :value="$governance['case_bribery']['total']"
                                :unit="__(' cases')" :isNumber=true />
                        </p>
                    </div>
                </div>
            </x-report.vertical.page>

            {{-- Page 36  --}}
            <x-report.vertical.page title="{{ __('governance') }}">
                <div class="py-5 grid grid-cols-1 gap-5">
                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Legal proceedings') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['legal_proceedings']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Number of legal proceedings related to corruption and/or bribery have been initiated against the organisation or its workers') !!}</p>
                    <div class="flex justify-between items-center border-b border-b-esg7/40 py-4">
                        <p class="text-xs text-esg8">{!! __('Corruption') !!}</p>
                        <p class="text-xs text-esg8 flex items-center gap-1">
                            <x-cards.cards-value-unit
                                xclass="font-encodesans font-medium text-base text-esg8"
                                xunitClass="text-xs text-esg16"
                                :value="$governance['worker_case_contract']['total']"
                                :unit="__('cases')" :isNumber=true />
                        </p>
                    </div>
                    <div class="flex justify-between items-center border-b border-b-esg7/40 py-4">
                        <p class="text-xs text-esg8">{!! __('Bribery') !!}</p>
                        <p class="text-xs text-esg8 flex items-center gap-1">
                            <x-cards.cards-value-unit
                                xclass="font-encodesans font-medium text-base text-esg8"
                                xunitClass="text-xs text-esg16"
                                :value="$governance['worker_case_bribery']['total']"
                                :unit="__('cases')" :isNumber=true />
                        </p>
                    </div>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Training plans and risk assessment') !!}</p>
                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['training_risk_assessment']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>

                    <p class="text-xs font-medium uppercase text-esg16">{!! __('Types of risks identified in the corruption and/or bribery assessment') !!}</p>
                    @foreach($governance['risk_type']['indicators'] as $indicator)
                        @if ($indicator['value'] == 'yes')
                            <x-list.bullet
                                class="mt-4"
                                bgcolor="bg-esg3"
                                title="{{ $indicator['indicator_name'] }}" />
                        @endif
                    @endforeach

                    <x-report.table.table margin="!-mt-3" class="!border-t-esg3">
                        @foreach($governance['corruption_bribery']['indicators'] as $indicator)
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ $indicator['indicator_name'] }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($indicator['value']  == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(3)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>
                        @endforeach
                    </x-report.table.table>
                </div>
            </x-report.vertical.page>

            {{-- Page Last--}}
            <x-report.vertical.page lastpage="true" noheader="true" nofooter="true">
                <div class="h-screen">
                    <img src="/images/report/screen//vertical/7.png" class="w-full"/>
                </div>
            </x-report.vertical.page>
        </div>
    </div>
@endsection
