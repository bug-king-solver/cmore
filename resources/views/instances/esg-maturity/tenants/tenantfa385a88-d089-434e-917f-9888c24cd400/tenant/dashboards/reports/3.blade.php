@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' => 'bg-esg15'])

@php
    $categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
    $genderIconUrl = global_asset('images/icons/genders/{id}.svg');
@endphp
@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {

            @if ($charts['gender_total'])

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
                            color: twConfig.theme.colors.esg27
                        },
                    },
                    cutout: '80%',
                    animation: {
                        duration: 0
                    }
                };

                var plugins = [{
                    afterInit: function(chart, args, options) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '',
                            gender = '';

                        chart.data.datasets[0].data.forEach((data, i) => {
                            let iconUrl = '{{ $genderIconUrl }}';

                            let labelText = chart.data.labels[i];
                            let value = data;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];
                            let total = parseInt('{{ $charts['gender_total']['total'] }}');
                            let percentage = Math.round((value * 100) / total);

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

                            labelText = labelText[0].toUpperCase() + labelText.substring(1);

                            html += `
                                <div class="grid w-full grid-cols-3">
                                    <div class="col-span-2 flex items-center">
                                        <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}">
                                            <img src="${iconUrl.replace('{id}', gender)}" class="h-5 w-5">
                                        </div>
                                        <div class="inline-block text-esg8">${labelText}</div>
                                    </div>
                                    <div class="text-right font-bold leading-10 text-esg8 w-32"><span style="color: ${backgroundColor}">${percentage}%</span> (${value})</div>
                                </div>
                            `;
                        })

                        document.getElementById(legendId).innerHTML = html;
                    },
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
                        ctx.font = "bold 18px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "Total";
                        ctx.fillText(text, width / 2, height / 2 + 15);


                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 24px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = "{{ $charts['gender_total']['total'] }}";
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 18px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "colaboradores";
                        ctx.fillText(text, width / 2, height / 2 + top + 30);
                    }
                }];

                let configGenderTotal = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['gender_total']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['gender_total']['series']) !!},
                            backgroundColor: ['#E8621F', '#458D88', '#774294'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("gender_total_chart"), configGenderTotal);
            @endif

            @if ($charts['gender_collaborators'])
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
                            color: twConfig.theme.colors.esg27
                        },
                    },
                    cutout: '80%',
                    animation: {
                        duration: 0
                    }
                };

                var plugins = [{
                    afterInit: function(chart, args, options) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '',
                            gender = '';

                        chart.data.datasets[0].data.forEach((data, i) => {
                            let iconUrl = '{{ $genderIconUrl }}';

                            let labelText = chart.data.labels[i];
                            let value = data;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];
                            let total = parseInt(
                                '{{ $charts['gender_collaborators']['total'] }}');
                            let percentage = Math.round((value * 100) / total);

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

                            labelText = labelText[0].toUpperCase() + labelText.substring(1);

                            html += `
                                <div class="grid w-full grid-cols-3">
                                    <div class="col-span-2 flex items-center">
                                        <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}">
                                            <img src="${iconUrl.replace('{id}', gender)}" class="h-5 w-5">
                                        </div>
                                        <div class="inline-block text-esg8">${labelText}</div>
                                    </div>
                                    <div class="text-right font-bold leading-10 text-esg8 w-32"><span style="color: ${backgroundColor}">${percentage}%</span> (${value})</div>
                                </div>
                            `;
                        })

                        document.getElementById(legendId).innerHTML = html;
                    },
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
                        ctx.font = "bold 18px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "Total";
                        ctx.fillText(text, width / 2, height / 2 + 15);


                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 24px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = "{{ $charts['gender_collaborators']['total'] }}";
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 18px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "colaboradores";
                        ctx.fillText(text, width / 2, height / 2 + top + 30);
                    }
                }];
                let configGenderCollaborators = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['gender_collaborators']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['gender_collaborators']['series']) !!},
                            backgroundColor: ['#E8621F', '#458D88', '#774294'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("gender_collaborators_chart"), configGenderCollaborators);
            @endif

            @if ($charts['gender_top_management'])
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
                            color: twConfig.theme.colors.esg8
                        },
                    },
                    cutout: '80%',
                    animation: {
                        duration: 0
                    }
                };

                var plugins = [{
                    afterInit: function(chart, args, options) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '',
                            gender = '';

                        chart.data.datasets[0].data.forEach((data, i) => {
                            let iconUrl = '{{ $genderIconUrl }}';

                            let labelText = chart.data.labels[i];
                            let value = data;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];
                            let total = parseInt(
                                '{{ $charts['gender_top_management']['total'] }}');
                            let percentage = Math.round((value * 100) / total);

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

                            labelText = labelText[0].toUpperCase() + labelText.substring(1);

                            html += `
                                <div class="grid w-full grid-cols-3">
                                    <div class="col-span-2 flex items-center">
                                        <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}">
                                            <img src="${iconUrl.replace('{id}', gender)}" class="h-5 w-5">
                                        </div>
                                        <div class="inline-block text-esg8">${labelText}</div>
                                    </div>
                                    <div class="text-right font-bold leading-10 text-esg8 w-32"><span style="color: ${backgroundColor}">${percentage}%</span> (${value})</div>
                                </div>
                            `;
                        })

                        document.getElementById(legendId).innerHTML = html;
                    },
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
                        ctx.font = "bold 18px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "Total";
                        ctx.fillText(text, width / 2, height / 2 + 15);


                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 24px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = "{{ $charts['gender_top_management']['total'] }}";
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 18px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "colaboradores";
                        ctx.fillText(text, width / 2, height / 2 + top + 30);
                    }
                }];

                let genderTopManagement = {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($charts['gender_top_management']['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($charts['gender_top_management']['series']) !!},
                            backgroundColor: ['#E8621F', '#458D88', '#774294'],
                            borderRadius: 8,
                            borderWidth: 0,
                            spacing: 0,
                            hoverOffset: 1
                        }]
                    },
                    options,
                    plugins
                };

                new Chart(document.getElementById("gender_top_management_chart"), genderTopManagement);
            @endif ;

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
                            display: true,
                            text: "{{ __('Matriz de Prioridade') }}",
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: '18px',
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            padding: {
                                bottom: 30
                            },
                            color: twConfig.theme.colors.esg8
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
                                text: "CRITICIDADE PARA O NEGÓCIO",
                                color: twConfig.theme.colors.esg8,
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
                                borderColor: twConfig.theme.colors.esg8,
                                borderWidth: 2,
                                tickLength: 0,
                                color: twConfig.theme.colors.esg8,
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
                                color: twConfig.theme.colors.esg8,
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
                                borderColor: twConfig.theme.colors.esg8,
                                borderWidth: 2,
                                tickLength: 0,
                                color: twConfig.theme.colors.esg8,
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


            // emission
            @if ($charts['emission_scope'])

                const centerText = {
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
                        ctx.font = "bold 20px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "Total";
                        ctx.fillText(text, width / 2, height / 2 + 10);

                        var total = "{{ $charts['emission_scope']['total'] }}";

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg2;
                        var text = total;
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 22px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "t CO₂e";
                        ctx.fillText(text, width / 2, height / 2 + top + 40);
                    }
                };

                const emissionLegend = {
                    afterInit: function(chart, args, options) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '';

                        chart.data.datasets[0].data.forEach((data, i) => {
                            let iconUrl = '{{ $genderIconUrl }}';

                            let labelText = chart.data.labels[i];
                            let value = data;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];
                            let total = parseInt('{{ $charts['emission_scope']['total'] }}');
                            let percentage = ((value * 100) / total).toFixed(1);

                            html += `
                                <div class="grid w-full grid-cols-3">
                                    <div class=" flex items-center w-32">
                                        <div class="text-esg5 text-2xl">
                                            <span class="w-2 h-2 relative -top-1 rounded-full inline-block bg-esg5 text-esg5" style="background-color: ${backgroundColor}"></span>
                                        </div>
                                        <div class="inline-block text-esg8 text-lg pl-1">${labelText}</div>
                                    </div>
                                    <div class="text-right font-bold leading-10 text-esg11 w-32 text-base"><span style="color: ${backgroundColor}">${percentage}%</span> (${value})</div>
                                </div>
                            `;
                        })

                        document.getElementById(legendId).innerHTML = html;
                    }
                };

                var options = {
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: true,
                            text: '{{ __('Emission') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    labels: {!! json_encode($charts['emission_scope']['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($charts['emission_scope']['series']) !!},
                        backgroundColor: ["#7AA12E", "#5A7820", "#99CA3C"],
                        hoverBackgroundColor: ["#7AA12E", "#5A7820", "#99CA3C"],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [centerText, emissionLegend]
                };

                var ctx = document.getElementById('emission_chart');
                new Chart(ctx, config);
            @endif

            // Voluntary contributions to biodiversity projects / net income

            @if ($charts['voluntary_contributions'])
                const centerTextContributions = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg2;
                        var text = parseInt(chart.data.datasets[0].data[0]) + '%';
                        ctx.fillText(text, width / 2, height / 2 + 40);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 24px bold " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['voluntary_contributions']['value'] !!} m€";
                        ctx.fillText(text, width / 2, height / 2 + top + 20);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('Global ESG Maturity Level') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['voluntary_contributions']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg25, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg25, twConfig.theme.colors
                            .esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [centerTextContributions]
                };

                var ctx = document.getElementById('voluntary_contributions');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('Global ESG Maturity Level') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('voluntary_contributions2');
                new Chart(ctx, config);
            @endif


            // employees_with_disabilities
            @if ($charts['employees_disabilities'])
                const employees_with_disabilities = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['employees_disabilities']['value'] !!}";
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 24px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "colaboradores";
                        ctx.fillText(text, width / 2, height / 2 + top + 40);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('Global ESG Maturity Level') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['employees_disabilities']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [employees_with_disabilities]
                };

                var ctx = document.getElementById('employees_with_disabilities');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('Global ESG Maturity Level') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('employees_with_disabilities2');
                new Chart(ctx, config);
            @endif

            //employee_satisfaction
            @if ($charts['employee_satisfaction_rate'])

                const centerTextEmployeeSatisfaction = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('employee_satisfaction') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['employee_satisfaction_rate']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [centerTextEmployeeSatisfaction]
                };

                var ctx = document.getElementById('employee_satisfaction');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('employee_satisfaction') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('employee_satisfaction2');
                new Chart(ctx, config);
            @endif


            //employee_satisfaction_conciliation_measures
            @if ($charts['employee_satisfaction_rate_measures'])
                const employee_satisfaction_conciliation_measures = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('employee_satisfaction') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['employee_satisfaction_rate_measures']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [employee_satisfaction_conciliation_measures]
                };

                var ctx = document.getElementById('employee_satisfaction_conciliation_measures');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('employee_satisfaction_conciliation_measures') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('employee_satisfaction_conciliation_measures2');
                new Chart(ctx, config);
            @endif

            // labor_agreements
            @if ($charts['labor_agreements'])
                const labor_agreements = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = {!! json_encode($charts['labor_agreements']['value']) !!};
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 24px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "colaboradores";
                        ctx.fillText(text, width / 2, height / 2 + top + 40);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('labor_agreements') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['labor_agreements']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [labor_agreements]
                };

                var ctx = document.getElementById('labor_agreements');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('labor_agreements') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('labor_agreements2');
                new Chart(ctx, config);
            @endif

            // employees_in_training
            @if ($charts['employee_training_sustainability'])
                const employees_in_training = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['employee_training_sustainability']['value'] !!}";
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 24px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "colaboradores";
                        ctx.fillText(text, width / 2, height / 2 + top + 40);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('employees_in_training') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['employee_training_sustainability']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [employees_in_training]
                };

                var ctx = document.getElementById('employees_in_training');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('employees_in_training') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('employees_in_training2');
                new Chart(ctx, config);
            @endif

            // pay_disparities_in_top_management
            @if ($charts['disparities_top_management'])
                const pay_disparities_in_top_management = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = chart.data.datasets[0].data[0] + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('pay_disparities_in_top_management') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['disparities_top_management']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [pay_disparities_in_top_management]
                };

                var ctx = document.getElementById('pay_disparities_in_top_management');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('pay_disparities_in_top_management') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('pay_disparities_in_top_management2');
                new Chart(ctx, config);
            @endif

            // collaborators_in_volunteer
            @if ($charts['collaborators_volunteer_actions'])
                const collaborators_in_volunteer = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['collaborators_volunteer_actions']['value'] !!}";
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 24px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "colaboradores";
                        ctx.fillText(text, width / 2, height / 2 + top + 40);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('collaborators_in_volunteer') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['collaborators_volunteer_actions']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [collaborators_in_volunteer]
                };

                var ctx = document.getElementById('collaborators_in_volunteer');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('collaborators_in_volunteer') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('collaborators_in_volunteer2');
                new Chart(ctx, config);
            @endif

            // voluntary_contributions_to_social_projects
            @if ($charts['collaborators_environmental_projects'])
                const voluntary_contributions_to_social_projects = {
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + 30);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['collaborators_environmental_projects']['value'] !!} m€";
                        ctx.fillText(text, width / 2, height / 2 + top + 20);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('voluntary_contributions_to_social_projects') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['collaborators_environmental_projects']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [voluntary_contributions_to_social_projects]
                };

                var ctx = document.getElementById('voluntary_contributions_to_social_projects');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('voluntary_contributions_to_social_projects') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('voluntary_contributions_to_social_projects2');
                new Chart(ctx, config);
            @endif

            // stakeholders
            @if ($charts['average_satisfaction_rate_stakeholders'])
                const stakeholders = {
                    id: 'stakeholders',
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg3;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('stakeholders') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['average_satisfaction_rate_stakeholders']) !!},
                        backgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [stakeholders]
                };

                var ctx = document.getElementById('stakeholders');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('stakeholders') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('stakeholders2');
                new Chart(ctx, config);
            @endif

            // socio_environmental
            @if ($charts['socialenvironmental_information'])
                const socio_environmental = {
                    id: 'socio_environmental',
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg3;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['socialenvironmental_information']['value'] !!}";
                        ctx.fillText(text, width / 2, height / 2 + top);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 20px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "produtos/serviços";
                        ctx.fillText(text, width / 2, height / 2 + top + 40);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('socio_environmental') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['socialenvironmental_information']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [socio_environmental]
                };

                var ctx = document.getElementById('socio_environmental');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('socio_environmental') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('socio_environmental2');
                new Chart(ctx, config);
            @endif

            // corresponding_turnover
            @if ($charts['turnover_in_portugal'])
                const corresponding_turnover = {
                    id: 'corresponding_turnover',
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg3;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top - 20);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['turnover_in_portugal']['value'] !!} m€";
                        ctx.fillText(text, width / 2, height / 2 + top + 30);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('corresponding_turnover') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['turnover_in_portugal']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [corresponding_turnover]
                };

                var ctx = document.getElementById('corresponding_turnover');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('corresponding_turnover') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('corresponding_turnover2');
                new Chart(ctx, config);
            @endif

            // criteria_of_sustainability
            @if ($charts['sustainability_criteria'])
                const criteria_of_sustainability = {
                    id: 'criteria_of_sustainability',
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
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg3;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top - 20);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['sustainability_criteria']['value'] !!} m€";
                        ctx.fillText(text, width / 2, height / 2 + top + 30);
                    }
                };

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('criteria_of_sustainability') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%'
                };

                var data = {
                    datasets: [{
                        data: {!! json_encode($charts['sustainability_criteria']['percentage']) !!},
                        backgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg18],
                        borderRadius: 12,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options,
                    plugins: [criteria_of_sustainability]
                };

                var ctx = document.getElementById('criteria_of_sustainability');
                new Chart(ctx, config);

                var options = {
                    plugins: {
                        title: {
                            display: true,
                            text: '{{ __('criteria_of_sustainability') }}',
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: 18,
                                weight: twConfig.theme.fontWeight.bold,
                            },
                            color: twConfig.theme.colors.esg27
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    cutout: '80%',
                    elements: {
                        arc: {
                            roundedCornersFor: 0
                        }
                    }
                };

                var data = {
                    datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [twConfig.theme.colors.esg18],
                        borderRadius: 0,
                        borderWidth: 0,
                        spacing: 0,
                    }],
                };

                var config = {
                    type: 'doughnut',
                    data: data,
                    options
                };

                var ctx = document.getElementById('criteria_of_sustainability2');
                new Chart(ctx, config);
            @endif


            document.getElementById("plus").onclick = function() {
                document.getElementById("minus").style.display = 'block';
                document.getElementById("plus").style.display = 'none';
                document.getElementById("saiba_mais").style.display = 'block';
            };

            document.getElementById("minus").onclick = function() {
                document.getElementById("plus").style.display = 'block';
                document.getElementById("minus").style.display = 'none';
                document.getElementById("saiba_mais").style.display = 'none';
            };

        });
    </script>
