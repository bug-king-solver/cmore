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
                        let html = '', gender = '';

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
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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
                        let html = '', gender = '';

                        chart.data.datasets[0].data.forEach((data, i) => {
                            let iconUrl = '{{ $genderIconUrl }}';

                            let labelText = chart.data.labels[i];
                            let value = data;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];
                            let total = parseInt('{{ $charts['gender_collaborators']['total'] }}');
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
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

            @if($charts['gender_top_management'])
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
                        let html = '', gender = '';

                        chart.data.datasets[0].data.forEach((data, i) => {
                            let iconUrl = '{{ $genderIconUrl }}';

                            let labelText = chart.data.labels[i];
                            let value = data;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];
                            let total = parseInt('{{ $charts['gender_top_management']['total'] }}');
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
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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


            // emission
            @if($charts['emission_scope'])

                const centerText = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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

            @if($charts['voluntary_contributions'])
                const centerTextContributions = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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
                        hoverBackgroundColor: [twConfig.theme.colors.esg25, twConfig.theme.colors.esg18],
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['employees_disabilities'])
                const employees_with_disabilities = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['employee_satisfaction_rate'])

                const centerTextEmployeeSatisfaction = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

                        ctx.save;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['employee_satisfaction_rate_measures'])
                const employee_satisfaction_conciliation_measures = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

                        ctx.save;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['labor_agreements'])
                const labor_agreements = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['employee_training_sustainability'])
                const employees_in_training = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['disparities_top_management'])
                const pay_disparities_in_top_management = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

                        ctx.save;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg1;
                        var text = chart.data.datasets[0].data[0] + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['collaborators_volunteer_actions'])
                const collaborators_in_volunteer = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['collaborators_environmental_projects'])
                const voluntary_contributions_to_social_projects = {
                    id: 'centerText',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['average_satisfaction_rate_stakeholders'])
                const stakeholders = {
                    id: 'stakeholders',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

                        ctx.save;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg3;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top);
                    }
                };

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['socialenvironmental_information'])
                const socio_environmental = {
                    id: 'socio_environmental',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

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

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['turnover_in_portugal'])
                const corresponding_turnover = {
                    id: 'corresponding_turnover',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

                        ctx.save;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg3;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top -20);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['turnover_in_portugal']['value'] !!} m€";
                        ctx.fillText(text, width / 2, height / 2 + top + 30);
                    }
                };

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            @if($charts['sustainability_criteria'])
                const criteria_of_sustainability = {
                    id: 'criteria_of_sustainability',
                    afterDatasetsDraw(chart, args, options) {
                        const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;

                        ctx.save;

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bolder 35px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg3;
                        var text = parseInt(chart.data.datasets[0].data[0]) + "%";
                        ctx.fillText(text, width / 2, height / 2 + top -20);

                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = "bold 28px " + twConfig.theme.fontFamily.encodesans;
                        ctx.fillStyle = twConfig.theme.colors.esg11;
                        var text = "{!! $charts['sustainability_criteria']['value'] !!} m€";
                        ctx.fillText(text, width / 2, height / 2 + top + 30);
                    }
                };

                var options =  {
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

                var options =  {
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
                        backgroundColor: [ twConfig.theme.colors.esg18],
                        hoverBackgroundColor: [ twConfig.theme.colors.esg18],
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
            <div class="mb-4 border-b border-gray-200 mt-12 flex items-center justify-between">
                <ul class="flex flex-wrap -mb-px text-lg !text-esg8 font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2" id="action_plan-tab" data-tabs-target="#action_plan" type="button" role="tab" aria-controls="action_plan" aria-selected="false">{{ __('Posicionamento e Plano de Ação') }}</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent" id="indicators-tab" data-tabs-target="#indicators" type="button" role="tab" aria-controls="indicators" aria-selected="false">{{ __('Indicadores de Desempenho') }}</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent" id="goals-tab" data-tabs-target="#goals" type="button" role="tab" aria-controls="goals" aria-selected="false">{{ __('Objetivos Materiais e Metas') }}</button>
                    </li>
                </ul>

                <div>
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4" @click="location.href='{{ route('tenant.dashboards',  ['questionnaire' => $questionnaire->id]).'?print=true' }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case font-medium">{{ __('Ver relatório') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>

            <div id="myTabContent ">
                <div class="hidden pb-32" id="action_plan" role="tabpanel" aria-labelledby="action_plan-tab">

                    <div class="text-center">
                        <h1 class="font-encodesans text-4xl font-bold leading-10 text-esg5 mt-24">{{__('Etapas da Jornada 2030')}}</h1>
                        <p class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-4 mb-16">{{__('Posicionamento da sua empresa')}}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2">

                        <div class="grid justify-items-center mt-36">
                            <div class="absolute -mt-[80px] ml-[10px]">
                                @include('icons.dashboard.coliderar', ['color' => $position === 'coliderar' ? '#BC7527' : '#C4C4C4'])
                            </div>

                            <div class="absolute -mt-[60px] -ml-[200px]">
                                @include('icons.dashboard.consolidar', ['color' => in_array($position, ['coliderar', 'consolidar']) ? '#D37E48' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[25px] -ml-[320px]">
                                @include('icons.dashboard.comunicar', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar']) ? '#E4A53C' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[130px] -ml-[320px]">
                                @include('icons.dashboard.construir', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir']) ? '#216470' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[205px] -ml-[200px]">
                                @include('icons.dashboard.conhecer', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer']) ? '#1F9C8A' : '#C4C4C4'])
                            </div>

                            <div class="absolute mt-[250px] ml-[0px]">
                                @include('icons.dashboard.despertar', ['color' => in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar']) ? '#99C1C0' : '#C4C4C4'])
                            </div>
                            <div class="w-[242.67px] h-[242.67px] shadow-md bg-esg4 rounded-full text-center font-encodesans text-lg text-esg8 font-bold">
                                <p class="pt-24">{{ __('Posicionamento ') }}</p>
                                <p> {{ __('da sua Empresa') }} </p>
                            </div>
                        </div>

                        <div class="font-encodesans mt-32 md:mt-0">
                            <div class="flex">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if ($position === 'coliderar') bg-[#BC7527] text-[#BC7527] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if ($position === 'coliderar') text-2xl text-[#BC7527] @else text-xl text-esg11 @endif">{{ __('COLIDERAR') }}</p>
                                    <p class="text-base text-esg11">Estabelecer compromissos de longo prazo: Alcançar os objetivos 2030 e definir a ambição 2050</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar'])) bg-[#D37E48] text-[#D37E48] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar'])) text-2xl text-[#D37E48] @else text-xl text-esg11 @endif">{{ __('CONSOLIDAR') }}</p>
                                    <p class="text-base text-esg11">Monitorizar e atualizar para garantir o progresso e a ambição: Reavaliar a trajetória e reforçar medidas</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar'])) bg-[#E4A53C] text-[#E4A53C] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar'])) text-2xl text-[#E4A53C] @else text-xl text-esg11 @endif">{{ __('COMUNICAR') }}</p>
                                    <p class="text-base text-esg11">Comunicar a jornada: Comunicar compromissos e desempenho e envolver as partes interessadas</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir'])) bg-[#216470] text-[#216470] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir'])) text-2xl text-[#216470] @else text-xl text-esg11 @endif">{{ __('CONSTRUIR') }}</p>
                                    <p class="text-base text-esg11">Operacionalizar a jornada: Estabelecer objetivos e metas e definir planos de ação</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer'])) bg-[#1F9C8A] text-[#1F9C8A] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer'])) text-2xl text-[#1F9C8A] @else text-xl text-esg11 @endif">{{ __('CONHECER') }}</p>
                                    <p class="text-base text-esg11">Estabelecer a base: Fazer diagnóstico e estabelecer prioridades estratégicas</p>
                                </div>
                            </div>

                            <div class="flex mt-5">
                                <div class="pt-1">
                                    <span class="w-8 h-8 grid items-center rounded-full  bg-esg4 text-esg4 pl-1.5">
                                        <span class="w-5 h-5 rounded-full inline-block @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar'])) bg-[#99C1C0] text-[#99C1C0] @else bg-esg11 text-esg11 @endif"></span>
                                    </span>
                                </div>

                                <div class="pl-5">
                                    <p class="font-bold @if (in_array($position, ['coliderar', 'consolidar', 'comunicar', 'construir', 'conhecer', 'despertar'])) text-2xl text-[#99C1C0] @else text-xl text-esg11 @endif">{{ __('DESPERTAR') }}</p>
                                    <p class="text-base text-esg11">Dar os primeiros passos: Compreender a necessidade e as oportunidades da sustentabilidade como estratégia corporativa</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-20 font-encodesans mb-20">
                        <p class="text-xl text-esg8 font-bold"> {{ __('Saiba mais') }} </p>
                        <div class="" id="plus">
                            @include('icons.dashboard.plus', ['class' => 'mt-2 inline-block', 'width' => 30, 'height' => 30])
                        </div>

                        <div class="hidden" id="minus">
                            @include('icons.dashboard.minus', ['class' => 'mt-2 inline-block', 'width' => 28, 'height' => 28])
                        </div>
                    </div>

                    <div id="saiba_mais" class="font-encodesans text-justify text-esg8 text-base font-normal my-14 hidden transition delay-150 duration-300">
                        @if ($position === 'despertar')
                            <p class="text-base font-bold text-[#99C1C0] text-center">Despertar: Os primeiros passos são importantes</p>
                            <p class="mt-5">
                                A sua empresa já começou a realizar algumas ações para iniciar a jornada para a sustentabilidade, no entanto, tem potencial para avançar mais.
                            </p>

                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>

                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=73" class="text-esg6">“Beginners”</a> do BCSD Portugal. Este programa é dirigido a empresas interessadas em começar a integrar os princípios da sustentabilidade nos seus negócios e operações, para gestão de risco e otimização da cadeia de valor. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'conhecer')
                            <p class="text-base font-bold text-[#1F9C8A] text-center mt-10">Etapa “Conhecer”: Estabelecer a base é fundamental</p>
                            <p class="mt-5">
                                A sua empresa está a conhecer os aspetos chave para a integração da sustentabilidade na sua estratégia empresarial. Nesta etapa, são definidas as prioridades estratégicas no âmbito da sustentabilidade para a organização, sendo realizadas as seguintes ações principais: atribuição de responsabilidades pela gestão da sustentabilidade, mapeamento de stakeholders, diagnóstico interno, análise de materialidade e análise da cadeia de valor.
                            </p>
                            <p class="mt-5">
                                Tendo por base as prioridades estratégicas de sustentabilidade definidas, nesta etapa são ainda identificados os instrumentos e práticas de gestão da sustentabilidade que permitam a sua operacionalização.
                            </p>
                            <p class="mt-5">
                                Concluídos estes passos, estará estabelecida a base do conhecimento, visando a etapa “construir” da Jornada 2030. Nesta etapa, são desenvolvidos os planos de ação, estabelecidas metas SMART, utilizados referenciais/diretrizes e estabelecidas parcerias chave.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=73" class="text-esg6">“Beginners”</a> do BCSD Portugal para a consolidação de conhecimentos base. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'construir')
                            <p class="text-base font-bold text-[#216470] text-center mt-10">Etapa “Construir”: Operacionalizar a jornada</p>
                            <p class="mt-5">
                                A sua empresa encontra-se a desenvolver um plano de ação detalhado para a operacionalização das prioridades estratégicas de sustentabilidade estabelecidas na etapa “Conhecer”
                            </p>
                            <p class="mt-5">
                                Na etapa “Construir”, são estabelecidos os objetivos e metas para avançar na sua jornada para a sustentabilidade e é definido o plano de ação com indicadores de monitorização, a respetiva calendarização e alocando responsabilidades. São, também, implementados instrumentos e práticas de gestão da sustentabilidade com o apoio de referenciais e diretrizes reconhecidos, e são estabelecidas parcerias chave.
                            </p>
                            <p class="mt-5">
                                Concluídos estes passos, está em curso a operacionalização da jornada, visando a etapa “Comunicar” da Jornada 2030. Nesta etapa, são identificadas as estratégias de comunicação que permitam envolver stakeholders através de ações como auscultação de stakeholders e formação de colaboradores, e dar a conhecer o desempenho em sustentabilidade da empresa, nomeadamente através da publicação do desempenho da empresa relativamente aos seus temas materiais no relatório de sustentabilidade.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=73" class="text-esg6">“Beginners”</a> do BCSD Portugal para a consolidação de conhecimentos base. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'comunicar')
                            <p class="text-base font-bold text-[#E4A53C] text-center mt-10">Etapa “Comunicar”: Comunicar a jornada para as partes interessadas</p>
                            <p class="mt-5">
                                A sua empresa realiza ações voltadas para a comunicação interna e externa alinhadas com as metas estabelecidas e o seu desempenho em sustentabilidade, nomeadamente através da publicação do relatório de sustentabilidade, capacitação de colaboradores e subscrição de iniciativas/princípios/compromissos específicos alinhados com os temas materiais identificados.
                            </p>
                            <p class="mt-5">
                                Na etapa “Comunicar”, a empresa desenvolve também uma estratégia de envolvimento com stakeholders e realiza ações de auscultação com os grupos identificados. Adicionalmente, a empresa exerce um papel de influência na sua cadeia de valor através de ações dirigidas a estes stakeholders.
                            </p>
                            <p class="mt-5">
                                Concluídos estes passos, está assegurada a comunicação interna e externa para garantir a transparência, envolvimento e capacitação dos seus stakeholders, visando a etapa “Consolidar” da Jornada 2030. Nesta etapa, são estabelecidos planos de reavaliação da trajetória, nomeadamente através da monitorização do seu progresso, de forma a garantir a consecução das metas estabelecidas e reforçar medidas que permitam aumentar a ambição rumo a 2030.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, recomendamos o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=74" class="text-esg6">“Achievers”</a> do BCSD Portugal, que foi concebido com o objetivo de apoiar a continuidade da jornada das empresas para a sustentabilidade e proporcionar acesso ao conhecimento para a sua permanente evolução. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'consolidar')
                            <p class="text-base font-bold text-[#D37E48] text-center mt-10">Etapa “Consolidar”: Monitorizar e atualizar para garantir o progresso e a ambiçã</p>
                            <p class="mt-5">
                                A sua empresa encontra-se num estágio avançado de conhecimento e implementação dos planos de ação que endereçam os temas materiais identificados pela empresa. Também realiza ações de comunicação interna e externa para garantir a transparência, envolvimento e capacitação dos seus stakeholders
                            </p>
                            <p class="mt-5">
                                Na etapa “Consolidar”, a empresa verifica o nível de implementação dos planos de ação e identifica necessidades de melhoria e oportunidades de inovação, através do desenvolvimento de ações relacionadas à atualização do diagnóstico interno, envolvimento de stakeholders, estabelecimento de novas parcerias chave e atualização de compromissos.
                            </p>
                            <p class="mt-5">
                                Estando em curso ações de revisão e atualização regulares das prioridades estratégicas de sustentabilidade e respetivos planos de ação, estão estabelecidas as condições para a etapa “Coliderar” da Jornada 2030. Nesta etapa, a empresa continua a implementar instrumentos de gestão que garantam a prossecução dos seus objetivos 2030 e, com base nesta concretização, estabelece metas de longo prazo, com vista a 2050.
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                            <p class="mt-5">
                                Se tiver interesse em realizar formação, que também pode apoiar este percurso, sugerimos que explore o curso <a href="https://elearning.bcsdportugal.org/course/view.php?id=74" class="text-esg6">“Achievers”</a> do BCSD Portugal, que foi concebido com o objetivo de apoiar a continuidade da jornada das empresas para a sustentabilidade e proporcionar acesso ao conhecimento para a sua permanente evolução. A oferta formativa do BCSD Portugal oferece ainda formações à medida.
                            </p>
                        @endif

                        @if ($position === 'coliderar')
                            <p class="text-base font-bold text-[#BC7527] text-center mt-10">Etapa “Coliderar”: Estabelecer compromissos de longo prazo – ambição 205</p>
                            <p class="mt-5">
                                Com um conhecimento sólido sobre os temas materiais da empresa e um plano de ação alicerçado em revisões e atualizações constantes para garantir o cumprimento das metas estabelecidas, a sua empresa adota as ferramentas de gestão necessárias para garantir a consecução das suas metas de sustentabilidade rumo a 2030.
                            </p>
                            <p class="mt-5">
                                Adicionalmente, a sua empresa está em condições de traçar metas de longo-prazo, bem como adotar medidas de gestão rumo a 2050.
                            </p>
                            <p class="mt-5">
                                Na etapa “Coliderar”, espera-se que a empresa integre, cada vez mais profundamente, a sustentabilidade no seu modelo de negócio, nomeadamente ao utilizar recursos tecnológicos inovadores e/ou ao estabelecer ou transformar totalmente o seu modelo de negócio segundo critérios de sustentabilidade (ambientais, sociais e de governança).
                            </p>
                            <p class="mt-5">
                                Para conhecer os próximos passos, recomendamos explorar os recursos disponibilizados na <a href="{{ route('tenant.library.index') }}">Biblioteca</a> desta plataforma, nomeadamente os que integram as categorias “Referências” e “Aplicação prática” das pastas dos Objetivos da Jornada 2030. Estes recursos foram sistematizados de forma a apoiar as empresas no seu percurso para a sustentabilidade, em cada um dos 20 objetivos que compõem a Jornada 2030.
                            </p>
                        @endif

                    </div>

                    <div class="bg-esg3/[0.15] rounded-3xl p-1 md:p-10">
                        <div class="text-center">
                            <h1 class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-5">{{__('Matriz de Prioridade')}}</h1>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 bg-esg4 rounded-3xl mt-12">
                            @if ($charts['action_plan'])
                                <div x-data="{showExtraLegend: false}" class="md:col-span-1 lg:p-5 xl:p-10 ">
                                    <div @mouseover="showExtraLegend = true" @mouseover.away="showExtraLegend = false"  class="relative w-full">
                                        <div class="h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px]">
                                            <div></div>
                                            <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                            <div class="text-esg27 absolute left-[31px] top-[45px] rotate-90 text-4xl">
                                                @include('icons.arrow', ['class' => 'rotate-180', 'fill' => color(8)])
                                            </div>
                                            <div
                                                class="text-esg27 absolute left-[310px] top-[300px] sm:left-[465px] sm:top-[448px] text-4xl">
                                                @include('icons.arrow', ['fill' => color(8)])
                                            </div>
                                            <div x-show="showExtraLegend" class="absolute left-[50px] top-[60px] text-sm text-esg8">{{ __('Highly Recommended') }}</div>
                                            <div x-show="showExtraLegend" class="absolute left-[50px] bottom-[60px] text-sm text-esg8">{{ __('Recommended') }}</div>
                                            <div x-show="showExtraLegend" class="absolute right-[60px] top-[60px] text-sm text-esg8">{{ __('High Criticality') }}</div>
                                            <div x-show="showExtraLegend" class="absolute right-[60px] bottom-[60px] text-sm text-esg8">{{ __('High Priority') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($charts['action_plan_table'])
                                <div id="action_list" class="md:col-span-1 lg:p-5 xl:p-10 mt-10 lg:mt-0">
                                    <h2 class="text-esg8 pb-5 text-lg font-bold">{{ __('Action Plan') }}</h2>
                                    <table
                                        class="text-esg8 font-encodesans w-full table-auto"
                                        aria-labelledby="{{ __('Action Plan') }}">
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
                        </div>
                    </div>
                </div>

                <div class="hidden pb-32" id="indicators" role="tabpanel" aria-labelledby="indicators-tab">
                    {{-- Ambiente --}}
                    <div class="p-4 md:p-14 bg-esg2/[0.10] rounded-3xl">
                        <div class="">
                            <h1 class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-5">{{__('Ambiente')}}</h1>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 mt-10">
                            <div>
                                <div class="bg-esg4 drop-shadow-md rounded-2xl p-4">
                                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                        <span data-tooltip-target="tooltip-01">@include('icons.dashboard.scope')</span>
                                        <div id="tooltip-01" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O1 | Descarbonizar a economia
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-2">{{ __('Emissões de GEE') }}</span>
                                    </div>
                                    <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                                        @if($charts['emission_scope'] && $charts['emission_scope'] > 0)
                                            <div class="grid grid-cols-1 sm:grid-cols-2">
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
                                        <span data-tooltip-target="tooltip-03">@include('icons.dashboard.supply-chain', ['color' => color(2), 'width' => 35, 'height' => 35])</span>
                                        <div id="tooltip-03" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O3 | Inovar para a economia circular
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-2">{{ __('Materiais adquiridos ou extraídos / VAB') }}</span>
                                    </div>
                                    <div class="text-esg25 font-encodesans text-5xl font-bold pl-10">
                                        @if (is_numeric($charts['materials_acquired']))
                                            <span class="text-esg2 font-bold text-5xl"> {{ number_format($charts['materials_acquired'], 1) }} </span> <span class="text-esg2 font-bold text-2xl"> ton/m€ </span>
                                        @else
                                            <span class="text-esg2 font-bold text-5xl"> - </span>
                                        @endif
                                    </div>
                                </div>

                                <div class=" w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10">
                                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                        <span data-tooltip-target="tooltip-003">@include('icons.dashboard.supply-chain', ['color' => color(2), 'width' => 35, 'height' => 35])</span>
                                        <div id="tooltip-003" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O3 | Inovar para a economia circular
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-2">{{ __('Resíduos gerados / VAB') }}</span>
                                    </div>
                                    <div class="text-esg25 font-encodesans text-5xl font-bold pl-10">
                                        @if(is_numeric($charts['generated_waste']))
                                            <span class="text-esg2 font-bold text-5xl"> {{ number_format($charts['generated_waste'], 1) }} </span> <span class="text-esg2 font-bold text-2xl"> ton/m€ </span>
                                        @else
                                            <span class="text-esg2 font-bold text-5xl"> - </span>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class=" w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                        <span data-tooltip-target="tooltip-001">@include('icons.dashboard.scope')</span>
                                        <div id="tooltip-001" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O1 | Descarbonizar a economia
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-2">{{ __('Balanço de emissões de GEE / VAB') }}</span>
                                    </div>
                                    <div class="text-esg25 font-encodesans text-5xl font-bold pl-10">
                                        @if(is_numeric($charts['emission_balance']))
                                            <span class="text-esg2 font-bold text-5xl"> {{ number_format($charts['emission_balance'], 1) }} </span> <span class="text-esg2 font-bold text-2xl">t CO₂e/m€</span>
                                        @else
                                            <span class="text-esg2 font-bold text-5xl"> - </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-esg4 drop-shadow-md rounded-2xl mt-10 p-4">
                                    <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                        <span data-tooltip-target="tooltip-02">@include('icons.dashboard.shield')</span>
                                        <div id="tooltip-02" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O2 | Atuar pela natureza
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-3">{{ __('Contribuições voluntárias para projetos de biodiversidade / resultado líquido') }}</span>
                                    </div>
                                    <div class="text-esg25 font-encodesans text-5xl font-bold">
                                        @if($charts['voluntary_contributions'])
                                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                                <canvas id="voluntary_contributions2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                                <canvas id="voluntary_contributions" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            </div>
                                        @else
                                            <span class="text-esg2 font-bold text-5xl"> - </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="p-4 md:p-14 bg-esg1/[0.10] rounded-3xl mt-24">
                        <div class="">
                            <h1 class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-5">{{__('Social')}}</h1>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 mt-10">
                            <div class="bg-esg4 drop-shadow-md rounded-2xl p-4">
                                <div class="text-esg8 font-encodesans flex text-lg font-bold h-20 pb-5">
                                    <span data-tooltip-target="tooltip-04">@include('icons.dashboard.talent', ['width' => 35, 'height' => 35])</span>
                                    <div id="tooltip-04" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O4 | Investir na atração e desenvolvimento de talento
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Taxa de satisfação dos colaboradores') }}</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    @if($charts['employee_satisfaction_rate'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="employee_satisfaction2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="employee_satisfaction" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                <div class="text-esg8 font-encodesans flex text-lg font-bold h-20 pb-5">
                                    <span data-tooltip-target="tooltip-05">@include('icons.dashboard.pp-life', ['width' => 35, 'height' => 40])</span>
                                    <div id="tooltip-05" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O5 | Valorizar a conciliação entre a vida profissional, familiar e pessoal
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-3">{{ __('Taxa de satisfação dos colaboradores sobre as medidas de conciliação') }}</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    @if($charts['employee_satisfaction_rate_measures'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="employee_satisfaction_conciliation_measures2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="employee_satisfaction_conciliation_measures" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10 mt-10">
                            <div class="bg-esg4 drop-shadow-md rounded-2xl p-4">
                                <div class="text-esg8 font-encodesans flex h-20 pb-5 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-06">@include('icons.dashboard.sustainability', ['width' => 35, 'height' => 35])</span>
                                    <div id="tooltip-06" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O6 | Capacitar para a sustentabilidade
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Colaboradores em formação sobre sustentabilidade') }}</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    @if($charts['employee_training_sustainability'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="employees_in_training2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="employees_in_training" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                <div class="text-esg8 font-encodesans flex h-20 pb-5 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-07">@include('icons.dashboard.conversation', ['width' => 35, 'height' => 40])</span>
                                    <div id="tooltip-07" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O7 | Dialogar para desenvolver as relações laborais
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-3">{{ __('Grau de cobertura dos acordos laborais') }}</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    @if($charts['labor_agreements'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="labor_agreements2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="labor_agreements" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 md:gap-10 mt-10">
                            <div class="col-span-2 relative bg-esg4 drop-shadow-md rounded-2xl p-4" x-data="{ visible: 'gender_total' }">
                                @if ($charts['gender_total'] || $charts['gender_collaborators'] || $charts['gender_top_management'])
                                    <div class="grid grid-cols-3 lg:gap-2 text-esg8 text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 sm:w-72 lg:w-80 lg:right-8 justify-between text-sm">
                                        @if ($charts['gender_total'])<button class="mb-2.5" :class="{'text-esg28': visible === 'gender_total'}" @click="visible = 'gender_total'">{{ __('Total') }}</button>@endif
                                        @if ($charts['gender_collaborators'])<button class="mb-2.5" :class="{'text-esg28': visible === 'gender_collaborators'}" @click="visible = 'gender_collaborators'">{{ __('Colaboradores') }}</button>@endif
                                        @if ($charts['gender_top_management'])<button class="mb-2.5 w-28" :class="{'text-esg28': visible === 'gender_top_management'}" @click="visible = 'gender_top_management'">{{ __('Gestão de topo') }}</button>@endif
                                    </div>

                                    @if ($charts['gender_total'])
                                        <div id="gender_total_block" x-show="visible == 'gender_total'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                            <div class="relative col-span-2">
                                                <div class="text-lg text-esg8 font-bold font-encodesans">
                                                    <span class="inline-block pr-3" data-tooltip-target="tooltip-08"> @include('icons.dashboard.user-group') </span>
                                                    <div id="tooltip-08" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                        O8 | Fortalecer a igualdade e a diversidade
                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                    </div>
                                                    {{__('Distribuição por género')}}
                                                </div>
                                                <div
                                                    class="text-esg8 text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                                </div>

                                                <div id="gender_total" class="grid grid-cols-1 sm:grid-cols-2">
                                                    <div> <canvas id="gender_total_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                                    <div id="gender_total_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($charts['gender_collaborators'])
                                        <div id="gender_collaborators_block" x-show="visible == 'gender_collaborators'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                            <div class="relative col-span-2">
                                                <div class="text-lg text-esg8 font-bold font-encodesans">
                                                    <span class="inline-block pr-3" data-tooltip-target="tooltip-008"> @include('icons.dashboard.user-group') </span>
                                                    <div id="tooltip-008" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                        O8 | Fortalecer a igualdade e a diversidade
                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                    </div>
                                                    {{__('Distribuição por género')}}
                                                </div>

                                                <div
                                                    class="text-esg8 text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                                </div>

                                                <div id="gender_collaborators" class="grid grid-cols-1 sm:grid-cols-2">
                                                    <div> <canvas id="gender_collaborators_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                                    <div id="gender_collaborators_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($charts['gender_top_management'])
                                        <div id="gender_top_management_block" x-show="visible == 'gender_top_management'" class="col-span-1 pt-10 xl:pr-20 lg:pt-5 xl:pt-0">
                                            <div class="relative col-span-2">
                                                <div class="text-lg text-esg8 font-bold font-encodesans">
                                                    <span class="inline-block pr-3" data-tooltip-target="tooltip-0008"> @include('icons.dashboard.user-group') </span>
                                                    <div id="tooltip-0008" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                        O8 | Fortalecer a igualdade e a diversidade
                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                    </div>
                                                    {{__('Distribuição por género')}}
                                                </div>

                                                <div
                                                    class="text-esg8 text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                                                </div>

                                                <div id="gender_top_management" class="grid grid-cols-1 sm:grid-cols-2">
                                                    <div> <canvas id="gender_top_management_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                                                    <div id="gender_top_management_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="relative">
                                        <div class="text-lg text-esg8 pb-5 font-bold font-encodesans">
                                            <span class="inline-block pr-3" data-tooltip-target="tooltip-00008"> @include('icons.dashboard.user-group') </span>
                                            <div id="tooltip-00008" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                                O8 | Fortalecer a igualdade e a diversidade
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                            {{__('Distribuição por género')}}
                                        </div>

                                        <img src="{{ global_asset('images/charts/doughnut.png') }}" alt="">

                                        <div class="absolute p-3 top-[125px] left-[125px] bg-esg4 text-esg29 rounded-lg"> {{ __('No data available') }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="grid place-items-center">
                                <div class="col-span-1 w-full h-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                    <div class="text-esg8 font-encodesans flex place-content-center pb-5 h-20 text-lg font-bold">
                                        <span data-tooltip-target="tooltip-000008">@include('icons.dashboard.user-group')</span>
                                        <div id="tooltip-000008" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O8 | Fortalecer a igualdade e a diversidade
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-2">{{ __('Disparidades remuneratórias na gestão de topo') }}</span>
                                    </div>
                                    <div class="text-esg1 font-encodesans text-5xl font-bold">
                                        @if($charts['disparities_top_management'])
                                            <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                                <canvas id="pay_disparities_in_top_management2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                                <canvas id="pay_disparities_in_top_management" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            </div>
                                        @else
                                            <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 md:gap-10 mt-10">
                            <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 text-center">
                                <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-0000008">@include('icons.dashboard.user-group')</span>
                                    <div id="tooltip-0000008" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O8 | {{ __('Fortalecer a igualdade e a diversidade') }}
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Colaboradores com incapacidades') }}</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    @if($charts['employees_disabilities'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="employees_with_disabilities2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="employees_with_disabilities" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-2xl grid grid-cols-1 mt-10 md:mt-0">
                                <div class="col-span-1 w-full bg-esg4 drop-shadow-md rounded-2xl p-4">
                                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                        <span data-tooltip-target="tooltip-09">@include('icons.dashboard.well-being-security', ['color' => color(1), 'width' => 35, 'height' => 35])</span>
                                        <div id="tooltip-09" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O9 | {{ __('Garantir a cultura de bem-estar e segurança') }}
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-2">{{ __('Frequência dos acidentes laborais') }}</span>
                                    </div>
                                    <div class="text-esg1 font-encodesans text-5xl font-bold pl-10">
                                        @if(is_numeric($charts['occupational_accidents']))
                                            <span class="text-esg1 font-bold text-5xl"> {{ number_format($charts['occupational_accidents']) }} </span> <span class="text-esg1 font-bold text-2xl"> / ano </span>
                                        @else
                                            <span class="text-esg1 font-bold text-5xl"> 0 </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-span-1 w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-5">
                                    <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                        <span data-tooltip-target="tooltip-010">@include('icons.dashboard.human-rights', ['color' => color(1), 'width' => 35, 'height' => 35])</span>
                                        <div id="tooltip-010" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                            O10 | Respeitar e promover os direitos humanos
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        <span class="pl-2">{{ __('Reclamações sobre direitos humanos recebidas') }}</span>
                                    </div>
                                    <div class="text-esg1 font-encodesans text-5xl font-bold pl-10">
                                        @if(is_numeric($charts['human_rights_complaints']))
                                            <span class="text-esg1 font-bold text-5xl"> {{ number_format($charts['human_rights_complaints']) }} </span> <span class="text-esg1 font-bold text-2xl"> / ano </span>
                                        @else
                                            <span class="text-esg1 font-bold text-5xl"> - </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 md:gap-10 mt-10">
                            <div class="bg-esg4 drop-shadow-md rounded-2xl p-4">
                                <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-011">@include('icons.dashboard.community')</span>
                                    <div id="tooltip-011" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        11 | {{ __('Pertencer às comunidades locais') }}
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Colaboradores em ações de voluntariado') }}</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    @if($charts['collaborators_volunteer_actions'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="collaborators_in_volunteer2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="collaborators_in_volunteer" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-0011">@include('icons.dashboard.community')</span>
                                    <div id="tooltip-0011" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        11 | {{ __('Pertencer às comunidades locais') }}
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">Contribuições voluntárias para projetos sociais e/ou ambientais / resultado líquido</span>
                                </div>
                                <div class="text-esg25 font-encodesans text-5xl font-bold">
                                    @if($charts['collaborators_environmental_projects'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="voluntary_contributions_to_social_projects2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="voluntary_contributions_to_social_projects" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg1 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Governança --}}
                    <div class="p-4 md:p-14 bg-esg3/[0.10] rounded-3xl mt-24">
                        <div class="">
                            <h1 class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-5">{{__('Governança')}}</h1>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                            <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4">
                                <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-012">@include('icons.dashboard.stackholders', ['width' => 30, 'height' => 30])</span>
                                    <div id="tooltip-012" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        12 | Criar valor para todos os stakeholders
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Taxa de satisfação média dos stakeholders prioritários') }}</span>
                                </div>
                                <div class="text-esg3 font-encodesans text-5xl font-bold">
                                    @if($charts['average_satisfaction_rate_stakeholders'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="stakeholders2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="stakeholders" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                <div class="no-flex text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-013">@include('icons.dashboard.report', ['width' => 30, 'height' => 30])</span>
                                    <div id="tooltip-013" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        13 | Divulgar o desempenho em sustentabilidade
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Divulgação do desempenho em sustentabilidade') }}</span>
                                </div>
                                <div class="flex flex-col grow justify-center text-esg3 font-encodesans text-5xl font-bold">
                                    <div class="flex justify-between items-center mt-5 px-10 pb-2">
                                        <div class="flex items-center">
                                            <div class="w-10">
                                                @include('icons.dashboard.notes', ["width" => 24, "height" => 24])
                                            </div>
                                            <div class="font-medium text-esg8 text-xl">
                                                {{ __('Relatório de sustentabilidade') }}
                                            </div>
                                        </div>
                                        <div class="">
                                            @if($charts['sustainability_performance'][0])
                                                @include('icons.dashboard.check-circle', ["width" => 24, "height" => 24])
                                            @else
                                                @include('icons.dashboard.block-circle', ["width" => 24, "height" => 24])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center mt-5 px-10 pt-2">
                                        <div class="flex items-center">
                                            <div class="w-10">
                                                @include('icons.dashboard.verified', ["width" => 24, "height" => 24])
                                            </div>
                                            <div class="font-medium text-esg8 text-xl">
                                                {{ __('Verificação externa do relatório') }}
                                            </div>
                                        </div>
                                        <div class="">
                                            @if($charts['sustainability_performance'][1])
                                                @include('icons.dashboard.check-circle', ["width" => 24, "height" => 24])
                                            @else
                                                @include('icons.dashboard.block-circle', ["width" => 24, "height" => 24])
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                            <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4">
                                <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-014">@include('icons.dashboard.transparent-gov', ['width' => 30, 'height' => 30])</span>
                                    <div id="tooltip-014" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        14 | Comunicar com responsabilidade e transparência
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Produtos / Serviços com informação socioambiental') }}</span>
                                </div>
                                <div class="text-esg3 font-encodesans text-5xl font-bold">
                                    @if($charts['socialenvironmental_information'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="socio_environmental2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="socio_environmental" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                <div class="no-flex text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-015">@include('icons.dashboard.management', ['width' => 30, 'height' => 30])</span>
                                    <div id="tooltip-015" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        15 | Aperfeiçoar as práticas de gestão
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Sistemas de gestão certificados') }}</span>
                                </div>
                                <div class="flex flex-col grow justify-center text-esg3 font-encodesans text-5xl font-bold">
                                    @if( isset($charts['management_certificate']['829']) || isset($charts['management_certificate']['830']) || isset($charts['management_certificate']['831']) || isset($charts['management_certificate']['832'])
                                        || isset($charts['management_certificate']['833']) || isset($charts['management_certificate']['834']) || isset($charts['management_certificate']['835']) || isset($charts['management_certificate']['836'])
                                        || isset($charts['management_certificate']['837']) || isset($charts['management_certificate']['838']) || isset($charts['management_certificate']['839']) || isset($charts['management_certificate']['840'])
                                        || isset($charts['management_certificate']['841']) || isset($charts['management_certificate']['842']) || isset($charts['management_certificate']['843']) || isset($charts['management_certificate']['844']))
                                        <div class="grid grid-cols-5 gap-4">
                                            @if(isset($charts['management_certificate']['829']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['829']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['829']) ? 'text-[#41AC49]' : 'text-esg11' }}">14001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['829']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['830']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['830']) ? 'text-esg8' : 'text-esg11' }}">&nbsp</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['830']) ? 'text-[#41AC49]' : 'text-esg11' }}">EMAS</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['830']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['831']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['831']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['831']) ? 'text-[#336600]' : 'text-esg11' }}">37001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['831']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['832']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['832']) ? 'text-esg8' : 'text-esg11' }}">NP</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['832']) ? 'text-[#CC6600]' : 'text-esg11' }}">4552</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['832']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['833']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['833']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['833']) ? 'text-[#CC3399]' : 'text-esg11' }}">22301</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['833']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['834']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['834']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['834']) ? 'text-[#2F9DA8]' : 'text-esg11' }}">20121</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['834']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['835']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['835']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['835']) ? 'text-[#F79600]' : 'text-esg11' }}">50001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['835']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['836']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['836']) ? 'text-esg8' : 'text-esg11' }}">NP</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['836']) ? 'text-[#91606C]' : 'text-esg11' }}">4457</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['836']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['837']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['837']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['837']) ? 'text-[#3462A6]' : 'text-esg11' }}">9001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['837']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['838']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['838']) ? 'text-esg8' : 'text-esg11' }}">NP</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['838']) ? 'text-[#E07185]' : 'text-esg11' }}">4469</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['838']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['839']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['839']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['839']) ? 'text-[#BF916F]' : 'text-esg11' }}">22000</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['839']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['840']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['840']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['840']) ? 'text-[#9253A0]' : 'text-esg11' }}">27001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['840']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['841']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['841']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['841']) ? 'text-[#CE2B4B]' : 'text-esg11' }}">45001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['841']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['842']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['842']) ? 'text-esg8' : 'text-esg11' }}">OHSAS</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['842']) ? 'text-[#CE2B4B]' : 'text-esg11' }}">18001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['842']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['843']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['843']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['843']) ? 'text-[#515760]' : 'text-esg11' }}">39001</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['843']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif

                                            @if(isset($charts['management_certificate']['844']))
                                                <div class="text-center">
                                                    <p class="text-sm font-medium {{ isset($charts['management_certificate']['844']) ? 'text-esg8' : 'text-esg11' }}">ISO</p>
                                                    <p class="text-xl font-bold {{ isset($charts['management_certificate']['844']) ? 'text-[#9253A0]' : 'text-esg11' }}">20000</p>
                                                    <p class="text-xs font-medium {{ isset($charts['management_certificate']['844']) ? 'text-esg8' : 'text-esg11' }}">CERTIFIED</p>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                            <div class="col-span-1 w-full bg-esg4 drop-shadow-md rounded-2xl p-4">
                                <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-016">@include('icons.dashboard.ethics', ['color' => color(3), 'width' => 35, 'height' => 35])</span>
                                    <div id="tooltip-016" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        16 | Viver a ética em todas as decisões
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Reclamações recebidas nos canais de reclamação e denúncia') }}</span>
                                </div>
                                <div class="text-esg3 font-encodesans text-5xl font-bold pl-10">
                                    @if(is_numeric($charts['whistleblowing_channels_complaints']))
                                        <span class="text-esg3 font-bold text-5xl"> {{ number_format($charts['whistleblowing_channels_complaints']) }} </span> <span class="text-esg3 font-bold text-2xl"> / ano </span>
                                    @else
                                        <span class="text-esg3 font-bold text-5xl"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-span-1 w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                <div class="text-esg8 font-encodesans flex pb-5 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-017">@include('icons.dashboard.data-protection', ['color' => color(3), 'width' => 35, 'height' => 35])</span>
                                    <div id="tooltip-017" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        17 | Assegurar a integridade dos sistemas de informação
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Ataques e violações aos sistemas de informação') }}</span>
                                </div>
                                <div class="text-esg3 font-encodesans text-5xl font-bold pl-10">
                                    @if(is_numeric($charts['attacks_and_breaches_of_information']))
                                        <span class="text-esg3 font-bold text-5xl"> {{ number_format($charts['attacks_and_breaches_of_information']) }} </span> <span class="text-esg3 font-bold text-2xl"> / ano </span>
                                    @else
                                        <span class="text-esg3 font-bold text-5xl"> - </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                            <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4">
                                <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-00018">@include('icons.dashboard.supply-chain', ['color' => color(3), 'width' => 35, 'height' => 35])</span>
                                    <div id="tooltip-00018" role="tooltip" class="inline-block absolute invisible z-20 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        18 | Alavancar a sustentabilidade através da cadeia de fornecimento
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Compras cobertas por critérios de sustentabilidade') }}</span>
                                </div>
                                <div class="text-esg3 font-encodesans text-5xl font-bold">
                                    @if($charts['sustainability_criteria'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="criteria_of_sustainability2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="criteria_of_sustainability" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>

                            <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-4 mt-10 md:mt-0">
                                <div class="text-esg8 font-encodesans flex pb-5 h-20 text-lg font-bold">
                                    <span data-tooltip-target="tooltip-020">@include('icons.dashboard.agreement', ['color' => color(3), 'width' => 35, 'height' => 35])</span>
                                    <div id="tooltip-020" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        20 | Expandir a Carta de Princípios do BCSD Portugal
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <span class="pl-2">{{ __('Volume de negócios em Portugal correspondente a subscritores da Carta') }}</span>
                                </div>
                                <div class="text-esg3 font-encodesans text-5xl font-bold">
                                    @if($charts['turnover_in_portugal'])
                                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                            <canvas id="corresponding_turnover2" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                            <canvas id="corresponding_turnover" class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 !h-[250px] !w-[250px]"></canvas>
                                        </div>
                                    @else
                                        <span class="text-esg3 font-bold text-5xl pl-10"> - </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden pb-32" id="goals" role="tabpanel" aria-labelledby="goals-tab">
                    <div class="text-center">
                        <h1 class="font-encodesans text-4xl font-bold leading-10 text-esg5 mt-24">{{__('Objetivos Materiais da Empresa')}}</h1>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-10 font-encodesans">
                        <div class="col-span-2">
                            <div class="flex w-full bg-esg4 drop-shadow-md rounded-2xl p-8">
                                <div class="text-2xl font-bold text-esg8">
                                    {{ __('Ambientais') }}
                                </div>

                                <div class="ml-10 w-full">
                                    <span data-tooltip-target="tooltip-1" >
                                        @include('icons.dashboard.scope', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(845, $charts['goals'], true) ? color(2) : color(7)])
                                    </span>
                                    <div id="tooltip-1" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O1 | Descarbonizar a economia
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-2" >
                                        @include('icons.dashboard.shield', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(846, $charts['goals'], true) ? color(2) : color(7)])
                                    </span>
                                    <div id="tooltip-2" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O2 | Atuar pela natureza
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-3" >
                                        @include('icons.dashboard.supply-chain', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(847, $charts['goals'], true) ? color(2) : color(7)])
                                    </span>
                                    <div id="tooltip-3" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O3 | Inovar para a economia circular
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full bg-esg4 drop-shadow-md rounded-2xl p-8 mt-10">
                                <div class="text-2xl font-bold text-esg8">
                                    {{ __('Sociais') }}
                                </div>

                                <div class="ml-10 w-full">
                                    <span data-tooltip-target="tooltip-4" > @include('icons.dashboard.talent', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(848, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-4" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O4 | Investir na atração e desenvolvimento de talento
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-5" > @include('icons.dashboard.pp-life', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(849, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-5" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O5 | Valorizar a conciliação entre a vida profissional, familiar e pessoal
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-6" > @include('icons.dashboard.sustainability', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(850, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-6" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O6 | Capacitar para a sustentabilidade
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-7" > @include('icons.dashboard.conversation', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(851, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-7" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O7 | Dialogar para desenvolver as relações laborais
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <br/><br/>
                                    <span data-tooltip-target="tooltip-8" > @include('icons.dashboard.user-group', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(852, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-8" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O8 | Fortalecer a igualdade e a diversidade
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-9" > @include('icons.dashboard.well-being-security', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(853, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-9" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O9 | Garantir a cultura de bem-estar e segurança
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-10" > @include('icons.dashboard.human-rights', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(854, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-10" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        O10 | Respeitar e promover os direitos humanos
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <span data-tooltip-target="tooltip-11" > @include('icons.dashboard.community', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(855, $charts['goals'], true) ? color(1) : color(7)])</span>
                                    <div id="tooltip-11" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                        11 | Pertencer às comunidades locais
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full bg-esg4 drop-shadow-md rounded-2xl p-8">
                            <div class="text-2xl font-bold text-esg8">
                                {{ __('Governança') }}
                            </div>

                            <div class="w-full mt-10 text-center">
                                <span data-tooltip-target="tooltip-12" > @include('icons.dashboard.stackholders', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(856, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-12" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    12 | Criar valor para todos os stakeholders
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <span data-tooltip-target="tooltip-13" > @include('icons.dashboard.report', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(857, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-13" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    13 | Divulgar o desempenho em sustentabilidade
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <span data-tooltip-target="tooltip-14" > @include('icons.dashboard.transparent-gov', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(858, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-14" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    14 | Comunicar com responsabilidade e transparência
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <br/><br/>
                                <span data-tooltip-target="tooltip-15" > @include('icons.dashboard.management', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(859, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-15" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    15 | Aperfeiçoar as práticas de gestão
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <span data-tooltip-target="tooltip-16" > @include('icons.dashboard.ethics', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(860, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-16" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    16 | Viver a ética em todas as decisões
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <span data-tooltip-target="tooltip-17" > @include('icons.dashboard.data-protection', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(861, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-17" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    17 | Assegurar a integridade dos sistemas de informação
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <br/><br/>
                                <span data-tooltip-target="tooltip-18" > @include('icons.dashboard.supply-chain', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(862, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-18" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    18 | Alavancar a sustentabilidade através da cadeia de fornecimento
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <span data-tooltip-target="tooltip-19" > @include('icons.dashboard.growth', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(863, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-19" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    19 | Acelerar a jornada para a sustentabilidade
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <span data-tooltip-target="tooltip-20" > @include('icons.dashboard.agreement', ['class' => 'inline-block ml-5', 'width' => 60, 'height' => 60, 'color' => in_array(864, $charts['goals'], true) ? color(3) : color(7)])</span>
                                <div id="tooltip-20" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                                    20 | Expandir a Carta de Princípios do BCSD Portugal
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h1 class="font-encodesans text-4xl font-bold leading-10 text-esg5 mt-24">{{__('Metas da Jornada 2030')}}</h1>
                    </div>

                    @if(isset($charts['goal']['845']) || isset($charts['goal']['846']) || isset($charts['goal']['847']))
                        <div class="text-center">
                            <h2 class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-10">{{__('Ambientais')}}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                            @if(isset($charts['goal']['845']))
                            <div id="accordion-collapse1" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-1">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg2/[0.15] active:bg-esg2/[0.15]" data-accordion-target="#accordion-collapse-body-1" aria-expanded="false" aria-controls="accordion-collapse-body-1">
                                        @include('icons.dashboard.scope', ['width' => 50, 'height' => 51])
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 1:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Descarbonizar a economia') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                                    <div class="p-5 font-light border-0 bg-esg2/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['845']); @endphp
                                        @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg2 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg2 text-esg2"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['846']))
                            <div id="accordion-collapse2" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-2">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg2/[0.15] active:bg-esg2/[0.15]" data-accordion-target="#accordion-collapse-body-2" aria-expanded="false" aria-controls="accordion-collapse-body-2">
                                        @include('icons.dashboard.shield', ['width' => 50, 'height' => 51])
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 2:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Atuar pela natureza') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
                                    <div class="p-5 font-light border-0 bg-esg2/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['846']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg2 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg2 text-esg2"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['847']))
                            <div id="accordion-collapse3" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-3">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg2/[0.15] active:bg-esg2/[0.15]" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
                                        @include('icons.dashboard.economy', ['width' => 50, 'height' => 51])
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 3:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Inovar para a economia circular') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-3">
                                    <div class="p-5 font-light border-0 bg-esg2/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['847']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg2 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg2 text-esg2"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif



                    @if(isset($charts['goal']['848']) || isset($charts['goal']['849']) || isset($charts['goal']['850']) || isset($charts['goal']['851']) || isset($charts['goal']['852']) || isset($charts['goal']['853']) || isset($charts['goal']['854']) || isset($charts['goal']['855']))
                        <div class="text-center">
                            <h2 class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-10">{{__('Social')}}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                            @if(isset($charts['goal']['848']))
                                <div id="accordion-collapse4" data-accordion="collapse">
                                    <h2 id="accordion-collapse-heading-4">
                                        <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-4" aria-expanded="false" aria-controls="accordion-collapse-body-4">
                                            @include('icons.dashboard.talent', ['width' => 50, 'height' => 51])
                                            <div class="w-3/4">
                                                <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 4:') }} </p>
                                                <p class="text-base font-bold text-esg8"> {{ __('Investir na atração e desenvolvimento de talento') }} </p>
                                            </div>
                                            <div class="">
                                                <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="accordion-collapse-body-4" class="hidden" aria-labelledby="accordion-collapse-heading-4">
                                        <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                            @php $text = explode("\n", $charts['goal']['848']); @endphp
                                            @foreach ($text as $line)
                                                <div class="flex mt-4">
                                                    <div class="text-esg1 text-2xl">
                                                        <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                    </div>
                                                    <div class="pl-3 inline-block">{{ $line }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(isset($charts['goal']['849']))
                            <div id="accordion-collapse5" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-5">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-5" aria-expanded="false" aria-controls="accordion-collapse-body-5">
                                        @include('icons.dashboard.pp-life')
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 5:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Valorizar a conciliação entre a vida profissional, familiar e pessoal') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-5" class="hidden" aria-labelledby="accordion-collapse-heading-5">
                                    <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['849']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg1 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['850']))
                            <div id="accordion-collapse6" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-6">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-6" aria-expanded="false" aria-controls="accordion-collapse-body-6">
                                        @include('icons.dashboard.sustainability')
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 6:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Capacitar para a sustentabilidade') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-6" class="hidden" aria-labelledby="accordion-collapse-heading-6">
                                    <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['850']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg1 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['851']))
                            <div id="accordion-collapse7" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-7">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-7" aria-expanded="false" aria-controls="accordion-collapse-body-7">
                                        @include('icons.dashboard.conversation')
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 7:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Dialogar para desenvolver as relações laborais') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-7" class="hidden" aria-labelledby="accordion-collapse-heading-7">
                                    <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['851']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg1 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['852']))
                            <div id="accordion-collapse8" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-8">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-8" aria-expanded="false" aria-controls="accordion-collapse-body-8">
                                        @include('icons.dashboard.user-group', ['width' => 50, 'height' => 51])
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 8:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Fortalecer a igualdade e a diversidade') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-8" class="hidden" aria-labelledby="accordion-collapse-heading-8">
                                    <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['852']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg1 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['853']))
                            <div id="accordion-collapse9" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-9">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-9" aria-expanded="false" aria-controls="accordion-collapse-body-9">
                                        @include('icons.dashboard.well-being-security')
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 9:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Garantir a cultura de bem-estar e segurança') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-9" class="hidden" aria-labelledby="accordion-collapse-heading-9">
                                    <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['853']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg1 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['854']))
                            <div id="accordion-collapse10" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-10">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-10" aria-expanded="false" aria-controls="accordion-collapse-body-10">
                                        @include('icons.dashboard.human-rights')
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 10:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Respeitar e promover os direitos humanos') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-10" class="hidden" aria-labelledby="accordion-collapse-heading-10">
                                    <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['854']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg1 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(isset($charts['goal']['855']))
                            <div id="accordion-collapse11" data-accordion="collapse">
                                <h2 id="accordion-collapse-heading-11">
                                    <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg1/[0.15] active:bg-esg1/[0.15]" data-accordion-target="#accordion-collapse-body-11" aria-expanded="false" aria-controls="accordion-collapse-body-11">
                                        @include('icons.dashboard.community', ['width' => 50, 'height' => 51])
                                        <div class="w-3/4">
                                            <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 11:') }} </p>
                                            <p class="text-base font-bold text-esg8"> {{ __('Pertencer às comunidades locais') }} </p>
                                        </div>
                                        <div class="">
                                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </button>
                                </h2>
                                <div id="accordion-collapse-body-11" class="hidden" aria-labelledby="accordion-collapse-heading-11">
                                    <div class="p-5 font-light border-0 bg-esg1/[0.15] rounded-b-2xl">
                                        @php $text = explode("\n", $charts['goal']['855']); @endphp
                                        @foreach ($text as $line)
                                            <div class="flex mt-4">
                                                <div class="text-esg1 text-2xl">
                                                    <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg1 text-esg1"></span>
                                                </div>
                                                <div class="pl-3 inline-block">{{ $line }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                    @endif


                    @if(isset($charts['goal']['856']) || isset($charts['goal']['857']) || isset($charts['goal']['858']) || isset($charts['goal']['859']) || isset($charts['goal']['860']) || isset($charts['goal']['861']) || isset($charts['goal']['862']) || isset($charts['goal']['863']) || isset($charts['goal']['864']))
                    <div class="text-center">
                        <h2 class="font-encodesans text-2xl font-bold leading-10 text-esg8 mt-10">{{__('Governança')}}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">

                        @if(isset($charts['goal']['856']))
                        <div id="accordion-collapse12" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-12">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-12" aria-expanded="false" aria-controls="accordion-collapse-body-12">
                                    @include('icons.dashboard.stackholders')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 12:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Criar valor para todos os stakeholders') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-12" class="hidden" aria-labelledby="accordion-collapse-heading-12">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['856']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif


                        @if(isset($charts['goal']['857']))
                        <div id="accordion-collapse13" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-13">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-13" aria-expanded="false" aria-controls="accordion-collapse-body-13">
                                    @include('icons.dashboard.report', ['width' => 50, 'height' => 51])
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 13:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Divulgar o desempenho em sustentabilidade') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-13" class="hidden" aria-labelledby="accordion-collapse-heading-13">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['857']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($charts['goal']['858']))
                        <div id="accordion-collapse14" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-14">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-14" aria-expanded="false" aria-controls="accordion-collapse-body-14">
                                    @include('icons.dashboard.transparent-gov')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 14:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Comunicar com responsabilidade e transparência') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-14" class="hidden" aria-labelledby="accordion-collapse-heading-14">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['858']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($charts['goal']['859']))
                        <div id="accordion-collapse15" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-15">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-15" aria-expanded="false" aria-controls="accordion-collapse-body-15">
                                    @include('icons.dashboard.management')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 15:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Aperfeiçoar as práticas de gestão') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-15" class="hidden" aria-labelledby="accordion-collapse-heading-15">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['859']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($charts['goal']['860']))
                        <div id="accordion-collapse16" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-16">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-16" aria-expanded="false" aria-controls="accordion-collapse-body-16">
                                    @include('icons.dashboard.ethics')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 16:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Viver a ética em todas as decisões') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-16" class="hidden" aria-labelledby="accordion-collapse-heading-16">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['860']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($charts['goal']['861']))
                        <div id="accordion-collapse17" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-17">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-17" aria-expanded="false" aria-controls="accordion-collapse-body-17">
                                    @include('icons.dashboard.data-protection')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 17:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Assegurar a integridade dos sistemas de informação') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-17" class="hidden" aria-labelledby="accordion-collapse-heading-17">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['861']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($charts['goal']['862']))
                        <div id="accordion-collapse18" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-18">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-18" aria-expanded="false" aria-controls="accordion-collapse-body-18">
                                    @include('icons.dashboard.supply-chain')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 18:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Alavancar a sustentabilidade através da cadeia de fornecimento') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-18" class="hidden" aria-labelledby="accordion-collapse-heading-18">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['862']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($charts['goal']['863']))
                        <div id="accordion-collapse19" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-19">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-19" aria-expanded="false" aria-controls="accordion-collapse-body-19">
                                    @include('icons.dashboard.growth')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 19:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Acelerar a jornada para a sustentabilidade') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-19" class="hidden" aria-labelledby="accordion-collapse-heading-19">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['863']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($charts['goal']['864']))
                        <div id="accordion-collapse20" data-accordion="collapse">
                            <h2 id="accordion-collapse-heading-20">
                                <button type="button" class="flex items-center justify-between w-full p-5 rounded-t-2xl font-medium text-left text-gray-500 border-0 focus:bg-esg3/[0.15] active:bg-esg3/[0.15]" data-accordion-target="#accordion-collapse-body-20" aria-expanded="false" aria-controls="accordion-collapse-body-20">
                                    @include('icons.dashboard.agreement')
                                    <div class="w-3/4">
                                        <p class="text-base font-normal text-esg8 mt-4"> {{ __('Objetivo 20:') }} </p>
                                        <p class="text-base font-bold text-esg8"> {{ __('Expandir a Carta de Princípios do BCSD Portugal') }} </p>
                                    </div>
                                    <div class="">
                                        <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-20" class="hidden" aria-labelledby="accordion-collapse-heading-20">
                                <div class="p-5 font-light border-0 bg-esg3/[0.15] rounded-b-2xl">
                                    @php $text = explode("\n", $charts['goal']['864']); @endphp
                                    @foreach ($text as $line)
                                        <div class="flex mt-4">
                                            <div class="text-esg3 text-2xl">
                                                <span class="w-2 h-2 relative -top-2.5 rounded-full inline-block bg-esg3 text-esg3"></span>
                                            </div>
                                            <div class="pl-3 inline-block">{{ $line }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
