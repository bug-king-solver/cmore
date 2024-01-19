@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' => (tenant()->id == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? 'bg-esg4' : 'bg-esg6'])

@php
$icon_color = (tenant()->id == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? '8' : '27';
$badge_color = (tenant()->id == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? '8' : '8';
$text_color = (tenant()->id == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? 'text-esg8' : 'text-esg27';
$categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
$genderIconUrl = global_asset('images/icons/genders/{id}.svg');
@endphp
@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {

            var colorid = '{!! tenant()->id !!}',
                color_code = (colorid == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? twConfig.theme.colors.esg8 : twConfig.theme.colors.esg27;


            var options = {
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        //text: '{{ __('Gender equality') }}',
                        font: {
                            family: twConfig.theme.fontFamily.encodesans,
                            size: 18,
                            weight: twConfig.theme.fontWeight.bold,
                        },
                        padding: {
                            bottom: 30
                        },
                        color: color_code
                    },
                },
                cutout: '70%',
                animation: {
                    duration: 0
                }
            };

            var plugins = [{
                afterInit: function(chart, args, options) {
                    const chartId = chart.canvas.id;
                    const legendId = `${chartId}-legend`;
                    let html = '';

                    chart.data.datasets[0].data.forEach((data, i) => {
                        let iconUrl = '{{ $genderIconUrl }}';

                        let labelText = chart.data.labels[i];
                        let value = data;
                        let backgroundColor = chart.data.datasets[0].backgroundColor[i];

                        switch (labelText.toLowerCase()) {
                            case '{{ __('male') }}':
                                gender = 'male';
                                break;
                            case '{{ __('female') }}':
                                gender = 'female';
                                break;
                            case '{{ __('other') }}':
                                gender = 'other';
                                break;
                        }

                        html += `
                            <div class="grid w-full grid-cols-3">
                                <div class="col-span-2 flex items-center">
                                    <div class="mr-4 inline-block rounded-full p-2 text-left bg-[${backgroundColor}]">
                                        <img src="${iconUrl.replace('{id}', gender)}" class="h-5 w-5">
                                    </div>
                                    <div class="inline-block {{ $text_color }}">${labelText}</div>
                                </div>
                                <div class="text-right font-bold leading-10 {{ $text_color }}">${value}%</div>
                            </div>
                        `;
                    })

                    document.getElementById(legendId).innerHTML = html;
                }
            }];

            @if ($charts['gender_executives'])
                let configGenderEquilityEmployees = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['gender_executives']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['gender_executives']['series']) !!},
                            backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22,
                                twConfig.theme.colors.esg21
                            ],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("gender_executives_chart"), configGenderEquilityEmployees);
            @endif

            @if ($charts['gender_high_governance_body'])
                let configGenderEquilityManagement = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['gender_high_governance_body']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['gender_high_governance_body']['series']) !!},
                            backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22,
                                twConfig.theme.colors.esg21
                            ],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("gender_high_governance_body_chart"), configGenderEquilityManagement);
            @endif

            @if ($charts['gender_collaborators'])
                let configGenderCLevel = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['gender_collaborators']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['gender_collaborators']['series']) !!},
                            backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22,
                                twConfig.theme.colors.esg21
                            ],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("gender_collaborators_chart"), configGenderCLevel);
            @endif

            @if ($charts['gender_minimum_wage'])
                let configGenderMinWage = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['gender_minimum_wage']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['gender_minimum_wage']['series']) !!},
                            backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22,
                                twConfig.theme.colors.esg21
                            ],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("gender_minimum_wage_chart"), configGenderMinWage);
            @endif

            @if($charts['leadership_genre'])
                var options = {
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: true,
                            //text: '{{ __('Gender equality') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            padding: {
                                bottom: 30
                            },
                            color: color_code
                        },
                    },
                    cutout: '70%',
                    animation: {
                        duration: 0
                    }
                };

                var plugins = [{
                    afterInit: function(chart, args, options) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '';

                        chart.data.datasets[0].data.forEach((data, i) => {
                            let iconUrl = '{{ $genderIconUrl }}';

                            let labelText = chart.data.labels[i];
                            let value = data;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];

                            switch (labelText.toLowerCase()) {
                                case '{{ __('male') }}':
                                    gender = 'male';
                                    break;
                                case '{{ __('female') }}':
                                    gender = 'female';
                                    break;
                                case '{{ __('other') }}':
                                    gender = 'other';
                                    break;
                            }

                            html += `
                                <div class="grid w-full grid-cols-3">
                                    <div class="col-span-2 flex items-center">
                                        <div class="mr-4 inline-block rounded-full p-2 text-left bg-[${backgroundColor}]">
                                            <img src="${iconUrl.replace('{id}', gender)}" class="h-5 w-5">
                                        </div>
                                        <div class="inline-block {{ $text_color }}">${labelText}</div>
                                    </div>
                                    <div class="text-right font-bold leading-10 {{ $text_color }}">${value}%</div>
                                </div>
                            `;
                        })

                        document.getElementById(legendId).innerHTML = html;
                    }
                }];

                let earningConfigGenderTotal = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['leadership_genre']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['leadership_genre']['series']) !!},
                            backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22,
                                twConfig.theme.colors.esg21
                            ],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("leadership_genre_chart"), earningConfigGenderTotal);
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
                            display: true,
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

        });
    </script>