@endpush

@section('content')
    <div class="px-4 lg:px-0">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-end items-center border-b border-esg7/50 my-10 pb-5 print:hidden">
                <div class="flex items-center flex-row-reverse gap-3">
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 print:hidden" @click="window.print()">
                        <x-slot name="buttonicon">
                            @includeFirst(
                                [tenant()->views . 'icons.download', 'icons.download'],
                                ['class' => 'inline', 'color' => color(4)]
                            )
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium">{{ __('Imprimir') }}</span>
                    </x-buttons.btn-icon-text>

                    <x-buttons.btn-icon-text class="!bg-esg4 !text-esg8 border-esg8  print:hidden"
                        @click="location.href='{{ route('tenant.dashboards', ['questionnaire' => $questionnaire->id]) }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium">{{ __('Voltar') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>

            {{-- Banner --}}
            <div class="pagebreak print:h-full relative -mt-10">
                <img src="/images/customizations/tenantfa385a88-d089-434e-917f-9888c24cd400/home-bg.jpg" class="h-[1000px]">
                <div class="absolute z-0 w-full h-[1000px] bg-black/40 -mt-[82.2%] print:-mt-[131%]"> </div>

                <div
                    class="absolute w-full h-56 text-esg27 -mt-[80%] text-center flex flex-col place-content-center text-3xl font-bold print:-mt-[125%]">
                    <p>{{ __('Relatório da Jornada 2030') }}</p>
                    <p class="text-xl mt-2">
                        {{ date('Y-m-d', strtotime($questionnaire['from'])) }} a
                        {{ date('Y-m-d', strtotime($questionnaire['to'])) }}
                    </p>
                </div>

                <div
                    class="absolute z-40 w-full h-56 bg-esg7 -mt-[50%] text-center grid place-content-center text-3xl font-bold print:-mt-[90%]">
                    <p class="uppercase">{{ $report['compnay']['name'] }}</p>
                </div>

                <div class="absolute z-40 w-full h-56 -mt-[20%] text-end grid place-content-end font-bold print:-mt-[30%]">
                    @include(tenant()->views . 'icons.carta')
                </div>
            </div>

            {{-- Indexs --}}
            <div class="pagebreak pl-6 mt-5">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>
                <p class="font-encodesans text-base font-bold leading-5 text-[#1F9C8A]"> {{ __('Índice') }} </p>

                <div class="flex justify-between">
                    <p class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-5"> <span>1</span>
                        <span>{{ __('Mensagem da Gestão') }}</span> </p>
                </div>

                <div class="flex justify-between">
                    <p class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-5"> <span>2</span>
                        <span>{{ __('Perfil da Empresa') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>2.1</span>
                        <span>{{ __('Caracterização') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>2.2</span>
                        <span>{{ __('Missão, Visão e Modelo de Negócio') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>2.3</span>
                        <span>{{ __('Governance da sustentabilidade') }}</span> </p>
                </div>

                <div class="flex justify-between">
                    <p class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-5"> <span>3</span>
                        <span>{{ __('Estratégia de Sustentabilidade') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>3.1</span>
                        <span>{{ __('Compromisso com a Carta de Princípios do BCSD Portugal') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>3.2</span>
                        <span>{{ __('Partes interessadas') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>3.3</span>
                        <span>{{ __('Materialidade') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>3.4</span>
                        <span>{{ __('Compromissos e metas de sustentabilidade') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>3.5</span>
                        <span>{{ __('Contributo para os Objetivos de Desenvolvimento Sustentável') }}</span> </p>
                </div>

                <div class="flex justify-between">
                    <p class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-5"> <span>4</span>
                        <span>{{ __('Desempenho na Jornada 2030') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>4.1</span>
                        <span>{{ __('Posicionamento nas etapas da Jornada 2030') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>4.2</span>
                        <span>{{ __('Ambiental') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>4.3</span>
                        <span>{{ __('Social') }}</span> </p>
                </div>

                <div class="flex justify-between pl-10">
                    <p class="font-encodesans text-xs font-normal leading-5 text-esg8 mt-4 flex gap-5"> <span>4.4</span>
                        <span>{{ __('Governança') }}</span> </p>
                </div>

                <div class="flex justify-between">
                    <p class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-5"> <span>5</span>
                        <span>{{ __('Declaração de Renovação do Compromisso') }}</span> </p>
                </div>

            </div>

            {{-- 1. Mensagem da Gestão --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between items-center">
                    <div class="w-12 h-3 mt-3.5 bg-[#E4A53C] absolute left-0 hidden print:block"> </div>
                    <div
                        class="font-encodesans text-base font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div> 1.</div>
                        <span>{{ __('Mensagem da Gestão') }}</span>
                    </div>
                </div>

                <div class="text-xs font-normal text-esg8 mt-4">
                    {{ $report['management_message'] ?? '-' }}
                </div>
            </div>

            {{-- 2. Perfil da Empresa --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between items-center">
                    <div class="w-12 h-3 mt-3.5 bg-[#E4A53C] absolute left-0 hidden print:block"> </div>
                    <div
                        class="font-encodesans text-base font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div> 2.</div>
                        <span>{{ __('Perfil da Empresa') }}</span>
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div> 2.1</div>
                        <span class="uppercase">{{ __('CARACTERIZAÇÃO') }}</span>
                    </div>
                </div>

                <div class="pl-2 mt-4">
                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                        <li>{{ __('Nome') }}: {{ $report['compnay']['name'] }}</li>
                        <li>{{ __('Setor de atividade') }}: {{ $report['compnay']['business_sector']['name'] }}</li>
                        <li>{{ __('CAE') }}: {{ $report['cae'] }}</li>
                        <li>{{ __('Dimensão') }}: {{ $report['dimension'] }}</li>
                        <li>{{ __('Natureza da empresa') }}: {{ $report['nature_of_company'] ?? '-' }}</li>
                        <li>{{ __('Geografia de atuação') }}: {{ $report['acting_geography'] ?? '-' }}</li>
                        <li>{{ __('Âmbito de atividade') }}: {{ $report['scope_of_activity'] ?? '-' }}</li>
                    </ul>
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>2.2</div>
                        <span class="uppercase">{{ __('Missão, Visão e Modelo de Negócio') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['mission_vision'] ?? '-' }}</p>
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>2.3</div>
                        <span class="uppercase">{{ __('Governance da sustentabilidade') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['sustainability_governance'] ?? '-' }}</p>
                </div>
            </div>

            {{-- 3. Estratégia de Sustentabilidade --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between items-center">
                    <div class="w-12 h-3 mt-3.5 bg-[#E4A53C] absolute left-0 hidden print:block"> </div>
                    <div
                        class="font-encodesans text-base font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div> 3.</div>
                        <span>{{ __('Estratégia de Sustentabilidade') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['sustainability_strategy'] ?? '-' }}</p>
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>3.1</div>
                        <span class="uppercase">{{ __('Compromisso com a Carta de Princípios do BCSD Portugal') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">
                        {{ __('Somos signatários da Carta de Princípios do BCSD Portugal, uma iniciativa que, no fim de 2022, juntava 180 empresas em torno de compromissos comuns de desenvolvimento sustentável para Portugal. Nesse âmbito, estamos a percorrer a Jornada 2030 – um roteiro de transição para a sustentabilidade até 2030 composto por 6 estágios de maturidade.') }}
                    </p>
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>3.2</div>
                        <span class="uppercase">{{ __('Partes interessadas') }}</span>
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Partes interessadas prioritárias') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['priority_stakeholders'] ?? '-' }}</p>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Expetativas e preocupações das partes interessadas') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['stakeholder_expectations'] ?? '-' }}</p>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Metodologia de incorporação das expetativas e preocupações das partes interessadas na estratégia da organização') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['methodology_for_stakeholder'] ?? '-' }}</p>
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>3.3</div>
                        <span class="uppercase">{{ __('Materialidade') }}</span>
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Metodologia utilizada para determinar os temas materiais') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['methodology_used_to_determine_material_topic'] ?? '-' }}</p>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Processo de auscultação às partes interessadas') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['stakeholder_consultation_process'] ?? '-' }}</p>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Temas materiais') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">{{ $report['material_themes'] ?? '-' }}</p>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Objetivos da Jornada 2030 materiais') }}</span>
                </div>

                <div class="mt-4">
                    @php
                        $data = [];
                        if ($report['journey_2030_materials'] != null) {
                            $data = explode('/', $report['journey_2030_materials']);

                            usort($data, function ($a, $b) {
                                $numberA = intval(preg_replace('/[^0-9]/', '', $a));
                                $numberB = intval(preg_replace('/[^0-9]/', '', $b));

                                return $numberA - $numberB;
                            });
                        }
                    @endphp
                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                        @foreach ($data as $text)
                            @if (trim($text) != '')
                                <li>{{ $text }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                {{-- 3.4 --}}
                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>3.4</div>
                        <span class="uppercase">{{ __('Compromissos e metas de sustentabilidade') }}</span>
                    </div>
                </div>

                {{-- Enviroment --}}
                @if (isset($charts['goal']['845']) || isset($charts['goal']['846']) || isset($charts['goal']['847']))
                    <div class="grid grid-cols-1 mt-4">
                        <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                            <span>{{ __('Ambientais') }}</span>
                        </div>

                        @if (isset($charts['goal']['845']))
                            <div class="">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left  border-0">
                                    @include('icons.dashboard.scope', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 1:') }} </span>
                                        <span class="text-xs font-bold text-esg8"> {{ __('Descarbonizar a economia') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['845']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li>{{ $line }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['846']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left  border-0">
                                    @include('icons.dashboard.shield', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 2:') }} </span>
                                        <span class="text-xs font-bold text-esg8"> {{ __('Atuar pela natureza') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0 ">
                                    @php $text = explode("\n", $charts['goal']['846']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li>{{ $line }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['847']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left  border-0">
                                    @include('icons.dashboard.economy', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 3:') }} </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Inovar para a economia circular') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0 ">
                                    @php $text = explode("\n", $charts['goal']['847']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li>{{ $line }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Social --}}
                @if (isset($charts['goal']['848']) ||
                        isset($charts['goal']['849']) ||
                        isset($charts['goal']['850']) ||
                        isset($charts['goal']['851']) ||
                        isset($charts['goal']['852']) ||
                        isset($charts['goal']['853']) ||
                        isset($charts['goal']['854']) ||
                        isset($charts['goal']['855']))

                    <div class="grid grid-cols-1">
                        <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                            <span>{{ __('Sociais') }}</span>
                        </div>
                        @if (isset($charts['goal']['848']))
                            <div>
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.talent', ['width' => 30, 'height' => 51])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 4:') }} </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Investir na atração e desenvolvimento de talento') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['848']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li>{{ $line }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['849']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.pp-life', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 5:') }} </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Valorizar a conciliação entre a vida profissional, familiar e pessoal') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['849']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['850']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.sustainability', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 6:') }} </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Capacitar para a sustentabilidade') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['850']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['851']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.conversation', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 7:') }} </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Dialogar para desenvolver as relações laborais') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['851']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['852']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.user-group', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 8:') }} </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Fortalecer a igualdade e a diversidade') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['852']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['853']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.well-being-security', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 9:') }} </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Garantir a cultura de bem-estar e segurança') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['853']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['854']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.human-rights', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <p class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 10:') }} </p>
                                        <p class="text-xs font-bold text-esg8">
                                            {{ __('Respeitar e promover os direitos humanos') }} </p>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['854']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['855']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.community', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 11:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Pertencer às comunidades locais') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['855']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                {{-- Governança --}}
                @if (isset($charts['goal']['856']) ||
                        isset($charts['goal']['857']) ||
                        isset($charts['goal']['858']) ||
                        isset($charts['goal']['859']) ||
                        isset($charts['goal']['860']) ||
                        isset($charts['goal']['861']) ||
                        isset($charts['goal']['862']) ||
                        isset($charts['goal']['863']) ||
                        isset($charts['goal']['864']))

                    <div class="grid grid-cols-1">
                        <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                            <span>{{ __('Governança') }}</span>
                        </div>
                        @if (isset($charts['goal']['856']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.stackholders', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 12:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Criar valor para todos os stakeholders') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['856']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['857']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.report', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 13:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Divulgar o desempenho em sustentabilidade') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['857']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['858']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.transparent-gov', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 14:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Comunicar com responsabilidade e transparência') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['858']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['859']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.management', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 15:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Aperfeiçoar as práticas de gestão') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['859']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['860']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.ethics', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 16:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Viver a ética em todas as decisões') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['860']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['861']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.data-protection', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 17:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Assegurar a integridade dos sistemas de informação') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['861']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['862']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.supply-chain', [
                                        'width' => 30,
                                        'height' => 31,
                                    ])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 18:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Alavancar a sustentabilidade através da cadeia de fornecimento') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['862']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['863']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.growth', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-xs font-normal text-esg8 mt-4"> {{ __('Objetivo 19:') }}
                                        </span>
                                        <span class="text-xs font-bold text-esg8">
                                            {{ __('Acelerar a jornada para a sustentabilidade') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['863']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($charts['goal']['864']))
                            <div class="mt-4">
                                <div class="flex items-center gap-5 w-full p-5 font-medium text-left border-0">
                                    @include('icons.dashboard.agreement', ['width' => 30, 'height' => 31])
                                    <div class="w-3/4">
                                        <span class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 20:') }}
                                        </span>
                                        <span class="text-base font-bold text-esg8">
                                            {{ __('Expandir a Carta de Princípios do BCSD Portugal') }} </span>
                                    </div>
                                </div>
                                <div class="pl-5 font-light border-0">
                                    @php $text = explode("\n", $charts['goal']['864']); @endphp
                                    <ul class="space-y-4 text-esg8 text-xs list-disc list-inside">
                                        @foreach ($text ?? [] as $line)
                                            @if ($line != ' ')
                                                <li> {{ $line }} </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- 3.5 --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>3.5</div>
                        <span
                            class="uppercase">{{ __('Contributo para os Objetivos de Desenvolvimento Sustentável') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-esg8 text-xs">
                        {{ __('Considerando os Objetivos da Jornada 2030 materiais para a nossa atividade, ao percorrermos a Jornada 2030 contribuímos para os seguintes Objetivos de Desenvolvimento Sustentável (ODS):') }}
                    </p>
                </div>

                <div class="mt-4">
                    <div class="grid grid-cols-6">
                        @if (in_array(850, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.4', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(852, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.5', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(851, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.7', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(853, $report['ods']) || in_array(851, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.8', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(845, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.9', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(854, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.10', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(847, $report['ods']) || in_array(857, $report['ods']) || in_array(862, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.12', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(845, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.13', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(846, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.15', ['class' => 'inline-block'])
                            </div>
                        @endif

                        @if (in_array(856, $report['ods']))
                            <div class="w-full mt-4">
                                @include('icons.goals.16', ['class' => 'inline-block'])
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 4. Desempenho na Jornada 2030 --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between items-center">
                    <div class="w-12 h-3 mt-3.5 bg-[#E4A53C] absolute left-0 hidden print:block"> </div>
                    <div
                        class="font-encodesans text-base font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>4.</div>
                        <span>{{ __('Desempenho na Jornada 2030') }}</span>
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>4.1</div>
                        <span class="uppercase">{{ __('Posicionamento nas etapas da Jornada 2030') }}</span>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 p-4">
                    <div class="grid justify-items-center mt-36">
                        <div class="absolute -mt-[80px] ml-[10px]">
                            @include('icons.dashboard.coliderar', [
                                'color' => $position === 'coliderar' ? '#BC7527' : '#C4C4C4',
                            ])
                        </div>

                        <div class="absolute -mt-[60px] -ml-[200px]">
                            @include('icons.dashboard.consolidar', [
                                'color' => in_array($position, ['coliderar', 'consolidar'])
                                    ? '#D37E48'
                                    : '#C4C4C4',
                            ])
                        </div>

                        <div class="absolute mt-[25px] -ml-[320px]">
                            @include('icons.dashboard.comunicar', [
                                'color' => in_array($position, ['coliderar', 'consolidar', 'comunicar'])
                                    ? '#E4A53C'
                                    : '#C4C4C4',
                            ])
                        </div>

                        <div class="absolute mt-[130px] -ml-[320px]">
                            @include('icons.dashboard.construir', [
                                'color' => in_array($position, [
                                    'coliderar',
                                    'consolidar',
                                    'comunicar',
                                    'construir',
                                ])
                                    ? '#216470'
                                    : '#C4C4C4',
                            ])
                        </div>

                        <div class="absolute mt-[205px] -ml-[200px]">
                            @include('icons.dashboard.conhecer', [
                                'color' => in_array($position, [
                                    'coliderar',
                                    'consolidar',
                                    'comunicar',
                                    'construir',
                                    'conhecer',
                                ])
                                    ? '#1F9C8A'
                                    : '#C4C4C4',
                            ])
                        </div>

                        <div class="absolute mt-[250px] ml-[0px]">
                            @include('icons.dashboard.despertar', [
                                'color' => in_array($position, [
                                    'coliderar',
                                    'consolidar',
                                    'comunicar',
                                    'construir',
                                    'conhecer',
                                    'despertar',
                                ])
                                    ? '#99C1C0'
                                    : '#C4C4C4',
                            ])
                        </div>
                        <div
                            class="w-[242.67px] h-[242.67px] shadow-md bg-esg4 rounded-full text-center font-encodesans text-lg text-esg8 font-bold">
                            <p class="pt-24">{{ __('Posicionamento ') }}</p>
                            <p> {{ __('da sua Empresa') }} </p>
                        </div>
                    </div>

                    <div class="font-encodesans md:mt-0">
                        <div class="flex">
                            <div class="pt-1">
                                <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                    <span
                                        class="w-5 h-5 rounded-full inline-block @if ($position === 'coliderar') bg-[#BC7527] text-[#BC7527] @else bg-esg11 text-esg11 @endif"></span>
                                </span>
                            </div>

                            <div class="pl-5">
                                <p
                                    class="font-bold @if ($position === 'coliderar') text-2xl text-[#BC7527] @else text-xl text-esg11 @endif">
                                    {{ __('COLIDERAR') }}</p>
                                <p class="text-base text-esg11">Estabelecer compromissos de longo prazo: Alcançar os
                                    objetivos 2030 e definir a ambição 2050</p>
                            </div>
                        </div>

                        <div class="flex mt-5">
                            <div class="pt-1">
                                <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                    <span
                                        class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar'])) bg-[#D37E48] text-[#D37E48] @else bg-esg11 text-esg11 @endif"></span>
                                </span>
                            </div>

                            <div class="pl-5">
                                <p
                                    class="font-bold @if (in_array($position, ['coliderar', 'consolidar'])) text-2xl text-[#D37E48] @else text-xl text-esg11 @endif">
                                    {{ __('CONSOLIDAR') }}</p>
                                <p class="text-base text-esg11">Monitorizar e atualizar para garantir o progresso e a
                                    ambição: Reavaliar a trajetória e reforçar medidas</p>
                            </div>
                        </div>

                        <div class="flex mt-5">
                            <div class="pt-1">
                                <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                    <span
                                        class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar'])) bg-[#E4A53C] text-[#E4A53C] @else bg-esg11 text-esg11 @endif"></span>
                                </span>
                            </div>

                            <div class="pl-5">
                                <p
                                    class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar'])) text-2xl text-[#E4A53C] @else text-xl text-esg11 @endif">
                                    {{ __('COMUNICAR') }}</p>
                                <p class="text-base text-esg11">Comunicar a jornada: Comunicar compromissos e desempenho e
                                    envolver as partes interessadas</p>
                            </div>
                        </div>

                        <div class="flex mt-5">
                            <div class="pt-1">
                                <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                    <span
                                        class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir'])) bg-[#216470] text-[#216470] @else bg-esg11 text-esg11 @endif"></span>
                                </span>
                            </div>

                            <div class="pl-5">
                                <p
                                    class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir'])) text-2xl text-[#216470] @else text-xl text-esg11 @endif">
                                    {{ __('CONSTRUIR') }}</p>
                                <p class="text-base text-esg11">Operacionalizar a jornada: Estabelecer objetivos e metas e
                                    definir planos de ação</p>
                            </div>
                        </div>

                        <div class="flex mt-5">
                            <div class="pt-1">
                                <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                    <span
                                        class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer'])) bg-[#1F9C8A] text-[#1F9C8A] @else bg-esg11 text-esg11 @endif"></span>
                                </span>
                            </div>

                            <div class="pl-5">
                                <p
                                    class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer'])) text-2xl text-[#1F9C8A] @else text-xl text-esg11 @endif">
                                    {{ __('CONHECER') }}</p>
                                <p class="text-base text-esg11">Estabelecer a base: Fazer diagnóstico e estabelecer
                                    prioridades estratégicas</p>
                            </div>
                        </div>

                        <div class="flex mt-5">
                            <div class="pt-1">
                                <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                    <span
                                        class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar'])) bg-[#99C1C0] text-[#99C1C0] @else bg-esg11 text-esg11 @endif"></span>
                                </span>
                            </div>

                            <div class="pl-5">
                                <p
                                    class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar'])) text-2xl text-[#99C1C0] @else text-xl text-esg11 @endif">
                                    {{ __('DESPERTAR') }}</p>
                                <p class="text-base text-esg11">Dar os primeiros passos: Compreender a necessidade e as
                                    oportunidades da sustentabilidade como estratégia corporativa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>4.2</div>
                        <span class="uppercase">{{ __('Ambiental') }}</span>
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Descarbonização') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Principais fontes de emissão de GEE') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['ghg_emissions'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicadores de monitorização') }}</span>
                </div>

                <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.scope')</span>
                        <span class="pl-2">{{ __('Emissões de GEE') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                        @if ($charts['emission_scope'])
                            <div class="grid grid-cols-2">
                                <div> <canvas id="emission_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                <div id="emission_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                            </div>
                        @else
                            <span class="text-esg2 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10">
                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.scope')</span>
                        <span class="pl-2">{{ __('Balanço de emissões de GEE / VAB') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold pl-10">
                        @if (is_numeric($charts['emission_balance']))
                            <span class="text-esg2 font-bold text-5xl">
                                {{ number_format($charts['emission_balance'], 1) }} </span> <span
                                class="text-esg2 font-bold text-2xl">t CO₂e/m€</span>
                        @else
                            <span class="text-esg2 font-bold text-5xl"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Biodiversidade') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Operações com impacte direto na biodiversidade') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['operations_direct_impact_biodiversity'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Medidas implementadas para compensar o impacte na biodiversidade') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['measures_implemented_biodiversity'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Contribuições voluntárias para projetos de regeneração da biodiversidade') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['voluntary_contributions_biodiversity'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="bg-esg4 drop-shadow-md rounded-2xl mt-4 p-4">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span data-tooltip-target="tooltip-02">@include('icons.dashboard.shield')</span>
                        <span
                            class="pl-3">{{ __('Contribuições voluntárias para projetos de biodiversidade / resultado líquido') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold">
                        @if ($charts['voluntary_contributions'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="voluntary_contributions2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="voluntary_contributions"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg2 font-bold text-5xl"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Economia circular') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Principais materiais adquiridos ou extraídos e respetiva massa') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['materials_acquired'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Principais resíduos gerados e respetiva massa') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['main_waste_generated'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicadores de monitorização') }}</span>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.supply-chain', [
                            'color' => color(2),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span class="pl-2">{{ __('Materiais adquiridos ou extraídos / VAB') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold pl-10">
                        @if (is_numeric($charts['materials_acquired']))
                            <span class="text-esg2 font-bold text-5xl">
                                {{ number_format($charts['materials_acquired'], 1) }} </span> <span
                                class="text-esg2 font-bold text-2xl"> ton/m€ </span>
                        @else
                            <span class="text-esg2 font-bold text-5xl"> - </span>
                        @endif
                    </div>
                </div>

                <div class=" w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10">
                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.supply-chain', [
                            'color' => color(2),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span class="pl-2">{{ __('Resíduos gerados / VAB') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold pl-10">
                        @if (is_numeric($charts['generated_waste']))
                            <span class="text-esg2 font-bold text-5xl">
                                {{ number_format($charts['generated_waste'], 1) }} </span> <span
                                class="text-esg2 font-bold text-2xl"> ton/m€ </span>
                        @else
                            <span class="text-esg2 font-bold text-5xl"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 4.3 Social --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>4.3</div>
                        <span class="uppercase">{{ __('Social') }}</span>
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Gestão de talento') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Descrição e âmbito do inquérito de satisfação aos colaboradores') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['satisfaction_survey_reconciliation'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Universo e amostra do questionário') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['universe_questionnaire'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Taxa de resposta ao questionário (%)') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['questionnaire_response_rate'] ?? '-' }} % </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Principais resultados obtidos') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['main_results_obtained'] ?? '-' }}</p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Medidas implementadas decorrentes dos resultados do questionário') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['measures_implemented_results_questionnaire'] ?? '-' }} </p>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div>
                    <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                        <span>{{ __('Indicador de monitorização') }}</span>
                    </div>

                    <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                        <div class="text-esg8 font-encodesans flex text-lg font-bold h-20 pb-5">
                            <span>@include('icons.dashboard.talent', ['width' => 35, 'height' => 35])</span>
                            <span class="pl-2">{{ __('Taxa de satisfação dos colaboradores') }}</span>
                        </div>
                        <div class="text-esg25 font-encodesans text-5xl font-bold">
                            @if ($charts['employee_satisfaction_rate'])
                                <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                    <canvas id="employee_satisfaction2"
                                        class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                    <canvas id="employee_satisfaction"
                                        class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                </div>
                            @else
                                <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Conciliação vida profissional, familiar e pessoal') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Descrição e âmbito do inquérito de satisfação sobre as medidas de conciliação realizado aos colaboradores') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['satisfaction_survey_reconciliation'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Universo e amostra do questionário') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['universe_questionnaire_social'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Taxa de resposta ao questionário (%)') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['questionnaire_response_rate_social'] ?? '-' }}
                        %</p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Principais resultados obtidos') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['main_results_obtained_social'] ?? '-' }}</p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Medidas implementadas decorrentes dos resultados do questionário') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['measures_implemented_questionnaire_social'] ?? '-' }}</p>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div>
                    <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                        <span>{{ __('Indicador de monitorização') }}</span>
                    </div>

                    <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                        <div class="text-esg8 font-encodesans flex text-lg font-bold h-20 pb-5">
                            <span>@include('icons.dashboard.pp-life', ['width' => 35, 'height' => 40])</span>
                            <span
                                class="pl-3">{{ __('Taxa de satisfação dos colaboradores sobre as medidas de conciliação') }}</span>
                        </div>
                        <div class="text-esg25 font-encodesans text-5xl font-bold">
                            @if ($charts['employee_satisfaction_rate_measures'])
                                <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                    <canvas id="employee_satisfaction_conciliation_measures2"
                                        class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                    <canvas id="employee_satisfaction_conciliation_measures"
                                        class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                </div>
                            @else
                                <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Capacitação em sustentabilidade') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Temas formativos de sustentabilidade prioritários') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['priority_sustainability_training_topics'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex h-20 pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.sustainability', ['width' => 35, 'height' => 35])</span>
                        <span class="pl-2">{{ __('Colaboradores em formação sobre sustentabilidade') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold">
                        @if ($charts['employee_training_sustainability'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="employees_in_training2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="employees_in_training"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Relações laborais') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Estratégia de gestão das relações laborais') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['labor_relations_management_strategy'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex h-20 pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.conversation', ['width' => 35, 'height' => 40])</span>
                        <span class="pl-3">{{ __('Grau de cobertura dos acordos laborais') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold">
                        @if ($charts['labor_agreements'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="labor_agreements2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="labor_agreements"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Igualdade e diversidade') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Estratégia de promoção da igualdade e diversidade') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['equality_diversity_promotion_strategy'] ?? '-' }} </p>
                </div>

            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="relative bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="relative col-span-2">
                        <div class="text-lg text-esg8 font-bold font-encodesans flex items-center">
                            <span class="inline-block pr-3"> @include('icons.dashboard.user-group') </span>
                            {{ __('Distribuição por género') }} {{ __('Total') }}
                        </div>

                        <div
                            class="text-esg8 text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                        </div>
                        @if ($charts['gender_total'])
                            <div id="gender_total" class="grid grid-cols-1 sm:grid-cols-2">
                                <div> <canvas id="gender_total_chart" class="m-auto !h-[250px] !w-[250px]"></canvas>
                                </div>
                                <div id="gender_total_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20">
                                </div>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="relative bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10">
                    <div class="relative col-span-2">
                        <div class="text-lg text-esg8 font-bold font-encodesans flex items-center">
                            <span class="inline-block pr-3"> @include('icons.dashboard.user-group') </span>
                            {{ __('Distribuição por género') }} {{ __('Colaboradores') }}
                        </div>

                        <div
                            class="text-esg8 text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                        </div>

                        @if ($charts['gender_collaborators'])
                            <div id="gender_collaborators" class="grid grid-cols-1 sm:grid-cols-2">
                                <div> <canvas id="gender_collaborators_chart"
                                        class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                <div id="gender_collaborators_chart-legend"
                                    class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="relative bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10">
                    <div class="relative col-span-2">
                        <div class="text-lg text-esg8 font-bold font-encodesans flex items-center">
                            <span class="inline-block pr-3"> @include('icons.dashboard.user-group') </span>
                            {{ __('Distribuição por género') }} {{ __('Gestão de topo') }}
                        </div>

                        <div
                            class="text-esg8 text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                        </div>

                        @if ($charts['gender_top_management'])
                            <div id="gender_top_management" class="grid grid-cols-1 sm:grid-cols-2">
                                <div> <canvas id="gender_top_management_chart"
                                        class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                <div id="gender_top_management_chart-legend"
                                    class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="w-full h-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.user-group')</span>
                        <span class="pl-2">{{ __('Disparidades remuneratórias na gestão de topo') }}</span>
                    </div>
                    <div class="text-esg1 font-encodesans text-5xl font-bold">
                        @if ($charts['disparities_top_management'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="pay_disparities_in_top_management2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="pay_disparities_in_top_management"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 text-center mt-10">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.user-group')</span>
                        <span class="pl-2">{{ __('Colaboradores com incapacidades') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold">
                        @if ($charts['employees_disabilities'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="employees_with_disabilities2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="employees_with_disabilities"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Saúde e segurança') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Tipo de acidentes laborais verificados') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['type_work_accidents'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Plano de melhorias implementado') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['implemented_improvement_plan'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.well-being-security', [
                            'color' => color(1),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span class="pl-2">{{ __('Frequência dos acidentes laborais') }}</span>
                    </div>
                    <div class="text-esg1 font-encodesans text-5xl font-bold pl-10">
                        @if (is_numeric($charts['occupational_accidents']))
                            <span class="text-esg1 font-bold text-5xl">
                                {{ number_format($charts['occupational_accidents']) }} </span> <span
                                class="text-esg1 font-bold text-2xl"> / ano </span>
                        @else
                            <span class="text-esg1 font-bold text-5xl"> 0 </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Direitos humanos') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Riscos de violação de direitos humanos identificados pela organização') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['human_rights_violation_risks'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Abordagem de gestão dos riscos de direitos humanos') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['human_rights_risk_management_approach'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Medidas decorrentes das reclamações sobre direitos humanos recebidas') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['complaints_human_rights_received'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.human-rights', [
                            'color' => color(1),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span class="pl-2">{{ __('Reclamações sobre direitos humanos recebidas') }}</span>
                    </div>
                    <div class="text-esg1 font-encodesans text-5xl font-bold pl-10">
                        @if (is_numeric($charts['human_rights_complaints']))
                            <span class="text-esg1 font-bold text-5xl">
                                {{ number_format($charts['human_rights_complaints']) }} </span> <span
                                class="text-esg1 font-bold text-2xl"> / ano </span>
                        @else
                            <span class="text-esg1 font-bold text-5xl"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Comunidades locais') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.community')</span>
                        <span class="pl-2">{{ __('Colaboradores em ações de voluntariado') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold">
                        @if ($charts['collaborators_volunteer_actions'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="collaborators_in_volunteer2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="collaborators_in_volunteer"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.community')</span>
                        <span class="pl-2">
                            {{ __('Contribuições voluntárias para projetos sociais e/ou ambientais / resultado líquido') }}</span>
                    </div>
                    <div class="text-esg25 font-encodesans text-5xl font-bold">
                        @if ($charts['collaborators_environmental_projects'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="voluntary_contributions_to_social_projects2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="voluntary_contributions_to_social_projects"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 4.4 governança --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between mt-4">
                    <div
                        class="font-encodesans text-xs font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>4.4</div>
                        <span class="uppercase">{{ __('governança') }}</span>
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Envolvimento com as partes interessadas') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Metodologia de avaliação da satisfação das partes interessadas') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['stakeholder_satisfaction_assessment'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.stackholders', ['width' => 30, 'height' => 30])</span>
                        <span class="pl-2">{{ __('Taxa de satisfação média dos stakeholders prioritários') }}</span>
                    </div>
                    <div class="text-esg3 font-encodesans text-5xl font-bold">
                        @if ($charts['average_satisfaction_rate_stakeholders'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="stakeholders2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="stakeholders"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Reporte de sustentabilidade') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="flex flex-col w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4 md:mt-0">
                    <div class="no-flex text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.report', ['width' => 30, 'height' => 30])</span>
                        <span class="pl-2">{{ __('Divulgação do desempenho em sustentabilidade') }}</span>
                    </div>
                    <div class="flex flex-col grow justify-center text-esg3 font-encodesans text-5xl font-bold">
                        <div class="flex justify-between items-center mt-5 px-10 pb-2">
                            <div class="flex items-center">
                                <div class="w-10">
                                    @include('icons.dashboard.notes', ['width' => 24, 'height' => 24])
                                </div>
                                <div class="font-medium text-esg8 text-xl">
                                    {{ __('Relatório de sustentabilidade') }}
                                </div>
                            </div>
                            <div class="">
                                @if ($charts['sustainability_performance'][0])
                                    @include('icons.dashboard.check-circle', [
                                        'width' => 24,
                                        'height' => 24,
                                    ])
                                @else
                                    @include('icons.dashboard.block-circle', [
                                        'width' => 24,
                                        'height' => 24,
                                    ])
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-5 px-10 pt-2">
                            <div class="flex items-center">
                                <div class="w-10">
                                    @include('icons.dashboard.verified', ['width' => 24, 'height' => 24])
                                </div>
                                <div class="font-medium text-esg8 text-xl">
                                    {{ __('Verificação externa do relatório') }}
                                </div>
                            </div>
                            <div class="">
                                @if ($charts['sustainability_performance'][1])
                                    @include('icons.dashboard.check-circle', [
                                        'width' => 24,
                                        'height' => 24,
                                    ])
                                @else
                                    @include('icons.dashboard.block-circle', [
                                        'width' => 24,
                                        'height' => 24,
                                    ])
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Transparência da informação') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Exemplos de informação socioambiental incluída nos produtos/serviços') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['examples_socio_environmental'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.transparent-gov', ['width' => 30, 'height' => 30])</span>
                        <span class="pl-2">{{ __('Produtos / Serviços com informação socioambiental') }}</span>
                    </div>
                    <div class="text-esg3 font-encodesans text-5xl font-bold">
                        @if ($charts['socialenvironmental_information'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="socio_environmental2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="socio_environmental"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Práticas de gestão') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Temas materiais cobertos por sistemas de gestão') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['material_topics_covered_management'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Modo como os sistemas de gestão estão organizados e interrelacionados') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['how_management_systems_are_organized'] ?? '-' }} </p>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="flex flex-col w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="no-flex text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.management', ['width' => 30, 'height' => 30])</span>
                        <span class="pl-2">{{ __('Sistemas de gestão certificados') }}</span>
                    </div>
                    <div class="flex flex-col grow justify-center text-esg3 font-encodesans text-5xl font-bold">
                        @if (isset($charts['management_certificate']['829']) ||
                                isset($charts['management_certificate']['830']) ||
                                isset($charts['management_certificate']['831']) ||
                                isset($charts['management_certificate']['832']) ||
                                isset($charts['management_certificate']['833']) ||
                                isset($charts['management_certificate']['834']) ||
                                isset($charts['management_certificate']['835']) ||
                                isset($charts['management_certificate']['836']) ||
                                isset($charts['management_certificate']['837']) ||
                                isset($charts['management_certificate']['838']) ||
                                isset($charts['management_certificate']['839']) ||
                                isset($charts['management_certificate']['840']) ||
                                isset($charts['management_certificate']['841']) ||
                                isset($charts['management_certificate']['842']) ||
                                isset($charts['management_certificate']['843']) ||
                                isset($charts['management_certificate']['844']))
                            <div class="grid grid-cols-5 gap-4">
                                @if (isset($charts['management_certificate']['829']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['829']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['829']) ? 'text-[#41AC49]' : 'text-esg11' }}">
                                            14001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['829']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['830']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['830']) ? 'text-esg8' : 'text-esg11' }}">
                                            &nbsp</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['830']) ? 'text-[#41AC49]' : 'text-esg11' }}">
                                            EMAS</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['830']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['831']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['831']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['831']) ? 'text-[#336600]' : 'text-esg11' }}">
                                            37001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['831']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['832']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['832']) ? 'text-esg8' : 'text-esg11' }}">
                                            NP</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['832']) ? 'text-[#CC6600]' : 'text-esg11' }}">
                                            4552</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['832']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['833']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['833']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['833']) ? 'text-[#CC3399]' : 'text-esg11' }}">
                                            22301</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['833']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['834']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['834']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['834']) ? 'text-[#2F9DA8]' : 'text-esg11' }}">
                                            20121</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['834']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['835']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['835']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['835']) ? 'text-[#F79600]' : 'text-esg11' }}">
                                            50001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['835']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['836']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['836']) ? 'text-esg8' : 'text-esg11' }}">
                                            NP</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['836']) ? 'text-[#91606C]' : 'text-esg11' }}">
                                            4457</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['836']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['837']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['837']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['837']) ? 'text-[#3462A6]' : 'text-esg11' }}">
                                            9001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['837']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['838']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['838']) ? 'text-esg8' : 'text-esg11' }}">
                                            NP</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['838']) ? 'text-[#E07185]' : 'text-esg11' }}">
                                            4469</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['838']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['839']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['839']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['839']) ? 'text-[#BF916F]' : 'text-esg11' }}">
                                            22000</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['839']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['840']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['840']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['840']) ? 'text-[#9253A0]' : 'text-esg11' }}">
                                            27001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['840']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['841']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['841']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['841']) ? 'text-[#CE2B4B]' : 'text-esg11' }}">
                                            45001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['841']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['842']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['842']) ? 'text-esg8' : 'text-esg11' }}">
                                            OHSAS</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['842']) ? 'text-[#CE2B4B]' : 'text-esg11' }}">
                                            18001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['842']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['843']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['843']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['843']) ? 'text-[#515760]' : 'text-esg11' }}">
                                            39001</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['843']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif

                                @if (isset($charts['management_certificate']['844']))
                                    <div class="text-center">
                                        <p
                                            class="text-sm font-medium {{ isset($charts['management_certificate']['844']) ? 'text-esg8' : 'text-esg11' }}">
                                            ISO</p>
                                        <p
                                            class="text-xl font-bold {{ isset($charts['management_certificate']['844']) ? 'text-[#9253A0]' : 'text-esg11' }}">
                                            20000</p>
                                        <p
                                            class="text-xs font-medium {{ isset($charts['management_certificate']['844']) ? 'text-esg8' : 'text-esg11' }}">
                                            CERTIFIED</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Ética') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Descrição do sistema de gestão da ética') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['description_the_ethics_management'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Resultados da verificação externa do sistema de gestão da ética') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['results_external_verification'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Destinatários dos canais de reclamação e denúncia') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['recipients_complaint'] ?? '-' }} </p>
                </div>

            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Tipo de reclamações recebidas pelas partes interessadas') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['complaints_received_interested_parties'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="col-span-1 w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.ethics', [
                            'color' => color(3),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span class="pl-2">{{ __('Reclamações recebidas nos canais de reclamação e denúncia') }}</span>
                    </div>
                    <div class="text-esg3 font-encodesans text-5xl font-bold pl-10">
                        @if (is_numeric($charts['whistleblowing_channels_complaints']))
                            <span class="text-esg3 font-bold text-5xl">
                                {{ number_format($charts['whistleblowing_channels_complaints']) }} </span> <span
                                class="text-esg3 font-bold text-2xl"> / ano </span>
                        @else
                            <span class="text-esg3 font-bold text-5xl"> - </span>
                        @endif
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Integridade dos sistemas de informação') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Descrição do sistema de gestão da integridade da informação') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['information_integrity_management_system'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Certificações relativas ao sistema de gestão da integridade da informação') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['certifications_related_information_integrity_management_system'] ?? '-' }} </p>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Riscos de uso indevido dos sistemas de informação e ataques informáticos') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['risks_misuse_information_systems'] ?? '-' }}
                    </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Medidas tomadas para garantir a integridade dos sistemas de informação') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['measures_taken_ensure_integrity_information_systems'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Medidas de monitorização e verificação de resiliência do sistema de gestão da integridade da informação') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['resilience_information_integrity_management_system'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Incidências negativas ocorridas') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['negative_incidences_occurred'] ?? '-' }} </p>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Medidas de melhoria implementadas decorrentes das incidências negativas') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['improvement_measures_implemented_negative_incidences'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Testes de resiliência efetuados e resultados obtidos') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8"> {{ $report['resilience_tests_carried_out'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Critérios de seleção de fornecedores e tecnologias no âmbito da integridade da informação') }}</span>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-normal text-esg8">
                        {{ $report['selection_criteria_suppliers_technologies'] ?? '-' }} </p>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="col-span-1 w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                        <span>@include('icons.dashboard.data-protection', [
                            'color' => color(3),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span class="pl-2">{{ __('Ataques e violações aos sistemas de informação') }}</span>
                    </div>
                    <div class="text-esg3 font-encodesans text-5xl font-bold pl-10">
                        @if (is_numeric($charts['attacks_and_breaches_of_information']))
                            <span class="text-esg3 font-bold text-5xl">
                                {{ number_format($charts['attacks_and_breaches_of_information']) }} </span> <span
                                class="text-esg3 font-bold text-2xl"> / ano </span>
                        @else
                            <span class="text-esg3 font-bold text-5xl"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Cadeia de fornecimento') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.supply-chain', [
                            'color' => color(3),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span class="pl-2">{{ __('Compras cobertas por critérios de sustentabilidade') }}</span>
                    </div>
                    <div class="text-esg3 font-encodesans text-5xl font-bold">
                        @if ($charts['sustainability_criteria'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="criteria_of_sustainability2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="criteria_of_sustainability"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>

                <div class="font-encodesans text-xs font-normal leading-5 text-[#1F9C8A] mt-4">
                    <span>{{ __('Expandir a Carta de Princípios do BCSD Portugal') }}</span>
                </div>

                <div class="font-encodesans text-xs font-medium leading-5 text-esg8 mt-6">
                    <span>{{ __('Indicador de monitorização') }}</span>
                </div>

                <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-4">
                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                        <span>@include('icons.dashboard.agreement', [
                            'color' => color(3),
                            'width' => 35,
                            'height' => 35,
                        ])</span>
                        <span
                            class="pl-2">{{ __('Volume de negócios em Portugal correspondente a subscritores da Carta') }}</span>
                    </div>
                    <div class="text-esg3 font-encodesans text-5xl font-bold">
                        @if ($charts['turnover_in_portugal'])
                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                <canvas id="corresponding_turnover2"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                <canvas id="corresponding_turnover"
                                    class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                            </div>
                        @else
                            <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 5. Declaração de Renovação do Compromisso --}}
            <div class="pagebreak mx-10">
                <div class="absolute right-3 -mt-7 hidden print:block">
                    @include(tenant()->views . 'icons.cart_logo')
                </div>

                <div class="flex justify-between items-center">
                    <div class="w-12 h-3 mt-3.5 bg-[#E4A53C] absolute left-0 hidden print:block"> </div>
                    <div
                        class="font-encodesans text-base font-bold leading-5 text-[#1F9C8A] mt-4 flex gap-3 place-content-center">
                        <div>5.</div>
                        <span>{{ __('Declaração de Renovação do Compromisso') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs text-esg8 font-normal">
                        {{ __('Declaro a veracidade das informações prestadas no presente relatório e renovo o compromisso com a transição para a sustentabilidade e a melhoria da nossa performance de gestão, fazendo uso da ferramenta de implementação da Carta de Princípios do BCSD Portugal – a Jornada 2030, numa lógica de melhoria contínua.') }}
                    </p>
                </div>

                <div class="mt-4">
                    <p class="font-bold text-xs test-esg8">{{ auth()->user()->name }}</p>
                    <p class="font-normal text-xs test-esg8">{{ __('Submitted at') }}
                        {{ \Carbon\Carbon::parse($questionnaire['submitted_at'])->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="print:h-full relative mt-12">
                <img src="/images/customizations/tenantfa385a88-d089-434e-917f-9888c24cd400/home-bg.jpg"
                    class="h-[1000px]">
                <div class="absolute z-0 w-full h-[1000px] bg-black/40 -mt-[82.2%] print:-mt-[131%]"> </div>

                <div
                    class="absolute z-40 w-full h-32 bg-esg7 -mt-[10.5%] text-center grid place-content-center text-3xl font-bold print:-mt-[12%]">
                    <div class="flex gap-5 items-center">
                        @if ($report['logo'])
                            <div>
                                <img src="{{ $report['logo'] }}">
                            </div>
                        @endif
                        @include(tenant()->views . 'icons.cart_logo')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
