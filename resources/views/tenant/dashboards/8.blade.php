@extends('layouts.tenant', ['title' => __('Dashboard'), 'mainBgColor' => 'bg-esg4'])

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

            #launcher,
            #footer {
                visibility: hidden;
            }

            .print {
                page-break-after: avoid;
            }
        }

        @page {
            size: A4;
            /* DIN A4 standard, Europe */
            /* margin: 70pt 60pt 70pt; */
        }
    </style>
    <script nonce="{{ csp_nonce() }}">
        var color_green = '#008131',
            color_gray = '#f6f6f6';

        var color_male = "#058894",
            color_female = "#06A5B4",
            color_other = "#83D2DA";

        var social_male = "#21A6E8",
            social_female = "#C5A8FF",
            social_other = "#02C6A1";

        var enviroment_color1 = "#008131",
            enviroment_color2 = "#99CA3C",
            enviroment_color3 = "#6AD794",
            enviroment_color4 = "#98BDA6";

        var age_30 = "#E86321",
            age_30_50 = "#FBB040",
            age_50 = "#FDC97B";

        var governance_color = '#06A5B4';

        document.addEventListener('DOMContentLoaded', () => {
            var color_code = twConfig.theme.colors.esg7;

            // Working charts
            esgGlobal();

            esgCategoryTotal();

            @if (isset($charts['main']['action_plan']))
                var data = {!! $charts['main']['action_plan']['series'] !!};
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
                            min: {!! $charts['main']['action_plan']['xaxis']['min'] !!},
                            max: {!! $charts['main']['action_plan']['xaxis']['max'] !!}
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
                            min: {!! $charts['main']['action_plan']['yaxis']['min'] !!},
                            max: {!! $charts['main']['action_plan']['yaxis']['max'] !!}
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

            // Pie charts
            @if ($charts['climate_change']['energy_consumption']['base_line_year'])
                pieCharts(
                    {!! json_encode($charts['climate_change']['energy_consumption']['base_line_year']['labels']) !!},
                    {!! json_encode($charts['climate_change']['energy_consumption']['base_line_year']['dataset']) !!},
                    'energy_consumption_baseline',
                    [enviroment_color1, enviroment_color2],
                    '{{ __($charts['climate_change']['energy_consumption']['base_line_year']['unit']) }}'
                );
            @endif

            @if ($charts['climate_change']['energy_consumption']['reporting_year'])
                pieCharts(
                    {!! json_encode($charts['climate_change']['energy_consumption']['reporting_year']['labels']) !!},
                    {!! json_encode($charts['climate_change']['energy_consumption']['reporting_year']['dataset']) !!},
                    'energy_consumption_reporting',
                    [enviroment_color1, enviroment_color2],
                    '{{ __($charts['climate_change']['energy_consumption']['reporting_year']['unit']) }}'
                );
            @endif


            @if ($charts['workers_in_org']['contracted_workers'])
                pieCharts(
                    {!! json_encode($charts['workers_in_org']['contracted_workers']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['contracted_workers']['dataset']) !!},
                    'contracted_workers',
                    [social_male, social_female, social_other],
                    '{{ __($charts['workers_in_org']['contracted_workers']['unit']) }}'
                );
            @endif

            @if ($charts['workers_in_org']['outsourced_workers'])
                pieCharts(
                    {!! json_encode($charts['workers_in_org']['outsourced_workers']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['outsourced_workers']['dataset']) !!},
                    'outsourced_workers',
                    [social_male, social_female, social_other],
                    '{{ __($charts['workers_in_org']['outsourced_workers']['unit']) }}'
                );
            @endif

            @if ($charts['workers_in_org']['workers_minorities_percentage'])
                pieCharts(
                    {!! json_encode($charts['workers_in_org']['workers_minorities_percentage']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['workers_minorities_percentage']['dataset']) !!},
                    'workers_minorities_percentage',
                    ['#FBB040', '#F6F6F6'],
                );
            @endif

            @if ($charts['conduct_policies_corporate_culture']['highest_governance_body'])
                pieCharts(
                    {!! json_encode($charts['conduct_policies_corporate_culture']['highest_governance_body']['labels']) !!},
                    {!! json_encode($charts['conduct_policies_corporate_culture']['highest_governance_body']['dataset']) !!},
                    'gender_high_governance_body',
                    [social_male, social_female, social_other],
                    '{{ __($charts['conduct_policies_corporate_culture']['highest_governance_body']['unit']) }}'
                );
            @endif

            @if ($charts['biodiversity_and_ecosystems']['operations_located'])
                pieCharts(
                    {!! json_encode($charts['biodiversity_and_ecosystems']['operations_located']['labels']) !!},
                    {!! json_encode($charts['biodiversity_and_ecosystems']['operations_located']['dataset']) !!},
                    'operation_located',
                    [color_green, color_gray]
                );
            @endif

            @if ($charts['use_of_resources']['waste_distribution'])
                pieCharts(
                    {!! json_encode($charts['use_of_resources']['waste_distribution']['labels']) !!},
                    {!! json_encode($charts['use_of_resources']['waste_distribution']['dataset']) !!},
                    'water_distribution',
                    ['#008131', '#99CA3C', '#98BDA6'],
                    't'
                );
            @endif

            // BAR CHARTS
            @if ($charts['climate_change']['energy_from_non_renewable_sources_consumed'])
                barCharts(
                    {!! json_encode($charts['climate_change']['energy_from_non_renewable_sources_consumed']['labels']) !!},
                    {!! json_encode($charts['climate_change']['energy_from_non_renewable_sources_consumed']['dataset']) !!},
                    'energy_non_renewable',
                    ["#008131"],
                    'y'
                );
            @endif

            @if ($charts['climate_change']['energy_from_renewable_sources_consumed'])
                barCharts(
                    {!! json_encode($charts['climate_change']['energy_from_renewable_sources_consumed']['labels']) !!},
                    {!! json_encode($charts['climate_change']['energy_from_renewable_sources_consumed']['dataset']) !!},
                    'energy_renewable',
                    ["#99CA3C"],
                    'y'
                );
            @endif

            @if ($charts['climate_change']['ghg_scope_1_emissions'])
                barCharts(
                    {!! json_encode($charts['climate_change']['ghg_scope_1_emissions']['labels']) !!},
                    {!! json_encode($charts['climate_change']['ghg_scope_1_emissions']['dataset']) !!},
                    'emissions',
                    [enviroment_color1, enviroment_color2],
                    'x'
                );
            @endif


            @if ($charts['climate_change']['ghg_scope_2_emissions'])
                barCharts(
                    {!! json_encode($charts['climate_change']['ghg_scope_2_emissions']['labels']) !!},
                    {!! json_encode($charts['climate_change']['ghg_scope_2_emissions']['dataset']) !!},
                    'emissions2',
                    [enviroment_color1, enviroment_color2],
                    'x'
                );
            @endif


            @if ($charts['climate_change']['ghg_scope_3_emissions'])
                barCharts(
                    {!! json_encode($charts['climate_change']['ghg_scope_3_emissions']['labels']) !!},
                    {!! json_encode($charts['climate_change']['ghg_scope_3_emissions']['dataset']) !!},
                    'emissions3',
                    [enviroment_color1, enviroment_color2],
                    'x'
                );
            @endif

            @if ($charts['workers_in_org']['category_of_contract'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['category_of_contract']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['category_of_contract']['dataset']) !!},
                    'category_contract',
                    [social_male, social_female, social_other],
                    'x',
                    'multi'
                );
            @endif

            @if ($charts['workers_in_org']['type_of_contract'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['type_of_contract']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['type_of_contract']['dataset']) !!},
                    'type_contract',
                    [social_male, social_female, social_other],
                    'x',
                    'multi'
                );
            @endif

            @if ($charts['workers_in_org']['contracted_age_distribution'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['contracted_age_distribution']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['contracted_age_distribution']['dataset']) !!},
                    'contracted_worker_age',
                    [age_30, age_30_50, age_50]
                );
            @endif

            @if ($charts['workers_in_org']['outsourced_age_distribution'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['outsourced_age_distribution']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['outsourced_age_distribution']['dataset']) !!},
                    'outsourced_worker_age',
                    [age_30, age_30_50, age_50],
                );
            @endif

            @if ($charts['workers_in_org']['workers_minorities'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['workers_minorities']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['workers_minorities']['dataset']) !!},
                    'workers_minorities',
                    ['#FBB040'],
                    'y'
                );
            @endif

            @if ($charts['workers_in_org']['middle_management_gender_contracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['middle_management_gender_contracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['middle_management_gender_contracted']['dataset']) !!},
                    'middle_management_gender_contracted',
                    [social_male, social_female, social_other]
                );
            @endif
            @if ($charts['workers_in_org']['middle_management_age_contracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['middle_management_age_contracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['middle_management_age_contracted']['dataset']) !!},
                    'middle_management_age_contracted',
                    [age_30, age_30_50, age_50]
                );
            @endif

            @if ($charts['workers_in_org']['top_management_gender_contracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['top_management_gender_contracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['top_management_gender_contracted']['dataset']) !!},
                    'top_management_gender_contracted',
                    [social_male, social_female, social_other]
                );
            @endif

            @if ($charts['workers_in_org']['top_management_age_contracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['top_management_age_contracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['top_management_age_contracted']['dataset']) !!},
                    'top_management_age_contracted',
                    [age_30, age_30_50, age_50]
                );
            @endif


            @if ($charts['workers_in_org']['middle_management_gender_outsourced'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['middle_management_gender_outsourced']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['middle_management_gender_outsourced']['dataset']) !!},
                    'middle_management_gender_outsourced',
                    [social_male, social_female, social_other]
                );
            @endif
            @if ($charts['workers_in_org']['middle_management_age_outsourced'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['middle_management_age_outsourced']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['middle_management_age_outsourced']['dataset']) !!},
                    'middle_management_age_outsourced',
                    [age_30, age_30_50, age_50]
                );
            @endif

            @if ($charts['workers_in_org']['top_management_gender_outsourced'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['top_management_gender_outsourced']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['top_management_gender_outsourced']['dataset']) !!},
                    'top_management_gender_outsourced',
                    [social_male, social_female, social_other]
                );
            @endif

            @if ($charts['workers_in_org']['top_management_age_outsourced'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['top_management_age_outsourced']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['top_management_age_outsourced']['dataset']) !!},
                    'top_management_age_outsourced',
                    [age_30, age_30_50, age_50]
                );
            @endif

            @if ($charts['workers_in_org']['hourly_earning_variation'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['hourly_earning_variation']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['hourly_earning_variation']['dataset']) !!},
                    'earning_variation',
                    [social_male, social_female, social_other]
                );
            @endif

            @if ($charts['workers_in_org']['salary_variation'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['salary_variation']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['salary_variation']['dataset']) !!},
                    'salary_variation',
                    [
                        [
                            social_male, social_female, social_other
                        ],
                        [
                            social_male, social_female, social_other
                        ],
                        [
                            social_male, social_female, social_other
                        ],
                    ],
                    'x',
                    'multi'
                );
            @endif

            @if ($charts['workers_in_org']['distribution_of_hiring_and_layoffs'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['distribution_of_hiring_and_layoffs']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['distribution_of_hiring_and_layoffs']['dataset']) !!},
                    'hiring_layoffs',
                    [age_30, age_30_50, age_50],
                    'x',
                    'multi'
                );
            @endif

            @if ($charts['water_and_marine']['water_consumed'])
                barCharts(
                    {!! json_encode($charts['water_and_marine']['water_consumed']['labels']) !!},
                    {!! json_encode($charts['water_and_marine']['water_consumed']['dataset']) !!},
                    'water_consumed',
                    null,
                    'x',
                    'multi'
                );
            @endif

            @if ($charts['use_of_resources']['final_destination_applied'])
                barCharts(
                    {!! json_encode($charts['use_of_resources']['final_destination_applied']['labels']) !!},
                    {!! json_encode($charts['use_of_resources']['final_destination_applied']['dataset']) !!},
                    'final_destination_applied',
                    '#444444',
                    'x',
                    'multi',
                    'stack'
                );
            @endif

            @if ($charts['relations_with_suppliers']['type_of_suppliers'])
                barCharts(
                    {!! json_encode($charts['relations_with_suppliers']['type_of_suppliers']['labels']) !!},
                    {!! json_encode($charts['relations_with_suppliers']['type_of_suppliers']['dataset']) !!},
                    'supplier_types',
                    [governance_color],
                    'y'
                );
            @endif

            @if ($charts['relations_with_suppliers']['suppliers_by_continent'])
                barCharts(
                    {!! json_encode($charts['relations_with_suppliers']['suppliers_by_continent']['labels']) !!},
                    {!! json_encode($charts['relations_with_suppliers']['suppliers_by_continent']['dataset']) !!},
                    'supplier_continent',
                    [governance_color],
                    'y'
                );
            @endif

            @if ($charts['relations_with_suppliers']['suppliers_by_industry_sector'])
                barCharts(
                    {!! json_encode($charts['relations_with_suppliers']['suppliers_by_industry_sector']['labels']) !!},
                    {!! json_encode($charts['relations_with_suppliers']['suppliers_by_industry_sector']['dataset']) !!},
                    'supplier_industry_sector',
                    [governance_color],
                    'y'
                );
            @endif

            @if ($charts['relations_with_suppliers']['timings_payment_to_suppliers'])
                barCharts(
                    {!! json_encode($charts['relations_with_suppliers']['timings_payment_to_suppliers']['labels']) !!},
                    {!! json_encode($charts['relations_with_suppliers']['timings_payment_to_suppliers']['dataset']) !!},
                    'supplier_payment_timing',
                    [governance_color],
                    'x',
                    'multi'
                );
            @endif

            @if ($charts['corruption_prevention_detection']['cases_of_corruption'])
                barCharts(
                    {!! json_encode($charts['corruption_prevention_detection']['cases_of_corruption']['labels']) !!},
                    {!! json_encode($charts['corruption_prevention_detection']['cases_of_corruption']['dataset']) !!},
                    'corruption_bribery',
                    [governance_color],
                    'x',
                    'multi'
                );
            @endif
        });

        //function to hide show dataaset on checkbox update
        function updateChart(data, chartID) {
            const chartToUpdate = Chart.getChart(chartID);
            const isDataShown = chartToUpdate.isDatasetVisible(data.value);
            if (isDataShown === false) {
                chartToUpdate.show(data.value);
            }
            if (isDataShown === true) {
                chartToUpdate.hide(data.value);
            }
        }

        function hexToRgbA(hex) {
            if ((typeof hex === 'object' || Array.isArray(hex)) && hex !== null) {
                const newArr = hex.map(element => {
                    var c;
                    if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(element)) {
                        c = element.substring(1).split('');
                        if (c.length == 3) {
                            c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                        }
                        c = '0x' + c.join('');
                        return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',0.1)';
                    }
                });
                return newArr;
            } else {
                var c;
                if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
                    c = hex.substring(1).split('');
                    if (c.length == 3) {
                        c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                    }
                    c = '0x' + c.join('');
                    return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',0.1)';
                }
                return hex;
            }
        }

        // Common function for bar charts
        function barCharts(labels, data, id, barColor, view = 'x', type = "single", stack = null) {

            var extra = {
                id: 'centerText',
                afterInit: function(chart, args, options) {
                    if (stack != null) {
                        const chartId = chart.canvas.id;
                        const legendId = `${chartId}-legend`;
                        let html = '<div class="grid w-full grid-cols-3">';

                        let sumOfData = 0;
                        // Running the for loop
                        for (let i = 0; i < chart.data.datasets.length; i++) {
                            for (let j = 0; j < chart.data.datasets[i].data.length; j++) {
                                sumOfData = sumOfData + parseFloat(chart.data.datasets[i].data[j]);
                            }
                        }

                        chart.data.datasets.forEach((datavale, i) => {
                            let total = data;
                            let labelText = chart.data.labels[i];
                            let value = datavale;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];

                            let sumOfDatasetData = 0;
                            for (let k = 0; k < datavale.data.length; k++) {
                                sumOfDatasetData = sumOfDatasetData + parseFloat(datavale.data[k]);
                            }

                            let calcPercentage = Math.round(sumOfDatasetData * 100 / sumOfData);
                            let percentag = !isNaN(calcPercentage) ? (calcPercentage + '%') : '0%';
                            html += `

                                        <div class="col-span-2 flex items-center">
                                            <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${datavale.backgroundColor}"></div>
                                            <div class="inline-block text-sm text-esg8">${datavale.label}</div>
                                        </div>
                                        <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${datavale.backgroundColor}" class="text-sm font-bold">${formatNumber(sumOfDatasetData)}</span>  (${percentag})</div>
                                `;

                        })
                        html += '</div>';

                        document.getElementById(legendId).innerHTML = html;
                    }
                }
            };

            if (type == 'single') {
                var data = {
                    labels: labels,
                    datasets: [{
                        data: data,
                        lineTension: 0.3,
                        fill: true,
                        backgroundColor: barColor,
                        borderColor: '{{ color(6) }}',
                        maxBarThickness: 48
                    }],
                };
            } else {
                if (data.length > 0) {
                    data.forEach(function(obj, index) {
                        data[index]['maxBarThickness'] = 48;
                    });
                }
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
                        color: barColor,
                        backgroundColor: stack == null ? hexToRgbA(barColor) : null,
                        padding: 4,
                        padding: 4,
                        borderRadius: 4,
                        font: {
                            weight: 'bold'
                        },
                        align: (view == 'y' ? 'end' : 'top'),
                        formatter: function(value, context) {

                            if (stack != null) {

                                const datasetArray = [];
                                const datasetArrayIndexes = [];

                                data.datasets.forEach((dataset) => {
                                    if (dataset.data[context.dataIndex] != undefined) {
                                        datasetArray.push(dataset.data[context.dataIndex]);
                                    }
                                });

                                function totalSum(total, datapoint) {
                                    return parseFloat(total) + parseFloat(datapoint);
                                }

                                if (context.datasetIndex === datasetArray.length - 1) {
                                    let sum = datasetArray.reduce(totalSum, 0);
                                    return formatNumber(sum);
                                } else {
                                    return '';
                                }
                            } else {
                                return formatNumber(value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        stacked: (stack != null ? true : false),
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
                        stacked: (stack != null ? true : false),
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
            };


            if (document.getElementById(id)) {
                return new Chart(document.getElementById(id).getContext("2d"), {
                    type: 'bar',
                    data: data,
                    options: chartOptions,
                    plugins: [ChartDataLabels, extra]
                });

            }

            return;
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
                    let text = 0;
                    if (chart.canvas.id == 'workers_minorities_percentage' || chart.canvas.id == 'operation_located') {
                        text = data[0] + '%';
                    } else {
                        text = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                        ''
                        text = Math.round(text);
                    }
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
                                    gender = '{{ __('Male') }}';
                                    break;
                                case '{{ __('female') }}':
                                    gender = '{{ __('Female') }}';
                                    break;
                                case '{{ __('other') }}':
                                    gender = '{{ __('Other') }}';
                                    break;
                            }

                            const sum = total.reduce((accumulator, current) => parseFloat(accumulator) +
                                parseFloat(current));
                            let calcPercentage = Math.round(value * 100 / sum);
                            let percentag = !isNaN(calcPercentage) ? (calcPercentage + '%') : '0%';

                            if (id != 'energy_consumption_reporting' && id !=
                                'energy_consumption_baseline') {
                                html += `
                                        <div class="grid w-full grid-cols-3">
                                            <div class="col-span-2 flex items-center">
                                                <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}"></div>
                                                <div class="inline-block text-sm text-esg8">${labelText}</div>
                                            </div>
                                            <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${formatNumber(value)})</div>
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

                        if (document.getElementById(legendId) !=
                            undefined) // if this legendDiv not found means that chart don't show legends.
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

            if (document.getElementById(id)) {
                return new Chart(document.getElementById(id), config);
            }

            return;
        }

        function esgGlobal() {
            // Global ESG
            var options = {
                plugins: {
                    title: {
                        display: false,
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
                rotation: 270, // start angle in degrees
                circumference: 180, // sweep angle in degrees
                cutout: '80%'
            };

            var data = {
                datasets: [{
                    data: {{ json_encode($charts['main']['global_esg_maturity_level']['values']) }},
                    backgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg7],
                    hoverBackgroundColor: [twConfig.theme.colors.esg5, twConfig.theme.colors.esg7],
                    borderRadius: 20,
                    borderWidth: 0,
                    spacing: 0,
                }],
            };

            var config = {
                type: 'doughnut',
                data: data,
                options
            };

            if (document.getElementById('maturity_level_first')) {
                new Chart(document.getElementById('maturity_level_first').getContext("2d"), config);
            }

            var options = {
                plugins: {
                    title: {
                        display: false,
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
                    backgroundColor: [twConfig.theme.colors.esg7],
                    hoverBackgroundColor: [twConfig.theme.colors.esg7],
                    borderRadius: 20,
                    borderWidth: 0,
                    spacing: 0,
                }],
            };

            var config = {
                type: 'doughnut',
                data: data,
                options
            };

            if (document.getElementById('maturity_level_second')) {
                new Chart(
                    document.getElementById('maturity_level_second').getContext("2d"), config);
            }
        }

        function esgCategoryTotal() {

            var options = {
                plugins: {
                    title: {
                        display: false,
                        text: '{{ __('Consolidated Maturity Level by sector') }}',
                        font: {
                            family: twConfig.theme.fontFamily.encodesans,
                            size: 18,
                            weight: twConfig.theme.fontWeight.bold,
                        },
                        color: twConfig.theme.colors.esg27
                    },
                    tooltip: {
                        enabled: false,
                    }
                },
                rotation: 270, // start angle in degrees
                circumference: 180, // sweep angle in degrees
                cutout: '50%',
            };

            var data = {
                datasets: [{
                        data: [1],
                        backgroundColor: [twConfig.theme.colors.esg7],
                        hoverBackgroundColor: [twConfig.theme.colors.esg7],
                        borderRadius: 20,
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
                        borderRadius: 20,
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
                        borderRadius: 20,
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

            if (document.getElementById('maturity_level_category2')) {
                new Chart(
                    document.getElementById('maturity_level_category2').getContext("2d"),
                    config
                );
            }

            var options = {
                plugins: {
                    title: {
                        display: false,
                        text: '{{ __('Consolidated Maturity Level by sector') }}',
                        font: {
                            family: twConfig.theme.fontFamily.encodesans,
                            size: 18,
                            weight: twConfig.theme.fontWeight.bold,
                        },
                        color: twConfig.theme.colors.esg27
                    },
                    tooltip: {
                        enabled: false,
                    }
                },
                rotation: 270, // start angle in degrees
                circumference: 180, // sweep angle in degrees
                cutout: '50%',
            };

            var data = {
                datasets: [{
                        data: {{ json_encode($charts['main']['global_esg_maturity_level_by_category'][0]['dataset']) }},
                        backgroundColor: [twConfig.theme.colors.esg2, twConfig.theme.colors.esg7],
                        hoverBackgroundColor: [twConfig.theme.colors.esg2, twConfig.theme.colors.esg7],
                        borderRadius: 20,
                        borderWidth: 0,
                        spacing: 0,
                    },
                    {
                        weight: 0.2,
                    },
                    {
                        data: {{ json_encode($charts['main']['global_esg_maturity_level_by_category'][1]['dataset']) }},
                        backgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg7],
                        hoverBackgroundColor: [twConfig.theme.colors.esg1, twConfig.theme.colors.esg7],
                        borderRadius: 20,
                        borderWidth: 0,
                        spacing: 0,
                    },
                    {
                        weight: 0.2,
                    },
                    {
                        data: {{ json_encode($charts['main']['global_esg_maturity_level_by_category'][2]['dataset']) }},
                        backgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg7],
                        hoverBackgroundColor: [twConfig.theme.colors.esg3, twConfig.theme.colors.esg7],
                        borderRadius: 20,
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

            if (document.getElementById('maturity_level_category')) {
                new Chart(
                    document.getElementById('maturity_level_category').getContext("2d"),
                    config
                );
            }
        }
    </script>
@endpush

@section('content')

    @php
        $subpointGender = json_encode([['color' => 'bg-[#21A6E8]', 'text' => __('Male')], ['color' => 'bg-[#C5A8FF]', 'text' => __('Female')], ['color' => 'bg-[#02C6A1]', 'text' => __('Other')]]);
        $subpointAge = json_encode([['color' => 'bg-[#E86321]', 'text' => __('Less than 30')], ['color' => 'bg-[#FBB040]', 'text' => __('Between 30 and 50')], ['color' => 'bg-[#FDC97B]', 'text' => __('More than 50')]]);
    @endphp
    <div class="px-4 lg:px-0">
        <div class="mt-10">
            <div class="w-full flex justify-between">
                <div class="">
                    <a href="{{ route('tenant.questionnaires.index') }}"
                        class="text-esg5 w-fit text-2xl font-bold flex flex-row gap-2 items-center">
                        @include('icons.back', [
                            'color' => color(5),
                            'width' => '20',
                            'height' => '16',
                        ])
                        {{ __('In-depth/Assess') }}
                    </a>
                </div>
                <div class="hidden">
                    <x-buttons.btn-icon-text class="bg-esg5 text-esg4 !rounded-md"
                        @click="location.href='{{ route('tenant.dashboards', ['questionnaire' => $questionnaire->id]) . '?print=true' }}'">
                        <x-slot name="buttonicon">
                        </x-slot>
                        <span class="ml-2 normal-case text-sm font-medium py-0.5">{{ __('View Report') }}</span>
                    </x-buttons.btn-icon-text>
                </div>
            </div>
        </div>

        <div class="" x-data="{ main: true, environment: false, social: false, governance: false }">
            <div class="max-w-2xl mx-auto mt-10 text-center">
                <label class="text-xl font-bold text-esg5"> {{ __('Welcome to the questionnaire’s dashboard!') }} </label>
                <p class="mt-4 text-lg text-esg16">
                    {{ __('This is the data visualization of the answers given on the questionnaire. Select a tab and a category to control what is being showed.') }}
                </p>
            </div>

            <div class="my-8 grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="grid place-content-center border rounded-md w-full shadow cursor-pointer"
                    x-on:click="main= true, environment= false, social=false, governance=false"
                    :class="main ? 'bg-esg6/10 border-esg6 text-esg6 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="main"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Main') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border rounded-md shadow cursor-pointer"
                    x-on:click="main= false, environment= true, social=false, governance=false"
                    :class="environment ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Environment"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer ">{{ __('Environment') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer"
                    x-on:click="main= false, environment= false, social=true, governance=false"
                    :class="social ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Social"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer ">{{ __('Social') }}</label>
                    </div>
                </div>

                <div class="grid place-content-center border  rounded-md shadow cursor-pointer"
                    x-on:click="main= false, environment= false, social=false, governance=true"
                    :class="governance ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'">
                    <div class="flex items-center cursor-pointer">
                        <label for="Governance"
                            class="w-full py-4 ml-2 text-base font-medium cursor-pointer">{{ __('Governance') }}</label>
                    </div>
                </div>
            </div>

            <div class="my-4 border border-esg7/30 rounded-md"></div>

            {{-- Main --}}
            <div x-show="main">
                <div class="bg-esg5/10 rounded-3xl p-8 mt-8">

                    @if ($questionnaire->type->id != 19)
                        <div class="mt-5">
                            @livewire('dashboard.charts.radar-filter', [
                                $questionnaire,
                                __('Global CSRD Maturity'),
                                'global_csrd_maturity',
                                [],
                                [
                                    'type' => 'simple',
                                    'chart' => [
                                        'legend' => [
                                            'display' => false,
                                        ],
                                    ],
                                ],
                            ])
                        </div>

                        <div class="mt-5">
                            @livewire('dashboard.charts.radar-filter', [
                                $questionnaire,
                                __('CSRD Maturity by Category'),
                                'csrd_maturity_by_category',
                                [
                                    'options_1' => ['ambiente', 'social', 'governanca'],
                                    'options_2' => ['gestao-dos-criterios-esg', 'identificacao-e-avaliacao', 'integracao-na-estrategia', 'resiliencia'],
                                ],
                                [
                                    'type' => 'complete',
                                    'chart' => [
                                        'legend' => [
                                            'display' => true,
                                        ],
                                    ],
                                ],
                            ])
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">
                        @if ($questionnaire->type->id != 19)
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Global ESG Maturity Level')]) }}" type="none" class="!h-min"
                                contentplacement="justify-center">
                                <div class="relative m-auto h-[270px] w-[270px] xl:h-[300px] xl:w-[300px]">
                                    <x-charts.donut id="maturity_level_second"
                                        class="absolute m-auto !h-[270px] !w-[270px] xl:!h-[300px] xl:!w-[300px]" />
                                    <x-charts.donut id="maturity_level_first"
                                        class="absolute m-auto !h-[270px] !w-[270px] xl:!h-[300px] xl:!w-[300px]" />
                                    <div id="esg_global_value"
                                        class="text-esg5 absolute bottom-[90px] xl:bottom-[70px] w-full text-center text-4xl font-bold">
                                        {{ $charts['main']['global_esg_maturity_level']['values'][0] }}
                                        {{ $charts['main']['global_esg_maturity_level']['unit'] }}</div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Global ESG Maturity Level by Category')]) }}" type="none"
                                class="!h-auto" contentplacement="justify-center">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="">
                                        <div class="relative h-[270px] w-[270px]">
                                            <x-charts.donut id="maturity_level_category2"
                                                class="absolute m-auto !h-[270px] !w-[270px]" />
                                            <x-charts.donut id="maturity_level_category"
                                                class="absolute m-auto !h-[270px] !w-[270px]" />
                                        </div>
                                    </div>

                                    <div class="grid pl-2 place-content-center">
                                        <div class="grid w-full grid-cols-3">
                                            <div class="col-span-2 flex items-center">
                                                <div class="mr-4 inline-block rounded-full p-2 text-left bg-esg2"></div>
                                                <div class="inline-block text-sm text-esg8">{{ __('Enviroment') }}</div>
                                            </div>
                                            <div class="text-right text-sm text-esg8 leading-8"> <span
                                                    class="text-esg2 text-sm font-bold">{{ $charts['main']['global_esg_maturity_level_by_category'][0]['maturity_final'] }}
                                                    {{ $charts['main']['global_esg_maturity_level_by_category'][0]['unit'] }}</span>
                                            </div>
                                        </div>

                                        <div class="grid w-full grid-cols-3">
                                            <div class="col-span-2 flex items-center">
                                                <div class="mr-4 inline-block rounded-full p-2 text-left bg-esg1"></div>
                                                <div class="inline-block text-sm text-esg8">{{ __('Social') }}</div>
                                            </div>
                                            <div class="text-right text-sm text-esg8 leading-8"> <span
                                                    class="text-esg1 text-sm font-bold">{{ $charts['main']['global_esg_maturity_level_by_category'][1]['maturity_final'] }}
                                                    {{ $charts['main']['global_esg_maturity_level_by_category'][1]['unit'] }}</span>
                                            </div>
                                        </div>

                                        <div class="grid w-full grid-cols-3">
                                            <div class="col-span-2 flex items-center">
                                                <div class="mr-4 inline-block rounded-full p-2 text-left bg-esg3"></div>
                                                <div class="inline-block text-sm text-esg8">{{ __('Governance') }}</div>
                                            </div>
                                            <div class="text-right text-sm text-esg8 leading-8"> <span
                                                    class="text-esg3 text-sm font-bold">{{ $charts['main']['global_esg_maturity_level_by_category'][2]['maturity_final'] }}
                                                    {{ $charts['main']['global_esg_maturity_level_by_category'][2]['unit'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        @endif

                        @if ($charts['main']['action_plan'])
                            @php $text = json_encode([__('Action Plans - Prority Matrix')]); @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto">
                                <div x-data="{ showExtraLegend: false }" class="md:col-span-1 lg:p-5 xl:p-10 ">
                                    <div @mouseover="showExtraLegend = true" @mouseover.away="showExtraLegend = false"
                                        class="relative w-full">
                                        <div class="h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px]">
                                            <div></div>
                                            <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                            <div class="text-esg8 absolute left-[31px] top-[15px] rotate-90 text-4xl">
                                                @include('icons.arrow', [
                                                    'class' => 'rotate-180',
                                                    'fill' => color(7),
                                                ])
                                            </div>
                                            <div
                                                class="text-esg8 absolute left-[310px] top-[300px] sm:left-[465px] sm:top-[448px] text-4xl">
                                                @include('icons.arrow', ['fill' => color(7)])
                                            </div>
                                            <div x-show="showExtraLegend"
                                                class="absolute left-[50px] top-[60px] text-sm text-esg9">
                                                {{ __('Highly Recommended') }}</div>
                                            <div x-show="showExtraLegend"
                                                class="absolute left-[50px] bottom-[60px] text-sm text-esg9">
                                                {{ __('Recommended') }}</div>
                                            <div x-show="showExtraLegend"
                                                class="absolute right-[60px] top-[60px] text-sm text-esg9">
                                                {{ __('High Criticality') }}</div>
                                            <div x-show="showExtraLegend"
                                                class="absolute right-[60px] bottom-[60px] text-sm text-esg9">
                                                {{ __('High Priority') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        @endif

                        @if ($charts['main']['action_plan_table'])
                            @php $text = json_encode([__('Action Plans')]); @endphp
                            <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto">
                                <div id="action_list"
                                    class="md:col-span-1 lg:mt-0 h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px] overflow-x-auto">

                                    <table class="text-esg8/70 font-encodesans w-full table-auto">
                                        <thead class="">
                                            <tr class="text-xs font-medium uppercase">
                                                <th class="p-2 text-left">#</th>
                                                <th class="p-2 text-left">@include('icons.category', ['color' => '#444444b3'])</th>
                                                <th class="p-2 text-left">{{ __('Action') }}</th>
                                                <th class="p-2 text-center">{{ __('Impact') }}</th>
                                                <th class="p-2 text-center">{{ __('Toolkit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-medium">
                                            @foreach ($charts['main']['action_plan_table'] as $initiative)
                                                <tr class="text-xs action_plan_tr action_plan_{{ $loop->index + 1 }}">
                                                    <td class="p-2">{{ $loop->index + 1 }}</td>
                                                    <td class="p-2" data-t="{{ $initiative->id }}">
                                                        @if ($initiative->category_id)
                                                            @include(
                                                                'icons.categories.' .
                                                                    ($initiative->category->parent_id ??
                                                                        $initiative->category_id))
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="p-2">{{ $initiative->name }}</td>
                                                    <td class="p-2 text-center">
                                                        <span
                                                            class="bg-esg5 text-white text-xs px-4 py-1 rounded-full">{{ $initiative->impact }}</span>
                                                    </td>
                                                    <td class="p-2 text-center">
                                                        @if (Storage::disk('toolkits')->exists($initiative->id . '.pdf'))
                                                            <x-buttons.a-icon
                                                                href="{{ tenantPrivateAsset($initiative->id . '.pdf', 'toolkits') }}">
                                                                @include('icons.download', [
                                                                    'class' => 'inline-block',
                                                                ])
                                                            </x-buttons.a-icon>
                                                        @else
                                                            <x-buttons.a-icon
                                                                href="{{ tenantPrivateAsset('toolkit-desenvolvimento.pdf', 'toolkits') }}">
                                                                @include('icons.download', [
                                                                    'class' => 'inline-block',
                                                                ])
                                                            </x-buttons.a-icon>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        @endif

                    </div>

                    <div class="mt-5">
                        @php $text = json_encode([__('Documentation')]); @endphp
                        <x-cards.card-dashboard-version1-withshadow text="{{ $text }}" class="!h-auto"
                            contentplacement="none">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach ($dashboardData['main']['documentation']['list'] as $list)
                                    <div class="flex items-center justify-between p-2 mb-3 border-b border-b-esg7/40">
                                        <label for="checkbox-website"
                                            class="font-encodesans text-sm font-normal text-esg8">{{ $list['label'] }}</label>
                                        <div class="">
                                            @if ($list['value'] == 'yes' || $list['value'] == '1')
                                                @include('icons.checkbox', ['color' => color(5)])
                                            @else
                                                @include('icons.no')
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            {{-- Envviroment --}}
            <div x-show="environment" x-data="{ climate: true, pollution: false, water: false, ecosystems: false, waste: false }">
                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="climate ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = true, pollution= false, water=false, ecosystems=false, waste=false">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="climate">
                                @include('icons.dashboard.8.globalwarming', ['color' => color(2)])
                            </template>
                            <template x-if="!climate">
                                @include('icons.dashboard.8.globalwarming', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Climate change') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="pollution ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = false, pollution= true, water=false, ecosystems=false, waste=false">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="pollution">
                                @include('icons.dashboard.8.factory', ['color' => color(2)])
                            </template>
                            <template x-if="!pollution">
                                @include('icons.dashboard.8.factory', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Pollution') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="water ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = false, pollution= false, water=true, ecosystems=false, waste=false">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="water">
                                @include('icons.dashboard.8.water', ['color' => color(2)])
                            </template>
                            <template x-if="!water">
                                @include('icons.dashboard.8.water', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Water and marine resources') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="ecosystems ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = false, pollution= false, water=false, ecosystems=true, waste=false">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="ecosystems">
                                @include('icons.dashboard.8.ecosystem', ['color' => color(2)])
                            </template>
                            <template x-if="!ecosystems">
                                @include('icons.dashboard.8.ecosystem', ['color' => color(16)])
                            </template>
                            <label for="main" class="text-base">{{ __('Biodiversity and ecosystems') }}</label>
                        </div>
                    </div>

                    <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                        :class="waste ? 'bg-esg2/10 border-esg2 text-esg2 font-bold' : 'text-esg16'"
                        x-on:click="climate = false, pollution= false, water=false, ecosystems=false, waste=true">
                        <div class="flex items-center w-full justify-center gap-5">
                            <template x-if="waste">
                                @include('icons.dashboard.8.waste', ['color' => color(2)])
                            </template>
                            <template x-if="!waste">
                                @include('icons.dashboard.8.waste', ['color' => color(16)])
                            </template>
                            <label for="main"
                                class="text-base">{{ __('Use of resources and circular economy') }}</label>
                        </div>
                    </div>
                </div>

                {{-- SECTION: climate --}}
                <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="climate" x-data="{ show: false }">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('High climate impact activities')]) }}" class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    @if (count($dashboardData['climate_change']['high_climate_impact_activities']['checkbox_lables']) > 0)
                                        <label for="checkbox-website"
                                            class="font-encodesans text-4xl font-medium text-esg8">
                                            {{ count($dashboardData['climate_change']['high_climate_impact_activities']['checkbox_lables']) }}
                                            <span class="text-base text-esg8">{{ __('activities') }}</span>

                                            <span x-on:click="show = !show"
                                                class="text-base text-esg16 text-sm underline">{{ __('Show') }}</span>

                                        </label>
                                        <div class="" :class="{ 'hidden': !show, 'block': show }">
                                            <x-cards.cards-checkbox-list :list="$dashboardData['climate_change'][
                                                'high_climate_impact_activities'
                                            ]['checkbox_lables']" color="bg-esg2" />
                                        </div>
                                    @else
                                        <label for="checkbox-website"
                                            class="font-encodesans text-4xl font-medium text-esg8">
                                            -
                                        </label>
                                    @endif
                                </div>
                                <div class="-mt-7">
                                    @include('icons.dashboard.8.enviroment.cima', ['color' => color(2)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ __('Operates in the fossil fuel sector') }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-checkbox :value="$dashboardData['climate_change']['operates_in_the_fossil_fuel_sector'][
                                        'values'
                                    ]" :color="color(2)" />
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div class="grid grid-cols-1 gap-5">
                            <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                                contentplacement="none">
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ __('Energy consumption monitoring') }}</p>
                                    </div>
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$dashboardData['climate_change']['energy_consumption_monitoring'][
                                            'values'
                                        ]" :color="color(2)" />
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Energy intensity')]) }}"
                                class="!h-auto" contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <x-cards.cards-value-unit :value="$dashboardData['climate_change']['energy_intensity']['values']" :unit="__($dashboardData['climate_change']['energy_intensity']['unit'])" :isNumber=true />
                                    </div>
                                    <div class="-mt-7">
                                        @include('icons.dashboard.gestao-energia', ['color' => color(2)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                                contentplacement="none">
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ __('Policy for energy consumption reduction') }}</p>
                                    </div>
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$dashboardData['climate_change'][
                                            'policy_for_energy_consumption_reduction'
                                        ]['values']" :color="color(2)" />
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                        <div class="">
                            @php
                                $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Renewable')], ['color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable')]]);
                            @endphp
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('energy consumption')]) }}" type="none" class="!h-auto"
                                subpoint="{{ $subpoint }}" contentplacement="none">
                                @if (
                                    !empty($charts['climate_change']['energy_consumption']['base_line_year']) &&
                                        !empty($charts['climate_change']['energy_consumption']['reporting_year']))
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @endif
                                @if ($charts['climate_change']['energy_consumption']['base_line_year'])
                                    <div class="text-center w-full">
                                        <label class="text-xs font-medium text-esg8">
                                            {{ __('Baseline year: ' . $charts['climate_change']['energy_consumption']['base_line_year']['year']) }}
                                        </label>
                                        <x-charts.donut id="energy_consumption_baseline"
                                            class="m-auto !h-[180px] !w-[180px] mt-5" />
                                        <div class="grid content-center mt-5" id="energy_consumption_baseline-legend">
                                        </div>
                                    </div>
                                @endif
                                @if ($charts['climate_change']['energy_consumption']['reporting_year'])
                                    <div class="text-center  w-full">
                                        <label class="text-xs font-medium text-esg8">
                                            {{ __('Reporting period: ' . $charts['climate_change']['energy_consumption']['reporting_year']['year']) }}
                                        </label>
                                        <x-charts.donut id="energy_consumption_reporting"
                                            class="m-auto !h-[180px] !w-[180px] mt-5" />
                                        <div class="grid content-center mt-5" id="energy_consumption_reporting-legend">
                                        </div>
                                    </div>
                                @endif
                                @if (
                                    !empty($charts['climate_change']['energy_consumption']['base_line_year']) &&
                                        !empty($charts['climate_change']['energy_consumption']['reporting_year']))
                        </div>
                        @endif

                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                @if ($charts['climate_change']['energy_from_non_renewable_sources_consumed'])
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('energy from non-renewable sources consumed')]) }}"
                        class="!h-min !mt-5">
                        <x-charts.bar id="energy_non_renewable" class="m-auto relative !h-full !w-full" unit="MWh"
                            subinfo="{{ json_encode([['value' => $charts['climate_change']['energy_from_non_renewable_sources_consumed']['total'], 'unit' => $charts['climate_change']['energy_from_non_renewable_sources_consumed']['unit']]]) }}" />
                    </x-cards.card-dashboard-version1-withshadow>
                @endif

                @if ($charts['climate_change']['energy_from_renewable_sources_consumed'])
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('energy from renewable sources consumed')]) }}" class="!h-min !mt-5">
                        <x-charts.bar id="energy_renewable" class="m-auto relative !h-full !w-full" unit="MWh"
                            subinfo="{{ json_encode([['value' => $charts['climate_change']['energy_from_renewable_sources_consumed']['total'], 'unit' => $charts['climate_change']['energy_from_renewable_sources_consumed']['unit']]]) }}" />
                    </x-cards.card-dashboard-version1-withshadow>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ __('Emissions Scope 1 monitoring') }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-checkbox :value="$dashboardData['climate_change']['emissions_scope_1_monitoring'][
                                        'values'
                                    ]" :color="color(2)" />
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Main sources of scope 1 emissions')]) }}" class="!h-auto"
                            type="none" contentplacement="none" class="!h-auto !mt-5">

                            @foreach ($dashboardData['climate_change']['main_sources_of_scope_1_emissions']['list'] as $list)
                                <div
                                    class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 {{ $loop->first ? 'mt-10' : 'mt-5' }}">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $list['label'] }}</p>
                                    </div>
                                    <div class="">
                                        <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                            $dashboardData['climate_change']['main_sources_of_scope_1_emissions'][
                                                'unit'
                                            ],
                                        )"
                                            :isNumber=true />
                                    </div>
                                </div>
                            @endforeach

                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG scope 1 emissions')]) }}"
                            subpoint="{{ json_encode($charts['climate_change']['ghg_scope_1_emissions']['subPoint']) }}"
                            class="!h-full">
                            <x-charts.bar id="emissions" class="m-auto relative !h-full !w-full" :unit="__($charts['climate_change']['ghg_scope_1_emissions']['unit'])"
                                subinfo="{{ json_encode($charts['climate_change']['ghg_scope_1_emissions']['subInfo']) }}" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Produces biogenic CO2 emissions derived from biomass burning or biodegradation') }}
                                </p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$dashboardData['climate_change']['produces_biogenic_co_2_emissions'][
                                    'values'
                                ]" :color="color(2)" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Monitors biogenic CO2 emissions derived from biomass burning or biodegradation') }}
                                </p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$dashboardData['climate_change']['monitors_biogenic_co_2_emissions'][
                                    'values'
                                ]" :color="color(2)" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                            contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ __('Emissions Scope 2 monitoring') }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-checkbox :value="$dashboardData['climate_change']['emissions_scope_2_monitoring'][
                                        'values'
                                    ]" :color="color(2)" />
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Main sources of scope 2 emissions')]) }}" type="none"
                            contentplacement="none" class="!h-auto !mt-5 !pb-14">
                            @foreach ($dashboardData['climate_change']['main_sources_of_scope_2_emissions']['list'] as $list)
                                <div
                                    class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 {{ $loop->first ? 'mt-10' : 'mt-5' }}">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $list['label'] }}
                                        </p>
                                    </div>
                                    <div class="">
                                        <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                            $dashboardData['climate_change']['main_sources_of_scope_2_emissions'][
                                                'unit'
                                            ],
                                        )"
                                            :isNumber=true />
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                    </div>

                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG scope 2 emissions')]) }}"
                            subpoint="{{ json_encode($charts['climate_change']['ghg_scope_2_emissions']['subPoint']) }}"
                            class="!h-full">
                            <x-charts.bar id="emissions2" class="m-auto relative !h-full !w-full" :unit="__($charts['climate_change']['ghg_scope_2_emissions']['unit'])"
                                subinfo="{{ json_encode($charts['climate_change']['ghg_scope_2_emissions']['subInfo']) }}" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Emissions Scope 3 monitoring') }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$dashboardData['climate_change']['emissions_scope_3_monitoring']['values']" :color="color(2)" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-10 pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Knows the main sources of scope 3 emissions') }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$dashboardData['climate_change'][
                                    'knows_the_main_sources_of_scope_3_emissions'
                                ]['values']" :color="color(2)" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('GHG scope 3 emissions')]) }}"
                            subpoint="{{ json_encode($charts['climate_change']['ghg_scope_3_emissions']['subPoint']) }}"
                            class="!h-full">
                            <x-charts.bar id="emissions3" class="m-auto relative !h-full !w-full" :unit="__($charts['climate_change']['ghg_scope_3_emissions']['unit'])"
                                subinfo="{{ json_encode($charts['climate_change']['ghg_scope_3_emissions']['subInfo']) }}" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Main sources of scope 3 emissions')]) }}" type="none"
                            contentplacement="none" class="!h-auto !mt-5 !pb-14">

                            @foreach ($dashboardData['climate_change']['main_sources_of_scope_3_emissions']['list'] as $list)
                                <div
                                    class="flex items-center justify-between border-b border-b-esg7/40 w-full gap-5 pb-3 {{ $loop->first ? 'mt-10' : 'mt-5' }}">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $list['label'] }}</p>
                                    </div>

                                    @if ($list['is_boolean'])
                                        <div class="">
                                            <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                        </div>
                                    @else
                                        <div class="w-1/3">
                                            <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                                $dashboardData['climate_change']['main_sources_of_scope_3_emissions'][
                                                    'unit'
                                                ],
                                            )"
                                                :isNumber=true />
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('GHG emissions removed/stored: Natural removal (forest)')]) }}"
                        class="!h-full" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['climate_change'][
                                    'ghg_emissions_removed_stored_natural_removal_forest'
                                ]['values']" :unit="$dashboardData['climate_change'][
                                    'ghg_emissions_removed_stored_natural_removal_forest'
                                ]['unit']" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.tree', ['color' => color(2)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('GHG emissions removed/stored: Storage through technology')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['climate_change'][
                                    'ghg_emissions_removed_stored_storage_through_technology'
                                ]['values']" :unit="$dashboardData['climate_change'][
                                    'ghg_emissions_removed_stored_storage_through_technology'
                                ]['unit']" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.cpu', ['color' => color(2)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Carbon Intesity')]) }}"
                        class="!h-full" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['climate_change']['carbon_intensity']['values']" :unit="__($dashboardData['climate_change']['carbon_intensity']['unit'])" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.tree', ['color' => color(2)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            {{-- SECTION: pollution --}}
            <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="pollution">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none" contentplacement="none">

                        @foreach ($dashboardData['polution']['list_1']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                @if ($list['is_boolean'])
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                    </div>
                                @else
                                    <div class="w-1/3">
                                        <x-cards.cards-list-value-unit :value="$list['value']" :unit="__($dashboardData['polution']['list_1']['unit'])"
                                            :isNumber=true />
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('amounts of substances generated, used during production or purchased')]) }}"
                        class="!h-auto" type="none" contentplacement="none" class="!h-auto">
                        @foreach ($dashboardData['polution']['amounts_of_substances_generated']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>

                                @if ($list['is_boolean'])
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                    </div>
                                @else
                                    <div>
                                        <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                            $dashboardData['polution']['amounts_of_substances_generated']['unit'],
                                        )"
                                            :isNumber=true />
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    @livewire('dashboard.charts.bar-filter', [__('Emission of air pollutants'), $charts['polution']['emission_of_air_pollutants']['chartData'], 'emission_pollutants'])
                    @livewire('dashboard.charts.bar-filter', [__('Emission of pollutants - Water and soil'), $charts['polution']['emission_of_pollutants_water_and_soil']['chartData'], 'emission_pollutants_water_soil'])
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    @livewire('dashboard.charts.bar-filter', [__('Emission of pollutants - Other'), $charts['polution']['emission_of_pollutants_other']['chartData'], 'emission_pollutants_other'])
                </div>
            </div>

            {{-- SECTION: water --}}
            <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="water">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Use of water resources')]) }}"
                        class="!h-auto" type="none" contentplacement="none">
                        @foreach ($dashboardData['water_and_marine']['use_of_water_resources']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>

                                @if ($list['is_boolean'])
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                    </div>
                                @else
                                    <div class="w-1/3">
                                        <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                            $dashboardData['water_and_marine']['use_of_water_resources']['unit'],
                                        )" />
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="grid grid-cols-1 gap-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Environment to where the waste water is discharged')]) }}"
                            class="!h-min" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$dashboardData['water_and_marine']['medium_waste_water_is_discharged'][
                                        'checkbox_lables'
                                    ]" />
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Source of water consumedy')]) }}" class="!h-min"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$dashboardData['water_and_marine']['source_of_water_consumed'][
                                        'checkbox_lables'
                                    ]" />
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water consumed')]) }}"
                        subpoint="{{ json_encode($charts['water_and_marine']['water_consumed']['subPoint']) }}"
                        class="!h-auto">
                        <x-charts.bar id="water_consumed" class="m-auto relative !h-full !w-full" :unit="__($charts['water_and_marine']['water_consumed']['unit'])"
                            subinfo="{{ json_encode($charts['water_and_marine']['water_consumed']['subInfo']) }}" />
                    </x-cards.card-dashboard-version1-withshadow>


                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Water intensity')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['water_and_marine']['water_intensity']['values']" :unit="__($dashboardData['water_and_marine']['water_intensity']['unit'])" :isNumber=true />
                            </div>
                            <div class="-mt-3">
                                @include('icons.dashboard.8.enviroment.water', ['color' => color(2)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            {{-- SECTION: ecosystems --}}
            <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="ecosystems">
                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([__('Impacts on biodiversity and ecosystems')]) }}" class="!h-auto"
                    type="none" contentplacement="none">
                    @foreach ($dashboardData['biodiversity_and_ecosystems']['impacts_on_biodiversity_and_ecosystems']['list'] as $list)
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ $list['label'] }}</p>
                            </div>

                            @if ($list['is_boolean'])
                                <div class="">
                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                </div>
                            @else
                                <div class="w-1/3">
                                    <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['biodiversity_and_ecosystems'][
                                            'impacts_on_biodiversity_and_ecosystems'
                                        ]['unit'],
                                    )" :isNumber=true />
                                </div>
                            @endif

                        </div>
                    @endforeach
                </x-cards.card-dashboard-version1-withshadow>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('operations in or near protected areas')]) }}" type="none"
                        contentplacement="none" class="!h-auto !pb-14">
                        @foreach ($dashboardData['biodiversity_and_ecosystems']['operation_protected_areas']['list'] as $list)
                            <div
                                class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 {{ $loop->first ? 'mt-10' : 'mt-5' }}">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div>
                                    <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['biodiversity_and_ecosystems']['operation_protected_areas'][
                                            'unit'
                                        ],
                                    )" :isNumber=true />
                                </div>
                            </div>
                        @endforeach

                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('operations located in sensitive, protected or high biodiversity value areas, outside environmentally protected areas, in the reporting period')]) }}"
                        type="none" class="!h-auto" contentplacement="none">
                        <div class="text-center !h-[180px] !w-[180px] m-auto mt-5">
                            <x-charts.donut id="operation_located" class="m-auto" />
                        </div>

                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            {{-- SECTION: waste --}}
            <div class="bg-esg2/10 rounded-3xl p-8 mt-8" x-show="waste">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Raw-Materials Consumption')]) }}" class="!h-auto" type="none"
                        contentplacement="none">
                        @foreach ($dashboardData['use_of_resources']['raw_materials_consumption']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>

                                <div class="">
                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Products and/or raw materials used')]) }}" type="none"
                        contentplacement="none" class="!h-auto !pb-14">
                        @foreach ($dashboardData['use_of_resources']['products_materials_used']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div>
                                    <x-cards.cards-list-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['use_of_resources']['products_materials_used']['unit'],
                                    )" :isNumber=true />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                    @livewire('dashboard.charts.bar-filter', [__('waste'), $charts['use_of_resources']['waste']['chartData'], 'waste'])
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('waste distribution')]) }}"
                        type="none" class="!h-auto" contentplacement="none">
                        <div class="grid grid-cols-6 place-content-center gap-4">
                            <div class="col-start-2 col-span-4">
                                <div class="text-center !h-[180px] !w-[180px] m-auto">
                                    <x-charts.donut id="water_distribution" class="m-auto mt-5" />
                                </div>
                                <div class="content-center mt-5 px-8" id="water_distribution-legend"></div>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Total Waste Generated')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['use_of_resources']['total_waste_generated']['values']" :unit="__($dashboardData['use_of_resources']['total_waste_generated']['unit'])" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.social.course', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Final destination for radioactive waste')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['use_of_resources']['destination_for_radioactive'][
                                    'checkbox_lables'
                                ]"
                                    xclass="font-encodesans text-lg	 font-normal text-esg8" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Final destination applied')]) }}"
                    class="!h-auto !mt-5">
                    <div class="gri grid-cols-1 md:grid-cols-2 gap-10">
                        <x-charts.bar id="final_destination_applied" class="m-auto relative !h-full !w-full"
                            :unit="__($charts['use_of_resources']['final_destination_applied']['unit'])" legend="true" />
                    </div>
                </x-cards.card-dashboard-version1-withshadow>
            </div>
        </div>

        {{-- Social --}}
        <div x-show="social" x-data="{ organisation: true, chain: false, communities: false, consumers: false }">

            <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                    :class="organisation ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                    x-on:click="organisation = true, chain= false, communities=false, consumers=false">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="organisation">
                            @include('icons.dashboard.8.social.user', ['color' => color(1)])
                        </template>
                        <template x-if="!organisation">
                            @include('icons.dashboard.8.social.user', ['color' => color(16)])
                        </template>
                        <label for="main" class="text-base">{{ __('Workers in the organisation') }}</label>
                    </div>
                </div>

                <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                    :class="chain ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                    x-on:click="organisation = false, chain= true, communities=false, consumers=false">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="chain">
                            @include('icons.dashboard.8.social.user', ['color' => color(1)])
                        </template>
                        <template x-if="!chain">
                            @include('icons.dashboard.8.social.user', ['color' => color(16)])
                        </template>
                        <label for="main" class="text-base">{{ __('Workers in the value chain') }}</label>
                    </div>
                </div>

                <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                    :class="communities ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                    x-on:click="organisation = false, chain= false, communities=true, consumers=false">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="communities">
                            @include('icons.dashboard.8.social.community', ['color' => color(1)])
                        </template>
                        <template x-if="!communities">
                            @include('icons.dashboard.8.social.community', ['color' => color(16)])
                        </template>
                        <label for="main" class="text-base">{{ __('Affected communities') }}</label>
                    </div>
                </div>

                <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                    :class="consumers ? 'bg-esg1/10 border-esg1 text-esg1 font-bold' : 'text-esg16'"
                    x-on:click="organisation=false, chain=false, communities=false, consumers=true">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="consumers">
                            @include('icons.dashboard.8.social.consumer', ['color' => color(1)])
                        </template>
                        <template x-if="!consumers">
                            @include('icons.dashboard.8.social.consumer', ['color' => color(16)])
                        </template>
                        <label for="main" class="text-base">{{ __('Consumers and end-users') }}</label>
                    </div>
                </div>
            </div>

            <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="organisation">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted workers')]) }}"
                        type="flex" class="!h-[270px]" contentplacement="none">
                        <x-charts.donut id="contracted_workers" class="m-auto !h-[180px] !w-[180px]" legendes="true" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('outsourced workers')]) }}"
                        type="flex" class="!h-[270px]" contentplacement="none">
                        <x-charts.donut id="outsourced_workers" class="m-auto !h-[180px] !w-[180px]" legendes="true" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('category of contract')]) }}"
                        subpoint="{{ $subpointGender }}" class="!h-min">
                        <x-charts.bar id="category_contract" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('type of contract')]) }}"
                        subpoint="{{ $subpointGender }}" class="!h-min">
                        <x-charts.bar id="type_contract" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('contracted Worker’s age distribution')]) }}"
                        subpoint="{{ $subpointAge }}" class="!h-min">
                        <x-charts.bar id="contracted_worker_age" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('outsourced Worker’s age distribution')]) }}"
                        subpoint="{{ $subpointAge }}" class="!h-min">
                        <x-charts.bar id="outsourced_worker_age" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Workers that belong to minorities')]) }}" class="!h-min">
                        <x-charts.bar id="workers_minorities" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('percentage of workers that belong to minorities')]) }}" type="flex"
                        class="!h-full" contentplacement="none">
                        <x-charts.donut id="workers_minorities_percentage" class="m-auto !h-[180px] !w-[180px]" />
                    </x-cards.card-dashboard-version1-withshadow>


                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Middle management by gender (contracted)')]) }}"
                        subpoint="{{ $subpointGender }}" class="!h-min">
                        <x-charts.bar id="middle_management_gender_contracted" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Middle management by age (contracted)')]) }}"
                        subpoint="{{ $subpointAge }}" class="!h-min">
                        <x-charts.bar id="middle_management_age_contracted" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Top management by gender (contracted)')]) }}"
                        subpoint="{{ $subpointGender }}" class="!h-min">
                        <x-charts.bar id="top_management_gender_contracted" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Top management by age (contracted)')]) }}"
                        subpoint="{{ $subpointAge }}" class="!h-min">
                        <x-charts.bar id="top_management_age_contracted" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>


                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Middle management by gender (outsourced)')]) }}"
                        subpoint="{{ $subpointGender }}" class="!h-min">
                        <x-charts.bar id="middle_management_gender_outsourced" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Middle management by age (outsourced)')]) }}"
                        subpoint="{{ $subpointAge }}" class="!h-min">
                        <x-charts.bar id="middle_management_age_outsourced" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Top management by gender (outsourced)')]) }}"
                        subpoint="{{ $subpointGender }}" class="!h-min">
                        <x-charts.bar id="top_management_gender_outsourced" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Top management by age (outsourced)')]) }}"
                        subpoint="{{ $subpointAge }}" class="!h-min">
                        <x-charts.bar id="top_management_age_outsourced" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('contracted Workers Turnover')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['contracted_workers_turnover']['values']" :unit="__($dashboardData['workers_in_org']['contracted_workers_turnover']['unit'])" />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.workers', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Gender pay gap')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['gender_pay_gap']['values']" :unit="__($dashboardData['workers_in_org']['gender_pay_gap']['unit'])" />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Hourly earning variation')]) }}" subpoint="{{ $subpointGender }}"
                        class="!h-min">
                        <x-charts.bar id="earning_variation" class="m-auto relative !h-full !w-full" :unit="__($charts['workers_in_org']['hourly_earning_variation']['unit'])" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Salary Variation')]) }}"
                        subpoint="{{ $subpointGender }}" class="!h-min">
                        <x-charts.bar id="salary_variation" class="m-auto relative !h-full !w-full" :unit="__($charts['workers_in_org']['salary_variation']['unit'])" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Distribution of hiring and layoffs')]) }}"
                        subpoint="{{ $subpointAge }}" class="!h-min">
                        <x-charts.bar id="hiring_layoffs" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Safety and health at work')]) }}" class="!h-full"
                        contentplacement="none">

                        @foreach ($dashboardData['workers_in_org']['safety_and_health_at_work']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>

                                <div class="">
                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                </div>
                            </div>
                        @endforeach

                    </x-cards.card-dashboard-version1-withshadow>


                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Accidents at work')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['accidents_at_work']['values']" :unit="__($dashboardData['workers_in_org']['accidents_at_work']['unit'])" :isNumber=true />

                            <div class="-mt-3">
                                @include('icons.dashboard.work-accident', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Days lost (injury, accident, death or illness)')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['days_lost']['values']" :unit="__($dashboardData['workers_in_org']['days_lost']['unit'])" :isNumber=true />
                            <div class="-mt-3">
                                @include('icons.dashboard.work-accident', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Training for the Workers')]) }}" class="!h-full"
                            contentplacement="none">
                            @foreach ($dashboardData['workers_in_org']['training_for_the_workers']['list'] as $list)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $list['label'] }}</p>
                                    </div>

                                    <div class="">
                                        <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Number of hours on training')]) }}" class="!h-auto"
                            contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['hours_on_training']['values']" :unit="__($dashboardData['workers_in_org']['hours_on_training']['unit'])" :isNumber=true />
                                <div class="-mt-3">
                                    @include('icons.dashboard.8.social.qualification', [
                                        'color' => color(1),
                                    ])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Contracted workers that attended training')]) }}"
                            class="!h-auto !mt-5" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['workers_attended_training']['values']" :unit="__($dashboardData['workers_in_org']['workers_attended_training']['unit'])" :isNumber=true />
                                <div class="-mt-3">
                                    @include('icons.dashboard.8.social.course', ['color' => color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>
            </div>

            <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="chain">
                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('value chain workers')]) }}"
                    class="!h-auto" type="none" contentplacement="none">

                    @foreach ($dashboardData['workers_value_chain']['value_chain_workers']['list'] as $list)
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ $list['label'] }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                            </div>
                        </div>
                    @endforeach
                </x-cards.card-dashboard-version1-withshadow>

                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([__('Topics covered in the policies to manage impacts, risks and opportunities')]) }}"
                    class="!h-auto !mt-5" type="none" contentplacement="none">
                    <x-cards.cards-checkbox-list :list="$dashboardData['workers_value_chain']['topics_covered_in_the_policies'][
                        'checkbox_lables'
                    ]" color="bg-esg1" />
                </x-cards.card-dashboard-version1-withshadow>

            </div>

            <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="communities">
                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Affected communities')]) }}"
                    class="!h-auto" type="none" contentplacement="none">
                    @foreach ($dashboardData['affected_communities']['affected_communities']['list'] as $list)
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ $list['label'] }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                            </div>
                        </div>
                    @endforeach
                </x-cards.card-dashboard-version1-withshadow>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Topics covered in the policies to manage impacts, risks and opportunities')]) }}"
                        class="!h-auto !mt-5" type="none" contentplacement="none">
                        <x-cards.cards-checkbox-list :list="$dashboardData['affected_communities']['topics_covered_in_the_policies'][
                            'checkbox_lables'
                        ]" color="bg-esg1" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Type of investments made in the community')]) }}"
                        class="!h-auto !mt-5" type="none" contentplacement="none">
                        <x-cards.cards-checkbox-list :list="$dashboardData['affected_communities']['type_of_investments_made_in_the_community'][
                            'checkbox_lables'
                        ]" color="bg-esg1" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('operations of the local community')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['affected_communities']['operations_of_the_local_community'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['affected_communities']['operations_of_the_local_community']['unit'],
                                )" />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.social.group', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('financial investment in the community')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['affected_communities'][
                                    'financial_investment_in_the_community'
                                ]['values']" :unit="__(
                                    $dashboardData['affected_communities']['financial_investment_in_the_community'][
                                        'unit'
                                    ],
                                )" :isCurrency=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('volunteer hours undertaken by the  contracted workers')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['affected_communities']['volunteer_hours_contracted_workers'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['affected_communities']['volunteer_hours_contracted_workers'][
                                        'unit'
                                    ],
                                )" />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.social.social', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            <div class="bg-esg1/10 rounded-3xl p-8 mt-8" x-show="consumers">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Consumers and End-Users')]) }}"
                        class="!h-auto" type="none" contentplacement="none">
                        @foreach ($dashboardData['consumers_and_end_users']['consumers_and_end_users']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Level of satisfaction')]) }}"
                        class="!h-auto" type="none" contentplacement="none">
                        @foreach ($dashboardData['consumers_and_end_users']['level_of_satisfaction']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['consumers_and_end_users']['level_of_satisfaction']['unit'],
                                    )"
                                        xclass="font-encodesans font-medium text-esg8 text-lg" />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Number of Consumers and/or end-users')]) }}" class="!h-auto"
                        type="none" contentplacement="none">
                        @foreach ($dashboardData['consumers_and_end_users']['number_of_consumers_end_users']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3 mt-5">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-value-unit :value="$list['value']"
                                        xclass="font-encodesans font-medium text-esg8 text-lg" :isNumber=true />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Variation of new consumers and/or end users')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">

                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['consumers_and_end_users']['variation_of_new_consumers'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['consumers_and_end_users']['variation_of_new_consumers']['unit'],
                                )" />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.social.community_program', [
                                    'color' => color(1),
                                ])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>
        </div>

        {{-- Governance --}}
        <div x-show="governance" x-data="{ culture: true, practices: false, detection: false, management: false }">

            <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="border rounded-md px-4 shadow h-16 flex items-center shadow-esg7/20"
                    :class="culture ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                    x-on:click="culture = true, practices= false, detection=false, management=false">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="culture">
                            @include('icons.dashboard.8.governance.conduct', ['color' => color(3)])
                        </template>
                        <template x-if="!culture">
                            @include('icons.dashboard.8.governance.conduct', ['color' => color(16)])
                        </template>
                        <label for="main"
                            class="text-base">{{ __('Main conduct policies and corporate culture') }}</label>
                    </div>
                </div>

                <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                    :class="practices ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                    x-on:click="culture = false, practices= true, detection=false, management=false">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="practices">
                            @include('icons.dashboard.8.governance.supplier', ['color' => color(3)])
                        </template>
                        <template x-if="!practices">
                            @include('icons.dashboard.8.governance.supplier', ['color' => color(16)])
                        </template>
                        <label for="main"
                            class="text-base">{{ __('Relations with suppliers and payment practices') }}</label>
                    </div>
                </div>

                <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                    :class="detection ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                    x-on:click="culture = false, practices= false, detection=true, management=false">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="detection">
                            @include('icons.dashboard.8.governance.corruption', ['color' => color(3)])
                        </template>
                        <template x-if="!detection">
                            @include('icons.dashboard.8.governance.corruption', ['color' => color(16)])
                        </template>
                        <label for="main"
                            class="text-base">{{ __('Corruption and bribery prevention and detection') }}</label>
                    </div>
                </div>

                <div class="border rounded-md px-4 w-full shadow h-16 flex items-center shadow-esg7/20"
                    :class="management ? 'bg-esg3/10 border-esg3 text-esg3 font-bold' : 'text-esg16'"
                    x-on:click="culture = false, practices= false, detection=false, management=true">
                    <div class="flex items-center justify-center w-full gap-5">
                        <template x-if="management">
                            @include('icons.dashboard.8.governance.risk', ['color' => color(3)])
                        </template>
                        <template x-if="!management">
                            @include('icons.dashboard.8.governance.risk', ['color' => color(16)])
                        </template>
                        <label for="main" class="text-base">{{ __('Risk Management') }}</label>
                    </div>
                </div>
            </div>

            <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="culture">
                {{-- NEW --}}
                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([__('Highest governance body of the organisation constituted and structured')]) }}"
                    class="!h-auto !mt-10" contentplacement="none">
                    <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                        <x-cards.cards-value-unit :value="$dashboardData['conduct_policies_corporate_culture'][
                            'highest_governance_body_of_the_organisation'
                        ]['checkbox_lables']"
                            xclass="font-encodesans text-lg	 font-normal text-esg8" />
                    </div>
                </x-cards.card-dashboard-version1-withshadow>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('highest governance body ')]) }}" type="flex"
                        contentplacement="none" class="!h-full">
                        <x-charts.donut id="gender_high_governance_body" class=" !h-[180px] !w-[180px]"
                            legendes="true" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Annual revenue')]) }}"
                            class="!h-auto" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <x-cards.cards-value-unit :value="$dashboardData['conduct_policies_corporate_culture']['annual_revenue'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['conduct_policies_corporate_culture']['annual_revenue']['unit'],
                                )" :isCurrency=true />
                                <div class="-mt-3">
                                    @include('icons.dashboard.8.money-hand', ['color' => color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Annual net revenue')]) }}"
                            class="!h-auto !mt-5" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <x-cards.cards-value-unit :value="$dashboardData['conduct_policies_corporate_culture']['annual_net_revenue'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['conduct_policies_corporate_culture']['annual_net_revenue']['unit'],
                                )" :isCurrency=true />
                                <div class="-mt-3">
                                    @include('icons.dashboard.8.money-hand', ['color' => color(3)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Annual reporting')]) }}"
                        class="!h-full" type="none" contentplacement="none">
                        <div class="grid grid-cols-1 md:grid-cols-1 mt-10">

                            @foreach ($dashboardData['conduct_policies_corporate_culture']['annual_reporting']['list'] as $list)
                                <div class="flex items-center justify-between pb-2 border-b border-b-esg7/40 mb-3">
                                    <label for="checkbox-website"
                                        class="font-encodesans text-sm font-normal text-esg8 w-9/12">{{ $list['label'] }}</label>
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>






                {{-- Old --}}
                <div class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div class="grid grid-cols-1 gap-5">
                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('Amount of the fines imposed')]) }}" class="!h-min"
                                contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website"
                                            class="font-encodesans text-4xl font-medium text-esg8">1.234 <span
                                                class="text-base text-esg8">€</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.8.fine', ['color' => color(3)])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>

                            {{-- NEW --}}
                            <x-cards.card-dashboard-version1-withshadow
                                text="{{ json_encode([__('incidents of discrimination')]) }}" class="!h-min"
                                contentplacement="none">
                                <div class="flex items-center justify-between w-full">
                                    <div class="">
                                        <label for="checkbox-website"
                                            class="font-encodesans text-4xl font-medium text-esg8">1.234 <span
                                                class="text-base text-esg8">{{ __('incidents') }}</span></label>
                                    </div>

                                    <div class="-mt-3">
                                        @include('icons.dashboard.8.discrimination', [
                                            'color' => color(3),
                                        ])
                                    </div>
                                </div>
                            </x-cards.card-dashboard-version1-withshadow>


                        </div>

                    </div>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Strategic Sustainable Development Goals')]) }}" class="!h-auto !mt-5">
                        <div class="text-esg25 font-encodesans text-5xl font-bold pb-10">
                            <div class="grid grid-cols-4 md:grid-cols-9 gap-3 mt-10 ">
                                <div class="w-full">
                                    @include('icons.goals.1', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.2', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.3', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.4', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.5', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.6', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.7', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.8', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.9', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.10', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.11', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.12', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.13', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.14', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.15', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.16', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>

                                <div class="w-full">
                                    @include('icons.goals.17', [
                                        'class' => 'inline-block',
                                        'color' => '#DCDCDC',
                                    ])
                                </div>
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('sustainability-related initiatives or principles')]) }}"
                        class="!h-auto !mt-5" contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-2">
                            <div class="">
                                <label for="checkbox-website"
                                    class="font-encodesans text-sm font-normal text-esg8">{{ __('Signatory or involved in external sustainability-related initiatives or principles') }}</label>
                            </div>
                            <div class="">
                                @include('icons.checkbox', ['color' => color(3)])
                            </div>
                        </div>

                        <div class="flex items-center justify-center gap-12 py-8">
                            @include('icons.dashboard.8.logos.sbt')
                            @include('icons.dashboard.8.logos.bcorp')
                            @include('icons.dashboard.8.logos.pri')
                            @include('icons.dashboard.8.logos.unglobal')
                            @include('icons.dashboard.8.logos.wbcsd')
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="practices">
                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([__('Characteristics of suppliers')]) }}" class="!h-auto" type="none"
                    contentplacement="none">

                    @foreach ($dashboardData['relations_with_suppliers']['characteristics_of_suppliers']['list'] as $list)
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ $list['label'] }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                            </div>
                        </div>
                    @endforeach
                </x-cards.card-dashboard-version1-withshadow>

                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Type of suppliers')]) }}"
                        class="!h-auto">
                        <x-charts.bar id="supplier_types" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Suppliers by continent')]) }}"
                        class="!h-auto">
                        <x-charts.bar id="supplier_continent" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([__('Suppliers by industry sector')]) }}" class="!h-auto">
                    <x-charts.bar id="supplier_industry_sector" class="m-auto relative !h-full !w-full" />
                </x-cards.card-dashboard-version1-withshadow>

                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Percentage of suppliers assessed for environmental impacts')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers'][
                                    'percentag_suppliers_environmental'
                                ]['values']" :unit="__(
                                    $dashboardData['relations_with_suppliers']['percentag_suppliers_environmental'][
                                        'unit'
                                    ],
                                )" />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.enviroment', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Percentage of suppliers assessed for social impacts')]) }}"
                        class="!h-auto" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers']['percentag_suppliers_social'][
                                'values'
                            ]" :unit="__(
                                $dashboardData['relations_with_suppliers']['percentag_suppliers_social']['unit'],
                            )" />
                            <div class="-mt-3">
                                @include('icons.dashboard.8.governance.group', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('confirmed cases in which contracts with business partners were terminated or not renewed as a result of violations')]) }}"
                        class="!h-auto" type="none" contentplacement="none">

                        @foreach ($dashboardData['relations_with_suppliers']['business_partners_were_terminated']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['relations_with_suppliers']['business_partners_were_terminated'][
                                            'unit'
                                        ],
                                    )"
                                        xclass="font-encodesans font-medium text-esg8 text-lg"
                                        xunitClass="text-xs text-esg16" />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Number of legal proceedings initiated against the organisation or its workers')]) }}"
                        class="!h-auto" type="none" contentplacement="none">
                        @foreach ($dashboardData['relations_with_suppliers']['proceedings_against_organisation']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['relations_with_suppliers']['proceedings_against_organisation'][
                                            'unit'
                                        ],
                                    )"
                                        xclass="font-encodesans font-medium text-esg8 text-lg"
                                        xunitClass="text-xs text-esg16" />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Average annual monetary value of payments made to suppliers')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers']['payments_made_to_suppliers'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['relations_with_suppliers']['payments_made_to_suppliers']['unit'],
                                )" :isCurrency=true />
                            </div>
                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' => color(3)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Average annual monetary value going to local suppliers')]) }}"
                        class="!h-auto" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers']['going_to_local_suppliers'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['relations_with_suppliers']['going_to_local_suppliers']['unit'],
                                )" :isCurrency=true />
                            </div>
                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' => color(3)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Payment ratio to local suppliers')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers'][
                                    'payment_ratio_to_local_suppliers'
                                ]['values']" :unit="__(
                                    $dashboardData['relations_with_suppliers']['payment_ratio_to_local_suppliers'][
                                        'unit'
                                    ],
                                )" />
                            </div>
                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' => color(3)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Lawsuits for late payment to suppliers')]) }}" class="!h-auto"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers'][
                                    'lawsuits_for_late_payment_to_suppliers'
                                ]['values']" />
                            </div>
                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' => color(3)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    @php
                        $subpoint = json_encode([['color' => 'bg-[#058894]', 'text' => __('Regular suppliers')], ['color' => 'bg-[#83D2DA]', 'text' => __('Small and medium suppliers')]]);
                    @endphp
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Timings for payment to suppliers')]) }}"
                        subpoint="{{ $subpoint }}" class="!h-min">
                        <x-charts.bar id="supplier_payment_timing" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="detection">
                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([__('Corruption and Bribery Prevention')]) }}" class="!h-auto"
                    type="none" contentplacement="none">

                    @foreach ($dashboardData['corruption_prevention_detection']['corruption_prevention_detection']['list'] as $list)
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ $list['label'] }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                            </div>
                        </div>
                    @endforeach
                </x-cards.card-dashboard-version1-withshadow>

                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Number of convictions')]) }}"
                        class="!h-min" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['corruption_prevention_detection']['number_of_convictions'][
                                    'values'
                                ]" :unit="__(
                                    $dashboardData['corruption_prevention_detection']['number_of_convictions']['unit'],
                                )" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.governance.gavel', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('monetary value of the fines imposed')]) }}" class="!h-auto"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['corruption_prevention_detection'][
                                    'monetary_value_of_the_fines_imposed'
                                ]['values']" :unit="__(
                                    $dashboardData['corruption_prevention_detection'][
                                        'monetary_value_of_the_fines_imposed'
                                    ]['unit'],
                                )" :isCurrency=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.governance.fine', ['color' => color(1)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('confirmed cases in which contracts with business partners were terminated or not renewed as a result of violations')]) }}"
                        class="!h-auto" type="none" contentplacement="none">

                        @foreach ($dashboardData['corruption_prevention_detection']['business_partners_were_terminated']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['corruption_prevention_detection'][
                                            'business_partners_were_terminated'
                                        ]['unit'],
                                    )"
                                        xclass="font-encodesans font-medium text-esg8 text-lg"
                                        xunitClass="text-xs text-esg16" :isNumber=true />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Number of legal proceedings initiated against the organisation or its workers')]) }}"
                        class="!h-auto" type="none" contentplacement="none">
                        @foreach ($dashboardData['corruption_prevention_detection']['proceedings_against_organisation']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-value-unit :value="$list['value']" :unit="__(
                                        $dashboardData['corruption_prevention_detection'][
                                            'proceedings_against_organisation'
                                        ]['unit'],
                                    )"
                                        xclass="font-encodesans font-medium text-esg8 text-lg"
                                        xunitClass="text-xs text-esg16" :isNumber=true />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>

                    @php
                        $subpoint = json_encode([['color' => 'bg-[#058894]', 'text' => __('Corruption')], ['color' => 'bg-[#83D2DA]', 'text' => __('Bribery')]]);
                    @endphp
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('cases of corruption and/or bribery')]) }}"
                        subpoint="{{ $subpoint }}" class="!h-min">
                        <x-charts.bar id="corruption_bribery" class="m-auto relative !h-full !w-full" />
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>

            <div class="bg-esg3/10 rounded-3xl p-8 mt-8" x-show="management">
                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                        contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Incidents of discrimination, in particular resulting in the application of sanctions') }}
                                </p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$dashboardData['risk_management']['incidents_of_discrimination_sanctions'][
                                    'values'
                                ]" :color="color(3)" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('incidents of discrimination')]) }}" class="!h-min"
                        contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['risk_management']['incidents_of_discrimination']['values']" :unit="__($dashboardData['risk_management']['incidents_of_discrimination']['unit'])" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.governance.discrimination', [
                                    'color' => color(1),
                                ])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div class="grid grid-cols-1 gap-5">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Corruption and bribery assessment and risks')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            @foreach ($dashboardData['risk_management']['corruption_assessment_and_risks']['list'] as $list)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $list['label'] }}</p>
                                    </div>
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('operations assessed for risks of corruption')]) }}"
                            class="!h-auto !mt-5" contentplacement="none">
                            <div class="flex items-center justify-between w-full">
                                <div class="">
                                    <x-cards.cards-value-unit :value="$dashboardData['risk_management']['operation_assesed_for_risk'][
                                        'values'
                                    ]" :unit="__(
                                        $dashboardData['risk_management']['operation_assesed_for_risk']['unit'],
                                    )" :isNumber=true />
                                </div>

                                <div class="-mt-3">
                                    @include('icons.dashboard.8.governance.pocket', ['color' => color(1)])
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Types of risks identified in the corruption and/or bribery assessment')]) }}"
                            class="!h-auto" type="none" contentplacement="none">

                            @foreach ($dashboardData['risk_management']['types_of_risks_identified']['list'] as $list)
                                <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                    <div class="">
                                        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                            {{ $list['label'] }}</p>
                                    </div>
                                    <div class="">
                                        <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                    </div>
                                </div>
                            @endforeach
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                <x-cards.card-dashboard-version1-withshadow
                    text="{{ json_encode([__('Human rights, forced labor and modern slavery risks and assessment')]) }}"
                    class="!h-auto" type="none" contentplacement="none">

                    @foreach ($dashboardData['risk_management']['human_rights']['list'] as $list)
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ $list['label'] }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                            </div>
                        </div>
                    @endforeach
                </x-cards.card-dashboard-version1-withshadow>

                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('operations assessed for Human rights, forced labor and modern slavery risks')]) }}"
                        class="!h-auto" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['risk_management']['operations_human_rights']['values']" :unit="__($dashboardData['risk_management']['operations_human_rights']['unit'])" />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.governance.g2490', ['color' => color(3)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('investments that include human rights clauses or undergone assessment')]) }}"
                        class="!h-auto" contentplacement="none">
                        <div class="flex items-center justify-between w-full">
                            <div class="">
                                <x-cards.cards-value-unit :value="$dashboardData['risk_management']['investments_human_rights']['values']" :unit="__($dashboardData['risk_management']['investments_human_rights']['unit'])" :isNumber=true />
                            </div>

                            <div class="-mt-3">
                                @include('icons.dashboard.8.money-hand', ['color' => color(3)])
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                        contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Studies regarding the relevant social and environmental impacts of the organisation') }}
                                </p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$dashboardData['risk_management']['studies_relevant_social_impacts'][
                                    'values'
                                ]" :color="color(3)" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow class="!h-auto" type="none"
                        contentplacement="none">
                        <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3">
                            <div class="">
                                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                    {{ __('Existence of vulnerable groups impacted by the organisation') }}</p>
                            </div>
                            <div class="">
                                <x-cards.cards-checkbox :value="$dashboardData['risk_management']['vulnerable_groups_impacted']['values']" :color="color(3)" />
                            </div>
                        </div>
                    </x-cards.card-dashboard-version1-withshadow>
                </div>

                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="grid grid-cols-1 gap-1">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Impacts considered in the studies related to environment and social')]) }}"
                            class="!h-auto" type="none" contentplacement="none">
                            <x-cards.cards-checkbox-list :list="$dashboardData['risk_management']['studies_related_to_environment'][
                                'checkbox_lables'
                            ]" color="bg-esg3" />
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto !mt-5" type="none"
                            contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ __('Creation of due diligence processes for environment and social significant impacts') }}
                                    </p>
                                </div>
                                <div class="">
                                    <x-cards.cards-checkbox :value="$dashboardData['risk_management']['creation_of_due_diligence_processes'][
                                        'values'
                                    ]" :color="color(3)" />
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>

                        <x-cards.card-dashboard-version1-withshadow class="!h-auto !mt-5" type="none"
                            contentplacement="none">
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ __('Affected stakeholders identified by the organisation activities') }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-checkbox :value="$dashboardData['risk_management']['affected_stakeholders']['values']" :color="color(3)" />
                                </div>
                            </div>
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>

                    <div class="">
                        <x-cards.card-dashboard-version1-withshadow
                            text="{{ json_encode([__('Organisation`s economic impacts')]) }}" class="!h-auto"
                            type="none" contentplacement="none">
                            <x-cards.cards-checkbox-list :list="$dashboardData['risk_management']['organisation_economic_impacts'][
                                'checkbox_lables'
                            ]" color="bg-esg3" />
                        </x-cards.card-dashboard-version1-withshadow>
                    </div>
                </div>

                <div class="my-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Types of affected stakeholders identified by the organisation activities')]) }}"
                        class="!h-auto" type="none" contentplacement="none">
                        <x-cards.cards-checkbox-list :list="$dashboardData['risk_management']['types_affected_stakeholders']['checkbox_lables']" color="bg-esg3" />
                    </x-cards.card-dashboard-version1-withshadow>

                    <x-cards.card-dashboard-version1-withshadow
                        text="{{ json_encode([__('Unethical corporate behavior and conflict of interest risks and assessment')]) }}"
                        class="!h-auto" type="none" contentplacement="none">
                        @foreach ($dashboardData['risk_management']['unethical_corporate_behavior']['list'] as $list)
                            <div class="flex items-center justify-between border-b border-b-esg7/40 w-full mt-5 pb-3">
                                <div class="">
                                    <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">
                                        {{ $list['label'] }}</p>
                                </div>
                                <div class="">
                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                </div>
                            </div>
                        @endforeach
                    </x-cards.card-dashboard-version1-withshadow>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