@endpush

@section('content')

    <div class="px-4 lg:px-0">
        <div class="max-w-7xl mx-auto">
            <div class="mt-5">
                <div class="text-center">
                    <h1 class="font-encodesans text-5xl font-bold leading-10 {{ $text_color }} pb-10 mt-24">{{__('Achievements')}}</h1>
                    <p class="font-encodesans text-2xl font-bold leading-10 {{ $text_color }} mb-16">{{__('Your journey to sustainability starts with small actions!')}}</p>
                </div>
                <div class="lg:px-32">
                    <div class="grid grid-cols-2 self-center sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 lg:pb-10">
                        <div class="mb-4" title="{{ __('Login to ESG Maturity') }}">
                            @include('icons.badges.badge', $charts['badges'][1] ? ['color' => color('esg5')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-[4.9rem] ml-7 absolute">
                                @include('icons.badges.1', ! $charts['badges'][1] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Questionnaire submitted') }}">
                            @include('icons.badges.badge', $charts['badges'][2] ? ['color' => color('esg5')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-7 absolute">
                                @include('icons.badges.2', ! $charts['badges'][2] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Complaint Mechanisms') }}">
                            @include('icons.badges.badge', $charts['badges'][3] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-[5.2rem] ml-6 absolute">
                                @include('icons.badges.3', ! $charts['badges'][3] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Master of politics') }}">
                            @include('icons.badges.badge', $charts['badges'][4] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-6 absolute">
                                @include('icons.badges.4', ! $charts['badges'][4] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Master of reports') }}">
                            @include('icons.badges.badge', $charts['badges'][5] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-7 absolute">
                                @include('icons.badges.5', ! $charts['badges'][5] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Environmentally conscious') }}">
                            @include('icons.badges.badge', $charts['badges'][6] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-[1.7rem] absolute">
                                @include('icons.badges.6', ! $charts['badges'][6] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Social responsability') }}">
                            @include('icons.badges.badge', $charts['badges'][7] ? ['color' => color('esg1')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-[5.1rem] ml-[1.6rem] absolute">
                                @include('icons.badges.7', ! $charts['badges'][7] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Customer oriented') }}">
                            @include('icons.badges.badge', $charts['badges'][8] ? ['color' => color('esg1')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-[5.1rem] ml-6 absolute">
                                @include('icons.badges.8', ! $charts['badges'][8] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Gender equality') }}">
                            @include('icons.badges.badge' , $charts['badges'][9] ? ['color' => color('esg1')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-7 absolute">
                                @include('icons.badges.9', ! $charts['badges'][9] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Select your suppliers') }}">
                            @include('icons.badges.badge', $charts['badges'][10] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-[1.7rem] absolute">
                                @include('icons.badges.10', ! $charts['badges'][10] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Measure and monitor your environmental journey') }}">
                            @include('icons.badges.badge', $charts['badges'][11] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-[1.6rem] absolute">
                                @include('icons.badges.11', ! $charts['badges'][11] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Attentive to the SDGs') }}">
                            @include('icons.badges.badge', $charts['badges'][12] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-[5.1rem] ml-[1.62rem] absolute">
                                @include('icons.badges.12', ! $charts['badges'][12] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Committed to your carbon footprint') }}">
                            @include('icons.badges.badge', $charts['badges'][13] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-6 absolute">
                                @include('icons.badges.13', ! $charts['badges'][13] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Committed to your energy efficiency') }}">
                            @include('icons.badges.badge', $charts['badges'][14] ? ['color' => color('esg2')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-20 ml-6 absolute">
                                @include('icons.badges.14', ! $charts['badges'][14] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Signatory / Adherence to global principles') }}">
                            @include('icons.badges.badge', $charts['badges'][15] ? ['color' => color('esg3')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-[5.1rem] ml-[1.6rem] absolute">
                                @include('icons.badges.15', ! $charts['badges'][15] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                        <div class="mb-4" title="{{ __('Evidence') }}">
                            @include('icons.badges.badge', $charts['badges'][16] ? ['color' => color('esg5')] : ['color'=>'#BFBFBF', 'bgcolor' => '#C6C6C6'])
                            <div class="-mt-[5.1rem] ml-[1.6rem] absolute">
                                @include('icons.badges.16', ! $charts['badges'][16] ? ['color'=>'#AFAFAF'] : ['color'=> color($badge_color)])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2">
                    @if ($charts['action_plan'])
                        <div x-data="{showExtraLegend: false}" class="md:col-span-1 lg:p-5 xl:p-10 ">
                            <div @mouseover="showExtraLegend = true" @mouseover.away="showExtraLegend = false"  class="relative w-full">
                                <div class="h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px]">
                                    <div></div>
                                    <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                    <div class="{{ $text_color }} absolute left-[31px] top-[45px] rotate-90 text-4xl">
                                        @include('icons.arrow', ['class' => 'rotate-180', 'fill' => color($icon_color)])
                                    </div>
                                    <div
                                        class="{{ $text_color }} absolute left-[310px] top-[300px] sm:left-[465px] sm:top-[448px] text-4xl">
                                        @include('icons.arrow', ['fill' => color($icon_color)])
                                    </div>
                                    <div x-show="showExtraLegend" class="absolute left-[50px] top-[60px] text-sm text-esg9">{{ __('Highly Recommended') }}</div>
                                    <div x-show="showExtraLegend" class="absolute left-[50px] bottom-[60px] text-sm text-esg9">{{ __('Recommended') }}</div>
                                    <div x-show="showExtraLegend" class="absolute right-[60px] top-[60px] text-sm text-esg9">{{ __('High Criticality') }}</div>
                                    <div x-show="showExtraLegend" class="absolute right-[60px] bottom-[60px] text-sm text-esg9">{{ __('High Priority') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($charts['action_plan_table'])
                        <div id="action_list" class="md:col-span-1 lg:p-5 xl:p-10 mt-10 lg:mt-0">
                            <h2 class="{{ $text_color }} pb-5 text-lg font-bold">{{ __('Action Plan') }}</h2>
                            <table class="{{ $text_color }} font-encodesans w-full table-auto">
                                <thead class="border-esg5 border-b-2">
                                    <tr class="text-left text-sm font-medium uppercase">
                                        <th class="p-2">#</th>
                                        <th class="p-2">@include('icons.category')</th>
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
                    @endif

                    <div class="col-span-2  grid grid-cols-1 md:grid-cols-4  pt-5">
                        @foreach ($charts['co2_emissions'] as $emission)
                            <div class="col-span-1 mt-20">
                                <div class="{{ $text_color }} font-encodesans flex items-center pb-5 text-lg font-bold">
                                    <span>@include('icons.categories.1')</span>
                                    <span class="pl-2">{{ $emission['name'] }}</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    {{ $emission['value'] ?? '-' }}
                                </div>
                            </div>
                        @endforeach

                        @if ($charts['employees_salary'])
                            <div class="col-span-1 mt-20">
                                <div class="{{ $text_color }} font-encodesans flex items-center pb-5 text-lg font-bold">
                                    <span>@include('icons.user')</span>
                                    <span class="pl-2">{{ __('Max vs Min Salaries') }}</span>
                                </div>
                                <div class="text-esg24 font-encodesans text-5xl font-bold">
                                    {{ $charts['employees_salary'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 lg:mt-20">
                    @php
                        if ($charts['gender_collaborators']) {
                            $visible = 'gender_collaborators';
                        } elseif ($charts['gender_minimum_wage']) {
                            $visible = 'gender_minimum_wage';
                        } elseif ($charts['leadership_genre']) {
                            $visible = 'leadership_genre';
                        } elseif ($charts['gender_executives']) {
                            $visible = 'gender_executives';
                        } elseif ($charts['gender_high_governance_body']) {
                            $visible = 'gender_high_governance_body';
                        } else {
                            $visible = 'gender_collaborators';
                        }
                    @endphp
                    <div class="relative" x-data="{ visible: '{{ $visible }}' }">
                        @if ($charts['gender_high_governance_body'] || $charts['gender_executives'] || $charts['leadership_genre'] || $charts['gender_collaborators'] || $charts['gender_minimum_wage'])
                            <div class="grid grid-cols-3 {{ $text_color }} text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 sm:w-72 justify-between text-sm">
                                @if ($charts['gender_collaborators'])<button class="mb-2.5" :class="{'text-esg28': visible === 'gender_collaborators'}" @click="visible = 'gender_collaborators'">{{ __('Employees') }}</button>@endif
                                @if ($charts['gender_minimum_wage'])<button class="mb-2.5" :class="{'text-esg28': visible === 'gender_minimum_wage'}" @click="visible = 'gender_minimum_wage'">{{ __('Min. Wage') }}</button>@endif
                                @if ($charts['leadership_genre'])<button class="mb-2.5" :class="{'text-esg28': visible === 'leadership_genre'}" @click="visible = 'leadership_genre'">{{ __('Leadership') }}</button>@endif
                                @if ($charts['gender_executives'])<button class="mb-2.5" :class="{'text-esg28': visible === 'gender_executives'}" @click="visible = 'gender_executives'">{{ __('Executives') }}</button>@endif
                                @if ($charts['gender_high_governance_body'])<button class="mb-2.5" :class="{'text-esg28': visible === 'gender_high_governance_body'}" @click="visible = 'gender_high_governance_body'">{{ __('High Gov.') }}</button>@endif
                            </div>

                            @if ($charts['gender_high_governance_body'])
                            <div id="gender_high_governance_body_block" x-show="visible == 'gender_high_governance_body'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                <div class="relative col-span-2">
                                    <div class="text-lg {{ $text_color }} font-bold font-encodesans">
                                        <span class="inline-block pr-3"> @include('icons.user') </span>
                                        {{__('Highest Governance Body by Gender')}}
                                    </div>

                                    <div
                                        class="{{ $text_color }} text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                    </div>

                                    <div id="gender_high_governance_body" class="grid grid-cols-1 sm:grid-cols-2">
                                        <div> <canvas id="gender_high_governance_body_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                        <div id="gender_high_governance_body_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if ($charts['gender_executives'])
                            <div id="gender_executives_block" x-show="visible == 'gender_executives'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                <div class="relative col-span-2">
                                    <div class="text-lg {{ $text_color }} font-bold font-encodesans">
                                        <span class="inline-block pr-3"> @include('icons.user') </span>
                                        {{__('Executives by Gender')}}
                                    </div>
                                    <div
                                        class="{{ $text_color }} text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                    </div>

                                    <div id="gender_executives" class="grid grid-cols-1 sm:grid-cols-2">
                                        <div> <canvas id="gender_executives_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                        <div id="gender_executives_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if ($charts['leadership_genre'])
                            <div id="leadership_genre_block" x-show="visible == 'leadership_genre'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                <div class="relative col-span-2">
                                    <div class="text-lg {{ $text_color }} font-bold font-encodesans">
                                        <span class="inline-block pr-3"> @include('icons.user') </span>
                                        {{__('Leadership genre')}}
                                    </div>

                                    <div
                                        class="{{ $text_color }} text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                    </div>

                                    <div id="leadership_genre" class="grid grid-cols-1 sm:grid-cols-2">
                                        <div> <canvas id="leadership_genre_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                        <div id="leadership_genre_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div id="gender_collaborators_block" x-show="visible == 'gender_collaborators'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                <div class="relative col-span-2">
                                    @if ($charts['gender_collaborators'])
                                        <div class="text-lg {{ $text_color }} font-bold font-encodesans">
                                            <span class="inline-block pr-3"> @include('icons.user') </span>
                                            {{__('Employees by Gender')}}
                                        </div>

                                        <div
                                            class="{{ $text_color }} text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                        </div>

                                        <div id="gender_collaborators" x-show="visible == 'gender_collaborators'" class="grid grid-cols-1 sm:grid-cols-2">
                                            <div> <canvas id="gender_collaborators_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                            <div id="gender_collaborators_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($charts['gender_minimum_wage'])
                            <div id="gender_minimum_wage_block" x-show="visible == 'gender_minimum_wage'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                <div class="relative col-span-2">
                                    <div class="text-lg {{ $text_color }} font-bold font-encodesans">
                                        <span class="inline-block pr-3"> @include('icons.user') </span>
                                        {{__('Minimum Wage by Gender')}}
                                    </div>

                                    <div
                                        class="{{ $text_color }} text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                    </div>

                                    <div id="gender_minimum_wage" class="grid grid-cols-1 sm:grid-cols-2">
                                        <div> <canvas id="gender_minimum_wage_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                        <div id="gender_minimum_wage_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @else
                            <div class="relative">
                                <div class="text-lg {{ $text_color }} pb-5 font-bold font-encodesans">
                                    <span class="inline-block pr-3"> @include('icons.user') </span>
                                    {{__('Gender Equality')}}
                                </div>

                                <img src="{{ global_asset('images/charts/doughnut.png') }}" alt="">

                                <div class="absolute p-3 top-[125px] left-[125px] bg-esg4 text-esg29 rounded-lg"> {{ __('No data available') }}</div>
                            </div>
                        @endif
                    </div>

                    <div id="ods" class="sm:col-span-1 lg:p-5 xl:p-10 xl:pt-0">
                        <div class="{{ $text_color }} font-encodesans pb-5 text-lg text-center font-bold">
                            {{ __('Sustainable Development Goals (SDGs) | Top 5') }}
                        </div>

                        <div class="flex flex-wrap justify-center">
                            <div class="relative w-[350px] h-[350px] ">
                                <div class="absolute w-full h-full flex items-center justify-center">
                                    <img class="w-40"  src="{{ global_asset('images/icons/sdgs/sustainable-development-goals.png') }}">
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#e3253e] clip-path-no-poverty"
                                    title="{{ $sdgs->find(1)->name }}">
                                    @if (!in_array(1, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[11.1rem] top-[0.75rem] rotate-[13deg]">
                                        @include('icons.sdgs.1')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#dda83a] clip-path-zero-hunger "
                                    title="{{ $sdgs->find(2)->name }}">
                                    @if (!in_array(2, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[14.2rem] top-[1.75rem] rotate-[34deg]">
                                        @include('icons.sdgs.2')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#4c9f38] clip-path-good-health"
                                    title="{{ $sdgs->find(3)->name }}">
                                    @if (!in_array(3, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[16.6rem] top-[4.15rem] rotate-[56deg]">
                                        @include('icons.sdgs.3')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#c5192d] clip-path-quality-education"
                                    title="{{ $sdgs->find(4)->name }}">
                                    @if (!in_array(4, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[18.2rem] top-[7rem] rotate-[75deg]">
                                        @include('icons.sdgs.4')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#ff3a21] clip-path-gender-equality"
                                    title="{{ $sdgs->find(5)->name }}">
                                    @if (!in_array(5, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[18.4rem] top-[10.5rem] rotate-[95deg]">
                                        @include('icons.sdgs.5')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#26bde2] clip-path-clean-water"
                                    title="{{ $sdgs->find(6)->name }}">
                                    @if (!in_array(6, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[17.5rem] top-[13.4rem] rotate-[118deg]">
                                        @include('icons.sdgs.6')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#fcc30b] clip-path-clean-energy"
                                    title="{{ $sdgs->find(7)->name }}">
                                    @if (!in_array(7, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[15.4rem] top-[16.2rem] rotate-[139deg]">
                                        @include('icons.sdgs.7')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#a21942] clip-path-decent-work"
                                    title="{{ $sdgs->find(8)->name }}">
                                    @if (!in_array(8, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[12.6rem] top-[17.8rem] rotate-[155deg]">
                                        @include('icons.sdgs.8')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#fd6925] clip-path-industry"
                                    title="{{ $sdgs->find(9)->name }}">
                                    @if (!in_array(9, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[9.5rem] top-[18.5rem] rotate-[179deg]">
                                        @include('icons.sdgs.9')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#dd1367] clip-path-reduced-inequalities"
                                    title="{{ $sdgs->find(10)->name }}">
                                    @if (!in_array(10, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[6.1rem] top-[17.8rem] rotate-[202deg]">
                                        @include('icons.sdgs.10')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#fd9d24] clip-path-sustainable-cities"
                                    title="{{ $sdgs->find(11)->name }}">
                                    @if (!in_array(11, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[3.3rem] top-[16.1rem] rotate-[222deg]">
                                        @include('icons.sdgs.11')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#bf8b2e] clip-path-responsible-consumption"
                                    title="{{ $sdgs->find(12)->name }}">
                                    @if (!in_array(12, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[1.3rem] top-[13.3rem] rotate-[244deg]">
                                        @include('icons.sdgs.12')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#3f7e44] clip-path-climate-action"
                                    title="{{ $sdgs->find(13)->name }}">
                                    @if (!in_array(13, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[.5rem] top-[10.2rem] rotate-[264deg]">
                                        @include('icons.sdgs.13')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#0a97d9] clip-path-life-water"
                                    title="{{ $sdgs->find(14)->name }}">
                                    @if (!in_array(14, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[0.75rem] top-[6.8rem] rotate-[287deg]">
                                        @include('icons.sdgs.14')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#56c02b] clip-path-life-land"
                                    title="{{ $sdgs->find(15)->name }}">
                                    @if (!in_array(15, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[2.3rem] top-[3.9rem] rotate-[307deg]">
                                        @include('icons.sdgs.15')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#00689d] clip-path-peace-justice"
                                    title="{{ $sdgs->find(16)->name }}">
                                    @if (!in_array(16, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[4.9rem] top-[1.8rem] rotate-[328deg]">
                                        @include('icons.sdgs.16')
                                    </div>
                                </div>

                                <div class="absolute w-[350px] h-[350px] bg-[#19486a] clip-path-partnerships-goals"
                                    title="{{ $sdgs->find(17)->name }}">
                                    @if (!in_array(17, $charts['sdgs_top5']))
                                        <div class="absolute z-10 h-full w-full bg-black opacity-50"></div>
                                    @endif
                                    <div class="absolute w-12 h-12 left-[7.8rem] top-[0.75rem] rotate-[350deg]">
                                        @include('icons.sdgs.17')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="font-encodesans text-center text-4xl {{ $text_color }} font-bold mt-10 mb-20">
                {{__('Document Checklist')}}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-website" type="checkbox" disabled {{ $charts['checkboxs']['website'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-website" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Website')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-policy" type="checkbox" disabled {{ $charts['checkboxs']['supplier_policy'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-policy" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Supplier Policy')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-pa" type="checkbox" disabled {{ $charts['checkboxs']['institutional'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-pa" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Institutional Presentation')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-ethics" type="checkbox" disabled {{ $charts['checkboxs']['code_of_ethics'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-ethics" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Code of Ethics and Supplier Conduct')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-environmental" disabled type="checkbox" {{ $charts['checkboxs']['environmental_policy'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-environmental" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Environmental Policy')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-safety" type="checkbox" disabled {{ $charts['checkboxs']['health_and_safety_policy'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-safety" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Occupational Health and Safety Policy')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-corruption" type="checkbox" disabled {{ $charts['checkboxs']['corruption_risk'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-corruption" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Corruption Risk Prevention Plan')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-customer" type="checkbox" disabled {{ $charts['checkboxs']['customer_policy'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-customer" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Customer Privacy Policy')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-policy" type="checkbox" disabled {{ $charts['checkboxs']['policy_to_prevent'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-policy" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Policy to prevent and deal with situations of conflict of interest')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-ethics-conduct" type="checkbox" disabled {{ $charts['checkboxs']['code_of_ethics_conduct'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-ethics-conduct" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Code of Ethics and Conduct')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-emissions" type="checkbox" disabled {{ $charts['checkboxs']['reducing_emissions'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-emissions" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Policy for reducing emissions')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-mechanisms" type="checkbox" disabled {{ $charts['checkboxs']['complaint_mechanisms'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-mechanisms" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Complaint Mechanisms')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-energy-consumption" type="checkbox" disabled {{ $charts['checkboxs']['energy_consumption'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-energy-consumption" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Policy to reduce energy consumption')}}</label>
                </div>

                <div class="flex items-center mr-4 mb-11">
                    <input id="checkbox-energy-consumption" type="checkbox" disabled {{ $charts['checkboxs']['remuneration_policy'] ? 'checked' : '' }} class="w-8 h-8 text-esg28 bg-esg4 rounded-[100%] border-esg5 focus:ring-esg5">
                    <label for="checkbox-energy-consumption" class="ml-4 font-encodesans text-xl font-bold {{ $text_color }}">{{__('Remuneration Policy')}}</label>
                </div>
            </div>

            @if (strtolower(tenant()->name) === 'demo')
                <div class="mt-12 pb-12">
                    <p class="{{ $text_color }} font-encodesans text-center text-2xl font-bold">
                        {{ __('If you want to continue your journey with us, you will have access to more than 100 charts to complete your dashboard.') }}
                    </p>
                    <p class="text-esg28 font-encodesans text-center text-2xl font-bold">
                        {{ __('Because at C-MORE we translate sustainability into business. Do we count on you?') }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection
