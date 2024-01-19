@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' => (tenant()->id == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? 'bg-esg4' : 'bg-esg6'])

@php
$icon_color = (tenant()->id == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? '8' : '27';
$text_color = (tenant()->id == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? 'text-esg8' : 'text-esg27';
$categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
$genderIconUrl = global_asset('images/icons/genders/{id}.svg');
@endphp
@push('body')
<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', () => {

        var colorid = '{!! tenant()->id !!}',
            color_code = (colorid == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? twConfig.theme.colors.esg8 : twConfig.theme.colors.esg27;

        // Global ESG
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
                    color: color_code
                },
                tooltip: {
                    enabled: false,
                },
            },
            rotation: 270, // start angle in degrees
            circumference: 180, // sweep angle in degrees
            cutout: '80%'
        };

        var data = {
            datasets: [{
                data: {!! json_encode($charts['global_esg']) !!},
                backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg7],
                hoverBackgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg7],
                borderRadius: 8,
                borderWidth: 0,
                spacing: 0,
            }],
        };

        var config = {
            type: 'doughnut',
            data: data,
            options
        };

        var ctx = document.getElementById('esg_global');
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
                    color: color_code
                },
                tooltip: {
                    enabled: false,
                },
            },
            rotation: 270, // start angle in degrees
            circumference: 180, // sweep angle in degrees
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
                backgroundColor: [ twConfig.theme.colors.esg7],
                hoverBackgroundColor: [ twConfig.theme.colors.esg7],
                borderRadius: 8,
                borderWidth: 0,
                spacing: 0,
            }],
        };

        var config = {
            type: 'doughnut',
            data: data,
            options
        };

        var ctx = document.getElementById('esg_global2');
        new Chart(ctx, config);


        var categories = [
            @foreach ($mainCategories as $category)
                {'icon': '{{ $categoryIconUrl }}'.replace('{id}', {{ $category->id }})},
            @endforeach
        ];

        var options = {
            chart: {
                type: 'radialBar',
                sparkline: {
                    enabled: true
                },
                toolbar: {
                    show: false,
                },
                background: twConfig.theme.colors.esg6,
                height: 548
            },
            title: {
                text: '{{ __('ESG Maturity Level by Categories') }}',
                style: {
                    fontSize:  '18px',
                    fontWeight:  twConfig.theme.fontWeight.bold,
                    fontFamily:  twConfig.theme.fontFamily.encodesans,
                    color: color_code,
                }
            },
            series: {!! json_encode($charts['main_categories_esg']) !!},
            colors: [twConfig.theme.colors.esg2, twConfig.theme.colors.esg1, twConfig.theme.colors.esg3],
            legend: {
                show: true,
                fontSize:  '16px',
                fontWeight:  twConfig.theme.fontWeight.semibold,
                fontFamily:  twConfig.theme.fontFamily.encodesans,
                position: 'bottom',
                horizontalAlign: 'left',
                offsetX: 0,
                labels: {
                    useSeriesColors: true,
                },
                itemMargin: {
                    vertical: 25
                },
                markers: false,
                formatter: function(seriesName, opts) {
                    // TODO :: Download does not work with images ícones
                    return '<img src="' + categories[opts.seriesIndex].icon + '" class="mr-2.5 inline h-6 font-normal"> <span class="{{ $text_color }} font-encodesans text-base font-bold">' + opts.w.globals.series[opts.seriesIndex] + '%</span>';
                },
            },
            plotOptions: {
                radialBar: {
                    offsetY: 4,
                    offsetX: -100,
                    hollow: {
                        size: '30%',
                    },
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: twConfig.theme.colors.esg7,
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            offsetY: -2,
                            fontSize: '36px',
                            fontWeight:  twConfig.theme.fontWeight.bold,
                            fontFamily:  twConfig.theme.fontFamily.encodesans,
                            color: color_code,
                        }
                    }
                }
            },
        };

        //var chart = new ApexCharts(document.querySelector("#esg_category_total"), options);
        //chart.render();


        esgCategoryTotal(0);


        var options =  {
            plugins: {
                title: {
                    display: true,
                    text: '{{ __('Consolidated Maturity Level by sector') }}',
                    font: {
                        family: twConfig.theme.fontFamily.encodesans,
                        size: 18,
                        weight: twConfig.theme.fontWeight.bold,
                    },
                    color: color_code
                },
                tooltip: {
                    enabled: false,
                }
            },
            rotation: 270, // start angle in degrees
            circumference: 180, // sweep angle in degrees
            cutout: '33%',
        };

        var data = {
            datasets: [
                {
                    data: [1],
                    backgroundColor: [twConfig.theme.colors.esg7],
                    hoverBackgroundColor: [twConfig.theme.colors.esg7],
                    borderRadius: 8,
                    borderWidth: 0,
                    spacing: 0,
                },
                {
                    weight: 0.2,
                },
                {
                    data: [1],
                    backgroundColor: [twConfig.theme.colors.esg7],
                    hoverBackgroundColor: [twConfig.theme.colors.esg7],
                    borderRadius: 8,
                    borderWidth: 0,
                    spacing: 0,
                },
                {
                    weight: 0.2,
                },
                {
                    data: [1],
                    backgroundColor: [twConfig.theme.colors.esg7],
                    hoverBackgroundColor: [twConfig.theme.colors.esg7],
                    borderRadius: 8,
                    borderWidth: 0,
                    spacing: 0,
                },
            ],
        };

        var config = {
            type: 'doughnut',
            data: data,
            options: options
        };

        var ctx = document.getElementById('esg_category_total2');
        new Chart(ctx, config);


        var options =  {
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: '{{ __('Gender equality') }}',
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
            afterInit: function (chart, args, options) {
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
                                    <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}">
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
            }
        ];


        @if($charts['gender_equility_total'])
            let configGenderEquilityTotal = {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($charts['gender_equility_total']['labels']) !!},
                    datasets:[{
                        data: {!! json_encode($charts['gender_equility_total']['series']) !!},
                        backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22, twConfig.theme.colors.esg21],
                        borderRadius: 8,
                        borderWidth: 0,
                        spacing: 0,
                        hoverOffset: 1
                    }]
                },
                options,
                plugins
            };

            new Chart(document.getElementById("gender_total_chart"), configGenderEquilityTotal);
        @endif

        @if($charts['gender_equility_employees'])
            let configGenderEquilityEmployees = {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($charts['gender_equility_employees']['labels']) !!},
                    datasets:[{
                        data: {!! json_encode($charts['gender_equility_employees']['series']) !!},
                        backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22, twConfig.theme.colors.esg21],
                        borderRadius: 8,
                        borderWidth: 0,
                        spacing: 0,
                        hoverOffset: 1
                    }]
                },
                options,
                plugins
            };

            new Chart(document.getElementById("gender_collaborators_chart"), configGenderEquilityEmployees);
        @endif

        @if($charts['gender_equility_management'])
            let configGenderEquilityManagement = {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($charts['gender_equility_management']['labels']) !!},
                    datasets:[{
                        data: {!! json_encode($charts['gender_equility_management']['series']) !!},
                        backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22, twConfig.theme.colors.esg21],
                        borderRadius: 8,
                        borderWidth: 0,
                        spacing: 0,
                        hoverOffset: 1
                    }]
                },
                options,
                plugins
            };

            new Chart(document.getElementById("gender_management_chart"), configGenderEquilityManagement);
        @endif

        @if($charts['gender_equility_c_level'])
            let configGenderCLevel = {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($charts['gender_equility_c_level']['labels']) !!},
                    datasets:[{
                        data: {!! json_encode($charts['gender_equility_c_level']['series']) !!},
                        backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg22, twConfig.theme.colors.esg21],
                        borderRadius: 8,
                        borderWidth: 0,
                        spacing: 0,
                        hoverOffset: 1
                    }]
                },
                options,
                plugins
            };

            new Chart(document.getElementById("gender_c_level_chart"), configGenderCLevel);
        @endif

        @if ($charts['action_plan'])
        var data = {!! $charts['action_plan']['series'] !!};
        let actionPlanData = data.map(function(value,index) { return value['data'][0]; });
        let actionPlanLabel = data.map(function(value,index) { return value['name']; });

        var plugins = [{
            afterDatasetsDraw: function(bubbleChart, easing) {
                var ctx = bubbleChart.ctx;

                bubbleChart.data.datasets.forEach(function(dataset, i) {
                    var meta = bubbleChart.getDatasetMeta(i);
                    if (meta.type == "bubble") {
                        meta.data.forEach(function(element, index) {
                            var dataString = dataset.label[index].toString() ;

                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.font =  "15px bold " + twConfig.theme.fontFamily.encodesans;
                            ctx.fillStyle = color_code;

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
            while(elements.length > 0){
                elements[0].parentNode.removeChild(elements[0]);
            }

            tooltipEl = document.createElement('div');
            tooltipEl.classList.add("action_tootltip");
            tooltipEl.style.background = twConfig.theme.colors.esg4;
            tooltipEl.style.fontFamily = twConfig.theme.fontFamily.encodesans;
            tooltipEl.style.fontWeight = 500;
            tooltipEl.style.borderRadius = '8px';
            tooltipEl.style.color = twConfig.theme.colors.esg29;
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
            const {chart, tooltip} = context;
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

            const {offsetLeft: positionX, offsetTop: positionY} = chart.canvas;

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
                        padding: {top: 20, left: 00, right: 00, bottom: 0}
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
                        padding: {top: 0, left: 0, right: 0, bottom: 20}
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

    var esg_category_total = '';

    function esgCategoryTotal(type = 0) {
        var colorid = '{!! tenant()->id !!}',
                color_code = (colorid == '1379696d-fd48-4a45-9c3a-175bb5d6a736') ? twConfig.theme.colors.esg8 : twConfig.theme.colors.esg27;
        var options =  {
            plugins: {
                title: {
                    display: true,
                    text: '{{ __('Consolidated Maturity Level by sector') }}',
                    font: {
                        family: twConfig.theme.fontFamily.encodesans,
                        size: 18,
                        weight: twConfig.theme.fontWeight.bold,
                    },
                    color: color_code
                },
                tooltip: {
                    enabled: false,
                }
            },
            rotation: 270, // start angle in degrees
            circumference: 180, // sweep angle in degrees
            cutout: '33%',
        };

        var data = {
            datasets: [
                {
                    data: {!! json_encode($charts['main_categories_esg'][0]) !!},
                    backgroundColor: type == 0 || type == 1 ? [twConfig.theme.colors.esg2, twConfig.theme.colors.esg7] : [twConfig.theme.colors.esg11, twConfig.theme.colors.esg7],
                    hoverBackgroundColor: type == 0 || type == 1 ? [twConfig.theme.colors.esg2, twConfig.theme.colors.esg7] : [twConfig.theme.colors.esg11, twConfig.theme.colors.esg7],
                    borderRadius: 8,
                    borderWidth: 0,
                    spacing: 0,
                },
                {
                    weight: 0.2,
                },
                {
                    data: {!! json_encode($charts['main_categories_esg'][1]) !!},
                    backgroundColor: type == 0 || type == 2 ? [twConfig.theme.colors.esg1, twConfig.theme.colors.esg7] : [twConfig.theme.colors.esg11, twConfig.theme.colors.esg7],
                    hoverBackgroundColor: type == 0 || type == 2 ? [twConfig.theme.colors.esg1, twConfig.theme.colors.esg7] : [twConfig.theme.colors.esg11, twConfig.theme.colors.esg7],
                    borderRadius: 8,
                    borderWidth: 0,
                    spacing: 0,
                },
                {
                    weight: 0.2,
                },
                {
                    data: {!! json_encode($charts['main_categories_esg'][2]) !!},
                    backgroundColor: type == 0 || type == 3 ? [twConfig.theme.colors.esg3, twConfig.theme.colors.esg7] : [twConfig.theme.colors.esg11, twConfig.theme.colors.esg7],
                    hoverBackgroundColor: type == 0 || type == 3 ? [twConfig.theme.colors.esg3, twConfig.theme.colors.esg7] : [twConfig.theme.colors.esg11, twConfig.theme.colors.esg7],
                    borderRadius: 8,
                    borderWidth: 0,
                    spacing: 0,
                },
            ],
        };

        var config = {
            type: 'doughnut',
            data: data,
            options: options
        };

        if(esg_category_total != '')
            esg_category_total.destroy();

        var ctx = document.getElementById('esg_category_total');
        esg_category_total = new Chart(ctx, config);
    }

    document.addEventListener('alpine:init', () => {
        Alpine.bind('highlightCategory', (type) => ({
            type: 'button',

            '@click'() {
                esgCategoryTotal(type);
            },
        }))
    });
</script>
@endpush

@section('content')

<div class="px-4 lg:px-0">
    <div class="max-w-7xl mx-auto">
        <div class="mt-5">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="p-3 xl:p-10 sm:col-span-1">
                    <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                        <canvas id="esg_global2" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                        <canvas id="esg_global" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                        <div id="esg_global_value" class="{{ $text_color }} absolute bottom-[50px] w-full text-center text-4xl font-bold">{!! $charts['global_esg'][0] !!}%</div>
                    </div>
                </div>

                <div id="esg_category" x-data="{visible: 'all'}" class="relative xl:p-10 col-span-1 grid grid-cols-1 sm:grid-cols-2">
                    <div class="h-full w-full justify-center">
                        <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                            <p class="w-full absolute mt-8 {{ $text_color }} text-center"> {{__('to your company based on your sector')}} </p>
                            <canvas id="esg_category_total2" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                            <canvas id="esg_category_total" class="absolute m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]"></canvas>
                        </div>
                    </div>

                    <div class="w-full ml-0 sm:w-48 sm:ml-14 sm:mt-14">
                        <div class="top-20 right-0 z-10 mr-15 flex justify-between w-full sm:w-48">
                            <button x-bind="highlightCategory(0)" :class="visible === 'all' ? 'text-esg28' : '{{ $text_color }}'" @click="visible = 'all'">{{ __('All') }}</button>
                            @foreach ($mainCategories as $category)
                                <button x-bind="highlightCategory({{$category->id}})" :class="{'text-esg28': visible === 'esg_category_{{ $category->id }}'}" @click="visible = 'esg_category_{{ $category->id }}'">
                                    <template x-if="visible === 'esg_category_{{ $category->id }}'">
                                        @include('icons.categories.' . $category->id)
                                    </template>

                                    <template x-if="visible !== 'esg_category_{{ $category->id }}'">
                                        @include('icons.categories.' . $category->id, ['color' => color($icon_color)])
                                    </template>
                                </button>
                            @endforeach
                        </div>
                        <div class=" {{ $text_color }} mt-5 mb-10 z-10 text-sm w-full sm:w-48">
                            <table class="w-full" x-show="visible === 'all'">
                                <tbody>
                                    @foreach ($charts['main_categories_table'] as $category)
                                        <tr x-data="{mouseover:false}" @mouseover="mouseover = true" @mouseover.away="mouseover = false" @if(isset($category->description)) data-tooltip-target="tooltip-esgbycat-{{ $category->id }}" data-tooltip-trigger="hover" data-tooltip-placement="right" @endif>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-l-md' : ''" class="px-2 py-1">{{ $category->name }}</td>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-r-md' : ''" class="text-right px-2">{{ is_numeric($category->maturity) ? round($category->maturity) . '%' : $category->maturity }}</td>
                                        </tr>

                                        @if(isset($category->description))
                                            <div id="tooltip-esgbycat-{{ $category->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip max-w-[14rem]">
                                                {{ $category->description }}
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            <table class="w-full" x-show="visible === 'esg_category_1'">
                                <tbody>
                                    @foreach ($charts['subcategories1_table'] as $subCategory)
                                        <tr x-data="{mouseover:false}" @mouseover="mouseover = true" @mouseover.away="mouseover = false" @if(isset($subCategory->description)) data-tooltip-target="tooltip-esgbycat-{{ $subCategory->id }}" data-tooltip-trigger="hover" data-tooltip-placement="right" @endif>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-l-md' : ''" class="px-2 py-1">{{ $subCategory->name }}</td>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-r-md' : ''" class="text-right px-2">{{ is_numeric($subCategory->maturity) ? round($subCategory->maturity) . '%' : $subCategory->maturity }}</td>
                                        </tr>

                                        @if(isset($subCategory->description))
                                            <div id="tooltip-esgbycat-{{ $subCategory->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip max-w-[14rem]">
                                                {{ $subCategory->description }}
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            <table class="w-full" x-show="visible === 'esg_category_2'">
                                <tbody>
                                    @foreach ($charts['subcategories2_table'] as $subCategory)
                                        <tr x-data="{mouseover:false}" @mouseover="mouseover = true" @mouseover.away="mouseover = false" @if(isset($subCategory->description)) data-tooltip-target="tooltip-esgbycat-{{ $subCategory->id }}" data-tooltip-trigger="hover" data-tooltip-placement="right" @endif>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-l-md' : ''" class="px-2 py-1">{{ $subCategory->name }}</td>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-r-md' : ''" class="text-right px-2">{{ is_numeric($subCategory->maturity) ? round($subCategory->maturity) . '%' : $subCategory->maturity }}</td>
                                        </tr>

                                        @if(isset($subCategory->description))
                                            <div id="tooltip-esgbycat-{{ $subCategory->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip max-w-[14rem]">
                                                {{ $subCategory->description }}
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            <table class="w-full" x-show="visible === 'esg_category_3'">
                                <tbody>
                                    @foreach ($charts['subcategories3_table'] as $subCategory)
                                        <tr x-data="{mouseover:false}" @mouseover="mouseover = true" @mouseover.away="mouseover = false" @if(isset($subCategory->description)) data-tooltip-target="tooltip-esgbycat-{{ $subCategory->id }}" data-tooltip-trigger="hover" data-tooltip-placement="right" @endif>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-l-md' : ''" class="px-2 py-1">{{ $subCategory->name }}</td>
                                            <td :class="mouseover ? 'bg-esg5/[0.25] rounded-r-md' : ''" class="text-right px-2">{{ is_numeric($subCategory->maturity) ? round($subCategory->maturity) . '%' : $subCategory->maturity }}</td>
                                        </tr>

                                        @if(isset($subCategory->description))
                                            <div id="tooltip-esgbycat-{{ $subCategory->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip max-w-[14rem]">
                                                {{ $subCategory->description }}
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if ($charts['action_plan'])
                <div x-data="{showExtraLegend: false}" class="md:col-span-1 lg:p-5 xl:p-10">
                    <div @mouseover="showExtraLegend = true" @mouseover.away="showExtraLegend = false" class="relative w-full">
                        <div class="h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px]">
                            <div></div>
                            <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                            <div class="{{ $text_color }} absolute left-[31px] top-[45px] rotate-90 text-4xl">
                                @include('icons/arrow', ['class' => 'rotate-180'])
                            </div>
                            <div class="{{ $text_color }} absolute left-[310px] top-[300px] sm:left-[465px] sm:top-[448px] text-4xl">
                                @include('icons/arrow')
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
                                <th class="p-2">{{ __('Impact') }}</th>
                                <th class="p-2">{{ __('Toolkit') }}</th>
                            </tr>
                        </thead>
                        <tbody class="font-medium">
                            @foreach ($charts['action_plan_table'] as $initiative)
                                <tr class="text-xs action_plan_tr action_plan_{{$loop->index + 1}}">
                                    <td class="p-2">{{ $loop->index + 1 }}</td>
                                    <td class="p-2 text-center" data-t="{{$initiative->id}}">@if ($initiative->category_id)@include('icons.categories.' . ($initiative->category->parent_id ?? $initiative->category_id))@else - @endif</td>
                                    <td class="p-2">{{ $initiative->name }}</td>
                                    <td class="p-2"><div class="w-full bg-esg5 rounded-full p-1 text-center">+{{ $initiative->impact }}%</div></td>
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
                <div id="gender" x-data="{visible: 'total'}" class="col-span-1 pt-10 xl:p-10 lg:pt-5 xl:pt-0">
                    <div class="relative col-span-2">
                        <div class="{{ $text_color }} text-bold relative sm:absolute top-[280px] sm:top-11 right-0 z-10 flex sm:w-72 justify-between text-sm">
                            @if($charts['gender_equility_total']) <button class="" :class="{'text-esg28': visible === 'total'}" @click="visible = 'total'">{{ __('Total') }}</button> @endif
                            @if($charts['gender_equility_employees']) <button class="" :class="{'text-esg28': visible === 'collaborators'}" @click="visible = 'collaborators'">{{ __('Employees') }}</button> @endif
                            @if($charts['gender_equility_management']) <button class="" :class="{'text-esg28': visible === 'management'}" @click="visible = 'management'">{{ __('Management') }}</button> @endif
                            @if($charts['gender_equility_c_level']) <button class="" :class="{'text-esg28': visible === 'c_level'}" @click="visible = 'c_level'">{{ __('C-Level') }}</button> @endif
                        </div>

                        <div id="gender_total" x-show="visible == 'total'" class="grid grid-cols-1 sm:grid-cols-2">
                            <div> <canvas id="gender_total_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                            <div id="gender_total_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                        </div>

                        <div id="gender_collaborators" x-show="visible == 'collaborators'" class="grid grid-cols-1 sm:grid-cols-2">
                            <div> <canvas id="gender_collaborators_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                            <div id="gender_collaborators_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                        </div>

                        <div id="gender_management" x-show="visible == 'management'" class="grid grid-cols-1 sm:grid-cols-2">
                            <div> <canvas id="gender_management_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                            <div id="gender_management_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                        </div>

                        <div id="gender_c_level" x-show="visible == 'c_level'" class="grid grid-cols-1 sm:grid-cols-2">
                            <div> <canvas id="gender_c_level_chart" class="m-auto !h-[250px] !w-[250px]"></canvas> </div>
                            <div id="gender_c_level_chart-legend" class="align-middle ml-0 md:ml-3 pt-12 sm:pt-20"></div>
                        </div>
                    </div>

                    <div class="col-span-1 grid grid-cols-2 pt-5">
                        @if ($charts['co2_emissions'])
                        <div class="col-span-1 mt-20">
                            <div class="{{ $text_color }} font-encodesans flex items-center pb-5 text-lg font-bold"><span>@include('icons.categories.1')</span> <span class="pl-2">{{ __('E1 Emissions (ton CO2)') }}</span></div>
                            <div class="text-esg25 font-encodesans text-5xl font-bold">{{ $charts['co2_emissions'] }}</div>
                        </div>
                        @endif
                        @if ($charts['water_withdrawal'])
                        <div class="col-span-1 mt-20">
                            <div class="{{ $text_color }} font-encodesans flex items-center pb-5 text-lg font-bold"><span>@include('icons.categories.1')</span> <span class="pl-2">{{ __('Water withdrawal (ML)') }}</span></div>
                            <div class="text-esg25 font-encodesans text-5xl font-bold">{{ $charts['water_withdrawal'] }}</div>
                        </div>
                        @endif
                        @if ($charts['turnover_rate'])
                        <div class="col-span-1 mt-20">
                            <div class="{{ $text_color }} font-encodesans flex items-center pb-5 text-lg font-bold"><span>@include('icons.categories.2')</span> <span class="pl-2">{{ __('Turnover rate') }}</span></div>
                            <div class="text-esg24 font-encodesans text-5xl font-bold">{{ $charts['turnover_rate'] }}%</div>
                        </div>
                        @endif
                        @if ($charts['trained_employees'])
                        <div class="col-span-1 mt-20">
                            <div class="{{ $text_color }} font-encodesans flex items-center pb-5 text-lg font-bold"><span>@include('icons.categories.3')</span> <span class="pl-2">{{ __('Employees Trained') }}</span></div>
                            <div class="text-esg26 font-encodesans text-5xl font-bold">{{ $charts['trained_employees'] }}%</div>
                        </div>
                        @endif
                    </div>
                </div>
                @if ($charts['sdgs_top5'])
                <div id="ods" class="sm:col-span-1 mt-12 lg:mt-0 lg:p-5 xl:p-10 xl:pt-0">
                    <div class="{{ $text_color }} font-encodesans pb-5 text-lg font-bold">
                        {{ __('Sustainable Development Goals (SDGs) | Top 5') }}
                    </div>

                    <div class="flex flex-wrap justify-center">
                        <div class="relative w-[350px] h-[350px] ">
                            <div class="absolute w-full h-full flex items-center justify-center">
                                <img class="w-40" src="{{ global_asset('images/icons/sdgs/sustainable-development-goals.png') }}">
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#e3253e] clip-path-no-poverty" title="{{ $sdgs->find(1)->name }}">
                                @if (! in_array(1, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[11.1rem] top-[0.75rem] rotate-[13deg]">
                                    @include('icons.sdgs.1')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#dda83a] clip-path-zero-hunger" title="{{ $sdgs->find(2)->name }}">
                                @if (! in_array(2, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[14.2rem] top-[1.75rem] rotate-[34deg]">
                                    @include('icons.sdgs.2')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#4c9f38] clip-path-good-health" title="{{ $sdgs->find(3)->name }}">
                                @if (! in_array(3, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[16.6rem] top-[4.15rem] rotate-[56deg]">
                                    @include('icons.sdgs.3')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#c5192d] clip-path-quality-education" title="{{ $sdgs->find(4)->name }}">
                                @if (! in_array(4, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[18.2rem] top-[7rem] rotate-[75deg]">
                                    @include('icons.sdgs.4')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#ff3a21] clip-path-gender-equality" title="{{ $sdgs->find(5)->name }}">
                                @if (! in_array(5, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[18.4rem] top-[10.5rem] rotate-[95deg]">
                                    @include('icons.sdgs.5')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#26bde2] clip-path-clean-water" title="{{ $sdgs->find(6)->name }}">
                                @if (! in_array(6, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[17.5rem] top-[13.4rem] rotate-[118deg]">
                                    @include('icons.sdgs.6')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#fcc30b] clip-path-clean-energy" title="{{ $sdgs->find(7)->name }}">
                                @if (! in_array(7, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[15.4rem] top-[16.2rem] rotate-[139deg]">
                                    @include('icons.sdgs.7')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#a21942] clip-path-decent-work" title="{{ $sdgs->find(8)->name }}">
                                @if (! in_array(8, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[12.6rem] top-[17.8rem] rotate-[155deg]">
                                    @include('icons.sdgs.8')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#fd6925] clip-path-industry" title="{{ $sdgs->find(9)->name }}">
                                @if (! in_array(9, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[9.5rem] top-[18.5rem] rotate-[179deg]">
                                    @include('icons.sdgs.9')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#dd1367] clip-path-reduced-inequalities" title="{{ $sdgs->find(10)->name }}">
                                @if (! in_array(10, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[6.1rem] top-[17.8rem] rotate-[202deg]">
                                    @include('icons.sdgs.10')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#fd9d24] clip-path-sustainable-cities" title="{{ $sdgs->find(11)->name }}">
                                @if (! in_array(11, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[3.3rem] top-[16.1rem] rotate-[222deg]">
                                    @include('icons.sdgs.11')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#bf8b2e] clip-path-responsible-consumption" title="{{ $sdgs->find(12)->name }}">
                                @if (! in_array(12, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[1.3rem] top-[13.3rem] rotate-[244deg]">
                                    @include('icons.sdgs.12')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#3f7e44] clip-path-climate-action" title="{{ $sdgs->find(13)->name }}">
                                @if (! in_array(13, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[.5rem] top-[10.2rem] rotate-[264deg]">
                                    @include('icons.sdgs.13')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#0a97d9] clip-path-life-water" title="{{ $sdgs->find(14)->name }}">
                                @if (! in_array(14, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[0.75rem] top-[6.8rem] rotate-[287deg]">
                                    @include('icons.sdgs.14')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#56c02b] clip-path-life-land" title="{{ $sdgs->find(15)->name }}">
                                @if (! in_array(15, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[2.3rem] top-[3.9rem] rotate-[307deg]">
                                    @include('icons.sdgs.15')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#00689d] clip-path-peace-justice" title="{{ $sdgs->find(16)->name }}">
                                @if (! in_array(16, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[4.9rem] top-[1.8rem] rotate-[328deg]">
                                    @include('icons.sdgs.16')
                                </div>
                            </div>

                            <div class="absolute w-[350px] h-[350px] bg-[#19486a] clip-path-partnerships-goals" title="{{ $sdgs->find(17)->name }}">
                                @if (! in_array(17, $charts['sdgs_top5']))<div class="absolute z-10 h-full w-full bg-black opacity-50"></div>@endif
                                <div class="absolute w-12 h-12 left-[7.8rem] top-[0.75rem] rotate-[350deg]">
                                    @include('icons.sdgs.17')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if (strtolower(tenant()->name) === 'demo')
        <div class="mt-12 pb-12">
            <p class="{{ $text_color }} font-encodesans text-center text-xl font-bold">{{ __('If you want to continue your journey with us, you will have access to more than 100 charts to complete your dashboard.') }}</p>
            <p class="text-esg28 font-encodesans text-center text-xl font-bold">{{ __('Because at C-MORE we translate sustainability into business. Do we count on you?') }}</p>
        </div>
        @endif
    </div>
</div>

@endsection
