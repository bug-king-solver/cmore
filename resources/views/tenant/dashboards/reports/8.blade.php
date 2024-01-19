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
            //esgGlobal();
            //esgCategoryTotal();

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
            

            radarChart(
                {!! json_encode([1,2,3,4]) !!},
                {!! json_encode($charts['report']['global_maturity']['chartData']['data']) !!},
                'csrd_maturity',
                [enviroment_color1, enviroment_color2]
            );
            {{--
            pieCharts(
                {!! json_encode([__('Similar results'), __('Better results'), __('Worse results')]) !!},
                {!! json_encode(['1000', '200', '8800']) !!},
                'rank_esg_emission_comopany',
                [age_30, age_30_50, age_50],
                '{{ __('Companies') }}'
            );

            pieCharts(
                {!! json_encode([__('Similar results'), __('Better results'), __('Worse results')]) !!},
                {!! json_encode(['1000', '200', '8800']) !!},
                'rank_renewable_energy_comopany',
                [age_30, age_30_50, age_50],
                '{{ __('Companies') }}'
            );

            pieCharts(
                {!! json_encode([__('Similar results'), __('Better results'), __('Worse results')]) !!},
                {!! json_encode(['1000', '200', '8800']) !!},
                'rank_renewable_energy_comopany2',
                [age_30, age_30_50, age_50],
                '{{ __('Companies') }}'
            );
            --}}
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

            @if ($charts['use_of_resources']['waste_distribution'])
                pieCharts(
                    {!! json_encode($charts['use_of_resources']['waste_distribution']['labels']) !!},
                    {!! json_encode($charts['use_of_resources']['waste_distribution']['dataset']) !!},
                    'water_distribution',
                    ['#008131', '#99CA3C', '#98BDA6'],
                    't'
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

            @if ($charts['workers_in_org']['subcontracted_age_distribution'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['subcontracted_age_distribution']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['subcontracted_age_distribution']['dataset']) !!},
                    'subcontracted_worker_age',
                    [age_30, age_30_50, age_50],
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


            @if ($charts['workers_in_org']['middle_management_gender_subcontracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['middle_management_gender_subcontracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['middle_management_gender_subcontracted']['dataset']) !!},
                    'middle_management_gender_subcontracted',
                    [social_male, social_female, social_other]
                );
            @endif
            @if ($charts['workers_in_org']['middle_management_age_subcontracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['middle_management_age_subcontracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['middle_management_age_subcontracted']['dataset']) !!},
                    'middle_management_age_subcontracted',
                    [age_30, age_30_50, age_50]
                );
            @endif

            @if ($charts['workers_in_org']['top_management_gender_subcontracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['top_management_gender_subcontracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['top_management_gender_subcontracted']['dataset']) !!},
                    'top_management_gender_subcontracted',
                    [social_male, social_female, social_other]
                );
            @endif

            @if ($charts['workers_in_org']['top_management_age_subcontracted'])
                barCharts(
                    {!! json_encode($charts['workers_in_org']['top_management_age_subcontracted']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['top_management_age_subcontracted']['dataset']) !!},
                    'top_management_age_subcontracted',
                    [age_30, age_30_50, age_50]
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

            @if ($charts['conduct_policies_corporate_culture']['highest_governance_body'])
                pieCharts(
                    {!! json_encode($charts['conduct_policies_corporate_culture']['highest_governance_body']['labels']) !!},
                    {!! json_encode($charts['conduct_policies_corporate_culture']['highest_governance_body']['dataset']) !!},
                    'gender_high_governance_body',
                    [social_male, social_female, social_other],
                    '{{ __('workers') }}'
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
            
            // Pie charts
            @if ($charts['climate_change']['energy_consumption']['base_line_year'])
                pieCharts(
                    {!! json_encode($charts['climate_change']['energy_consumption']['base_line_year']['labels']) !!},
                    {!! json_encode($charts['climate_change']['energy_consumption']['base_line_year']['dataset']) !!},
                    'energy_consumption_baseline',
                    [enviroment_color1, enviroment_color2],
                    '{{ __('MWh') }}'
                );
            @endif

            @if ($charts['climate_change']['energy_consumption']['reporting_year'])
                pieCharts(
                    {!! json_encode($charts['climate_change']['energy_consumption']['reporting_year']['labels']) !!},
                    {!! json_encode($charts['climate_change']['energy_consumption']['reporting_year']['dataset']) !!},
                    'energy_consumption_reporting',
                    [enviroment_color1, enviroment_color2],
                    '{{ __('MWh') }}'
                );
            @endif


            @if ($charts['workers_in_org']['contracted_workers'])
                pieCharts(
                    {!! json_encode($charts['workers_in_org']['contracted_workers']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['contracted_workers']['dataset']) !!},
                    'contracted_workers',
                    [social_male, social_female, social_other],
                    '{{ __('workers') }}'
                );
            @endif

            @if ($charts['workers_in_org']['subcontracted_workers'])
                pieCharts(
                    {!! json_encode($charts['workers_in_org']['subcontracted_workers']['labels']) !!},
                    {!! json_encode($charts['workers_in_org']['subcontracted_workers']['dataset']) !!},
                    'subcontracted_workers',
                    [social_male, social_female, social_other],
                    '{{ __('workers') }}'
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

            return new Chart(document.getElementById(id).getContext("2d"), {
                type: 'bar',
                data: data,
                options: chartOptions,
                plugins: [ChartDataLabels, extra]
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

            return new Chart(document.getElementById(id), config);
        }

        // Common function for radar charts
        function radarChart(labels, data, id, color) {
            var options = {
                responsive: false,
                scales: {
                    r: {
                        pointLabels: {
                            display: true,
                        },
                        ticks: {
                            callback: function(value, index, ticks) {
                                return value + '%';
                            }
                        },
                        format: {
                            callback: function(value, index, ticks) {
                                return value + '%';
                            }
                        },
                        grid: {
                            borderDash: [5, 5],
                        },
                        min: 0
                    }
                },

                plugins: {
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return false;
                            }
                        }
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
                    data: {{ json_encode($charts['main']['global_esg_maturity_level']) }},
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

            var ctx = document.getElementById('maturity_level');
            new Chart(ctx, config);

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

            var ctx = document.getElementById('maturity_level2');
            new Chart(ctx, config);
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

            var ctx = document.getElementById('maturity_level_category2');
            new Chart(ctx, config);

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

            // if(esg_category_total != '')
            //     esg_category_total.destroy();

            var ctx = document.getElementById('maturity_level_category');
            esg_category_total = new Chart(ctx, config);




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
            <x-report.pagewithimage url="/images/report/assess/page1.png">
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
            <x-report.pagewithimage url="/images/report/assess/page2.png" footer="true" footerCount="01" header="true">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('Company') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('overview') }}</p>
                </div>
            </x-report.page>

            {{-- Page 3 DONE --}}
            

             

             {{-- Page 3 DONE --}}
            <x-report.page title="{{ __('company overview') }}" footerCount="02">
                <div class="grid grid-cols-3 print:grid-cols-3 gap-10 py-5">
                    <div class="">
                        <div class="">
                            <p class="text-lg font-bold text-esg8">{{ __('Name') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $dashboardData['report']['company']->name ?? '' }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Business sector') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $dashboardData['report']['business_sector']->name ?? '' }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Headquarters') }}</p>
                            <p class="text-base text-esg8 mt-4">
                                @foreach($dashboardData['report']['country'] as $row)
                                   {{ $row['name'] }}
                                @endforeach
                            </p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('NIPC/NIF') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $dashboardData['report']['company']->vat_number }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Report period') }}</p>
                            <p class="text-base text-esg8 mt-4">  {{ date('Y-m-d', strtotime($questionnaire->from)) }} {{ __('to') }} {{ date('Y-m-d', strtotime($questionnaire->to)) }}</p>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-lg font-bold text-esg8">{{ __('Total of workers') }}</p>
                            <p class="text-base text-esg8 mt-4">{{ $dashboardData['report']['colaborators']['values'] }}</p>
                        </div>
                        
                    </div>

                    <div class="">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Financial information') }}</p>

                        <x-report.table.table>
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Annual revenue') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['conduct_policies_corporate_culture']['annual_revenue']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Annual net revenue') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['conduct_policies_corporate_culture']['annual_net_revenue']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Earnings before interest and taxes (EBIT)') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['ebit']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Liabilities') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['liabilities']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Total assets') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['total_assets']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Expenses arising from activities associated with human resources') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['expenses_human_resources']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Expenditure for the reporting period for activities associated with raw materials') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['expenditure_raw_materials']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>
                        </x-report.table.table>
                    </div>

                    <div class="pt-7">
                        <x-report.table.table>
                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Capital expenditure') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['capital_expenditure']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Total value of organisation`s debt') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['total_value_of_organisation_debt']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Net debt') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['net_debt']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Amount of interest expenses') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['amount_of_interest_expenses']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Net profit or loss') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['net_profit_or_loss']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Listed company') }}</x-report.table.td>
                                <x-report.table.td>
                                    @if ($dashboardData['report']['listed_company'] == 'yes')
                                        @include('icons.checkbox', ['color' =>  color(5)])
                                    @else
                                        @include('icons.checkbox-no')
                                    @endif
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __("Value of your organisation's weighted average cost of capital") }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['value_cost_of_capital']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __('Number of available shares of the organization') }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['number_shares_of_organization']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                            <x-report.table.tr>
                                <x-report.table.td class="w-10/12">{{ __("Value of the organisation's equity") }}</x-report.table.td>
                                <x-report.table.td>
                                    <x-currency :value="$dashboardData['report']['value_of_equity']['values']" />
                                </x-report.table.td>
                            </x-report.table.tr>

                        </x-report.table.table>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 4 DONE--}}
            <x-report.pagewithimage url="/images/report/assess/page4.png" footer="true" footerCount="03" header="true">
                <div class="">
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('ESG’s') }}</p>
                    <p class="text-6xl font-extrabold uppercase text-esg5">{{ __('scores') }}</p>
                </div>
            </x-report.page>

            {{-- Page 8.1 DONE--}}

            
            <x-report.page title="{{ __('ESG\’s scores') }}" footerCount="04">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-2 print:grid-cols-2 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div class="">
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Global ESG maturity level') }}</p>
                                <x-cards.cards-value-unit :value="$charts['main']['global_esg_maturity_level'][0]" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                            </div>

                            <div class="">
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Global ESG maturity level') }}</p>
                                <div class="grid grid-cols-3">
                                    <div class="text-center">
                                        <x-charts.percentage-circle :percent="$charts['main']['global_esg_maturity_level_by_category'][0]['maturity_final']" color="text-esg2" :text="__('Environment')"/>
                                    </div>
                                    <div class="text-center">
                                        <x-charts.percentage-circle :percent="$charts['main']['global_esg_maturity_level_by_category'][1]['maturity_final']" color="text-esg1" :text="__('Social')"/>
                                    </div>
                                    <div class="text-center">
                                        <x-charts.percentage-circle :percent="$charts['main']['global_esg_maturity_level_by_category'][2]['maturity_final']" color="text-esg3" :text="__('Governance')"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="">
                                <img src="/images/report/assess/page5.png" >
                            </div>
                        </div>
                        

                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('CSRD Maturity') }}</p>
                            <div class="">
                                <div class="m-auto">
                                    <x-charts.radar id="csrd_maturity" class="m-auto" width="360" height="360" />
                                </div>
                            </div>
                            <div class="w-3/5 print:w-3/4 m-auto">
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($charts['report']['global_maturity']['chartData']['labels'] as $keyLabels => $labeles)
                                        <div class="w-44">
                                            <div class="text-left gap-5 text-xs text-esg8 font-semibold mb-2">
                                                {{$keyLabels+1}} - {{$labeles}}
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($charts['report']['global_maturity']['chartData']['data'] as $keyLabels2 => $datasets)
                                                    <span class="inline-flex items-center rounded text-esg2 px-2.5 py-1 text-xs font-semibold text-{{$datasets['colorClass']}} bg-{{$datasets['colorClass']}}/10">{{ $datasets['data'][$keyLabels] }}%</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            {{-- Page 6 DONE--}}
            <x-report.page title="{{ __('ESG’s scores') }}" footerCount="05">
                <div class="py-5 min-h-[560px]">
                    <p class="text-lg font-bold text-esg8 uppercase">{{ __('Priority matrix and action plans') }}</p>
                    <div class="grid grid-cols-2 print:grid-cols-2 gap-10 mt-4">
                        <div class="">
                            @if ($charts['main']['action_plan'])
                                <div x-data="{showExtraLegend: false}" class="md:col-span-1 lg:p-5 xl:p-5 ">
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
                        @if ($charts['main']['action_plan_table'])  
                                <div id="action_list" class="md:col-span-1 lg:p-5 xl:p-10 lg:mt-0 print:p-2 h-auto overflow-x-auto">
                                    <table class="text-esg8/70 font-encodesans w-full table-auto">
                                        <tbody class="font-medium">
                                        @foreach ($charts['main']['action_plan_table'] as $initiative)
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
            {{-- 
            <x-report.page title="{{ __('ESG’s scores') }}" footerCount="06">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Benchmark') }}</p>
                            <div class="w-full py-6 px-4 shadow border flex-col justify-center items-center flex">
                                <div class="text-esg5 text-5xl font-bold">10.000</div>
                                <div class="text-neutral-700 text-lg font-normal">{{__('Total number of companies')}}</div>
                            </div>
                            <div class="w-full py-6 px-4 shadow border flex-col justify-center items-center flex">
                                <div class="text-esg5 text-5xl font-bold">3.000</div>
                                <div class="text-neutral-700 text-lg font-normal">{{ __('Companies in the same business sector') }}</div>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Total of GHG emissions') }}</p>
                            <div class="self-stretch px-4 py-1 bg-[#FFF2E5] rounded justify-between items-center inline-flex">
                                <div class="justify-start items-center gap-2 flex">
                                    <div class="w-8 h-8 relative">
                                        @include('icons.dashboard.8.trophy')
                                    </div>
                                    <div>
                                        <span class="text-esg5 text-xl">3rd</span>
                                        <span class="text-sm font-normal">
                                            {{ __('Company with the lowest GHG emissions') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="self-stretch px-4 py-1 bg-[#FFF2E5] rounded justify-between items-center inline-flex">
                                <div class="justify-start items-center gap-2 flex">
                                    <div class="w-8 h-8 relative">
                                        @include('icons.dashboard.8.trophy')
                                    </div>
                                    <div>
                                        <span class="text-esg5 text-xl">1st</span>
                                        <span class="text-sm font-normal">
                                            {{ __('Company in the business sector') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center w-full">
                                <x-charts.donut id="rank_esg_emission_comopany" class="m-auto !h-[180px] !w-[180px] mt-5" />
                                <div class="grid content-center mt-5" id="rank_esg_emission_comopany-legend"></div>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Total of GHG emissions') }}</p>
                            <div class="self-stretch px-4 py-1 bg-[#153A5B1A] rounded justify-between items-center inline-flex">
                                <div class="justify-start items-center gap-2 flex">
                                    <div>
                                        <span class="text-[#153A5B] text-xl">400th</span>
                                        <span class="text-sm font-normal">
                                            {{ __('Company with the highest percentage of renewable energy') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="self-stretch px-4 py-1 bg-[#153A5B1A] rounded justify-between items-center inline-flex">
                                <div class="justify-start items-center gap-2 flex">
                                    <div>
                                        <span class="text-[#153A5B] text-xl">40th</span>
                                        <span class="text-sm font-normal">
                                            {{ __('Company in the business sector') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center w-full">
                                <x-charts.donut id="rank_renewable_energy_comopany" class="m-auto !h-[180px] !w-[180px] mt-5" />
                                <div class="grid content-center mt-5" id="rank_renewable_energy_comopany-legend"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>


            <x-report.pagewithrightimage title="{{ __('ESG’s scores') }}" bgimage="{{ url('images/report/assess/page8.png') }}" footerCount="07">
                <div class="py-5 min-h-[560px]">
                    <div class="flex-col gap-4 inline-flex">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Total of GHG emissions') }}</p>
                        <div class="self-stretch px-4 py-1 bg-[#153A5B1A] rounded justify-between items-center inline-flex">
                            <div class="justify-start items-center gap-2 flex">
                                <div>
                                    <span class="text-[#153A5B] text-xl">400th</span>
                                    <span class="text-sm font-normal">
                                        {{ __('Company with the highest percentage of renewable energy') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="self-stretch px-4 py-1 bg-[#153A5B1A] rounded justify-between items-center inline-flex">
                            <div class="justify-start items-center gap-2 flex">
                                <div>
                                    <span class="text-[#153A5B] text-xl">40th</span>
                                    <span class="text-sm font-normal">
                                        {{ __('Company in the business sector') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center w-full">
                            <x-charts.donut id="rank_renewable_energy_comopany2" class="m-auto !h-[180px] !w-[180px] mt-5" />
                            <div class="grid content-center mt-5" id="rank_renewable_energy_comopany2-legend"></div>
                        </div>
                    </div>
                </div>
             </x-report.pagewithrightimage>
            --}}
            <x-report.pagewithimage url="/images/report/assess/page9.png" footer="true" footerCount="" header="true" footerborder="border-t-esg2" footerCount="08">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg2">{{ __('environment') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.pagewithimage>


            <x-report.page title="{{ __('Climate change') }}" footerborder="border-t-esg2" footerCount="09">
                <div class="py-5 print:py-0 print">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-5 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('High climate impact activities') }}</p>
                                <x-cards.cards-value-unit :value="count($dashboardData['climate_change']['high_climate_impact_activities']['checkbox_lables'])" :unit="__('activities')" xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                                
                                @if (count($dashboardData['climate_change']['high_climate_impact_activities']['checkbox_lables']) > 0)
                                    <label for="checkbox-website"
                                        class="font-encodesans text-4xl font-medium text-esg8">
                                        {{ count($dashboardData['climate_change']['high_climate_impact_activities']['checkbox_lables']) }}
                                        <span class="text-base text-esg8">{{ __('activities') }}</span>
                                    </label>
                                    <div class="">
                                        <x-cards.cards-checkbox-list :list="$dashboardData['climate_change'][
                                            'high_climate_impact_activities'
                                        ]['checkbox_lables']" color="bg-esg2" />
                                    </div>
                                @endif
                            </div>
                            <div>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Operates in the fossil fuel sector') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['climate_change']['operates_in_the_fossil_fuel_sector']['values']" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>

                            <div>
                                <p class="text-lg font-bold uppercase text-esg8">{{ __('Energy consumption') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Operates in the fossil fuel sector') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['climate_change']['energy_consumption_monitoring']['values']" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Policy for energy consumption reduction') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['climate_change'][
                                            'policy_for_energy_consumption_reduction'
                                        ]['values']" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Energy intensity') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['climate_change']['energy_intensity']['values']" :unit="__('MWh / €')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div class="">
                                @php
                                    $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Renewable')], ['color' => 'bg-[#99CA3C]', 'text' => __('Non-renewable')]]);
                                @endphp

                                <x-cards.card-dashboard-version1-withshadow type="none" contentplacement="none" class="!h-auto shadow-none !bg-transparent !p-0">
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
                                                class="m-auto !h-[170px] !w-[170px] print:!h-[130px] print:!w-[130px] mt-5" />
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
                                                class="m-auto !h-[170px] !w-[170px] print:!h-[130px] print:!w-[130px] mt-5" />
                                            <div class="grid content-center mt-5" id="energy_consumption_reporting-legend">
                                            </div>
                                        </div>
                                    @endif
                                    @if (
                                        !empty($charts['climate_change']['energy_consumption']['base_line_year']) &&
                                            !empty($charts['climate_change']['energy_consumption']['reporting_year']))
                                    </div>
                                    @endif

                                    <div class="flex justify-center gap-5">
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
                                <p class="text-base text-esg8">{{ __('Energy from non-renewable sources consumed') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach(array_slice($charts['climate_change']['energy_from_non_renewable_sources_consumed']['labels'], 0, 5) as $labelKey => $label)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $label }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-value-unit :value="$charts['climate_change']['energy_from_non_renewable_sources_consumed']['dataset'][$labelKey]" :unit="__('MWh')" xclass="font-encodesans font-normal text-esg8 text-sm" xunitClass="text-sm text-esg8" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div class="">
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach(array_slice($charts['climate_change']['energy_from_non_renewable_sources_consumed']['labels'], 5) as $labelKey => $label)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $label }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-value-unit :value="$charts['climate_change']['energy_from_non_renewable_sources_consumed']['dataset'][$labelKey]" :unit="__('MWh')" xclass="font-encodesans font-normal text-esg8 text-sm" xunitClass="text-sm text-esg8" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div>
                                <img src="/images/report/assess/page10.png">
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Climate change') }}" footerborder="border-t-esg2 !text-esg4" bgimage="/images/report/assess/page11.png" footerCount="10">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-base text-esg8">{{ __('Energy from renewable sources consumed') }}</p>
                            <x-report.table.table class="!border-t-esg2">
                                @foreach($charts['climate_change']['energy_from_renewable_sources_consumed']['labels'] as $labelKey => $label)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $label }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-value-unit :value="$charts['climate_change']['energy_from_renewable_sources_consumed']['dataset'][$labelKey]" :unit="__('MWh')" :isNumber=true xclass="font-encodesans font-normal text-esg8 text-sm" xunitClass="text-sm text-esg8" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Green House Gas Emissions (GHG) ') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Scope 1 Emissions') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Emissions Scope 1 monitoring') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['climate_change']['emissions_scope_1_monitoring'][
                                                'values'
                                            ]" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                                
                                <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none !bg-transparent !p-0">
                                    <x-charts.bar id="emissions" class="m-auto relative !h-full !w-full" unit="t CO2 eq" />
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Main sources') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach ($dashboardData['climate_change']['main_sources_of_scope_1_emissions']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-list-value-unit :value="$list['value']" :unit="__('t CO2 eq')" :isNumber=true xclass='text-sm font-normal text-esg16' />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>


            <x-report.page title="{{ __('Climate change') }}" footerborder="border-t-esg2" footerCount="11">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Biomass burning or biodegradation') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Produces biogenic CO2 emissions derived from biomass burning or biodegradation') }}</x-report.table.td>
                                        <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$dashboardData['climate_change']['produces_biogenic_co_2_emissions'][
                                                        'values'
                                                    ]" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Monitors biogenic CO2 emissions derived from biomass burning or biodegradation') }}</x-report.table.td>
                                        <x-report.table.td>
                                        <x-cards.cards-checkbox :value="$dashboardData['climate_change']['monitors_biogenic_co_2_emissions'][
                                        'values'
                                    ]" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td colspan="2" class="bg-[#99CA3C1A]">{{ __('Emission') }}</x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Biogenic CO2 emissions') }}</x-report.table.td>
                                        <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$dashboardData['report']['biogenic_co2_emissions'][
                                                        'values'
                                                    ]" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>
                            
                            <div>
                                <p class="text-xl font-bold text-esg8">{{ __('Scope 2 Emissions') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Emissions Scope 2 monitoring') }}</x-report.table.td>
                                        <x-report.table.td>
                                        <x-cards.cards-checkbox :value="$dashboardData['climate_change']['emissions_scope_2_monitoring'][
                                            'values'
                                        ]" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                                
                                <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none !bg-transparent !p-0">
                                    <x-charts.bar id="emissions2" class="m-auto relative !h-full !w-full" unit="t CO2 eq" />
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>

                        <div class="col-span-2">
                            
                            <div>
                                <p class="text-base text-esg8">{{ __('Main sources') }}</p>
                                
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach ($dashboardData['climate_change']['main_sources_of_scope_2_emissions']['list'] as $list)
                                    <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-list-value-unit :value="$list['value']" :unit="__('t CO2 eq')" :isNumber=true xclass='text-sm font-normal text-esg16' />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <img src="/images/report/assess/page12.png" >
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Climate change') }}" footerborder="border-t-esg2" footerCount="12">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-xl font-bold text-esg8">{{ __('Scope 3 Emissions') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Emissions Scope 3 monitoring') }}</x-report.table.td>
                                        <x-report.table.td>
                                        <x-cards.cards-checkbox :value="$dashboardData['climate_change']['emissions_scope_3_monitoring'][
                                            'values'
                                        ]" :color="color(2)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                                
                                <x-cards.card-dashboard-version1-withshadow class="!h-auto !shadow-none !bg-transparent !p-0">
                                    <x-charts.bar id="emissions3" class="m-auto relative !h-full !w-full" unit="t CO2 eq" />
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Main sources') }}</p>
                                <x-report.table.table class="!border-t-esg2 !mt-0">
                                    @foreach ($dashboardData['climate_change']['main_sources_of_scope_3_emissions']['list'] as $list)
                                    <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($list['is_boolean'])
                                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                                @else
                                                    <x-cards.cards-list-value-unit :value="$list['value']" :unit="__('t CO2 eq')" :isNumber=true xclass='text-sm font-normal text-esg16' />
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-xl font-bold text-esg8">{{ __('GHG emissions removed/stored') }}</p>           
                            <div>
                                <p class="text-base text-esg8">{{ __('Natural removal (forest)') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['climate_change'][
                                    'ghg_emissions_removed_stored_natural_removal_forest'
                                ]['values']" :unit="__('t CO2 eq / €')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                            </div>

                            <div>
                                <p class="text-base text-esg8">{{ __('Storage through technology') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['climate_change'][
                                    'ghg_emissions_removed_stored_storage_through_technology'
                                ]['values']" :unit="__('t CO2 eq / €')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                            </div>

                            <div>
                                <p class="text-base text-esg8">{{ __('Carbon intensity') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['climate_change']['carbon_intensity']['values']" :unit="__('t CO2 eq / €')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                            </div>
                        </div>

                    </div>
                </div>
            </x-report.page>



            <x-report.page title="{{ __('POLLUTION') }}" footerborder="border-t-esg2" footerCount="13">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Substances') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach ($dashboardData['polution']['list_1']['list'] as $list)
                                    <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($list['is_boolean'])
                                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                                @else
                                                    <x-cards.cards-list-value-unit :value="$list['value']" :unit="__('t CO2 eq')" :isNumber=true xclass='text-sm font-normal text-esg16' />
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <p class="text-xl font-bold text-esg8">{{ __('Amount generated') }}</p>
                            @foreach ($dashboardData['polution']['amounts_of_substances_generated']['list'] as $list)
                                <div>
                                    <p class="text-base text-esg8">{{ $list['label'] }}</p>
                                    <x-cards.cards-value-unit :value="$list['value']" :unit="__('t CO2 eq')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Emission of pollutants') }}</p>
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Air pollutants') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{__('Name')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline year')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline value')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Report')}}</x-report.table.td>
                                    </x-report.table.tr>
                                    @foreach (array_slice($charts['polution']['emission_of_air_pollutants']['chartData']['optionsList'], 0, 6) as $listkey => $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_air_pollutants']['chartData']['baseYears'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_air_pollutants']['chartData']['baseYearValues'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>
                                            <x-cards.cards-list-value-unit :value="$charts['polution']['emission_of_air_pollutants']['chartData']['reportYearValues'][$listkey]" :unit="__('t CO2 eq')" :isNumber=true xclass='text-sm font-normal text-esg16' /></x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach (array_slice($charts['polution']['emission_of_air_pollutants']['chartData']['optionsList'], 6) as $listkey => $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_air_pollutants']['chartData']['baseYears'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_air_pollutants']['chartData']['baseYearValues'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>
                                            <x-cards.cards-list-value-unit :value="$charts['polution']['emission_of_air_pollutants']['chartData']['reportYearValues'][$listkey]" :unit="__('t CO2 eq')" :isNumber=true xclass='text-sm font-normal text-esg16' /></x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('POLLUTION') }}" footerborder="border-t-esg2" footerCount="14">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Water and soil') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{__('Name')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline year')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline value')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Report')}}</x-report.table.td>
                                    </x-report.table.tr>
                                    @foreach (array_slice($charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['optionsList'], 0, 5) as $listkey => $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['baseYears'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['baseYearValues'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>
                                            <x-cards.cards-list-value-unit :value="$charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['reportYearValues'][$listkey]" :unit="__('t')" :isNumber=true xclass='text-sm font-normal text-esg16' /></x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach (array_slice($charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['optionsList'], 5) as $listkey => $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['baseYears'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['baseYearValues'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>
                                            <x-cards.cards-list-value-unit :value="$charts['polution']['emission_of_pollutants_water_and_soil']['chartData']['reportYearValues'][$listkey]" :unit="__('t')" :isNumber=true xclass='text-sm font-normal text-esg16' /></x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Other pollutants') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{__('Name')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline year')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline value')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Report')}}</x-report.table.td>
                                    </x-report.table.tr>
                                    @foreach ($charts['polution']['emission_of_pollutants_other']['chartData']['optionsList'] as $listkey => $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_pollutants_other']['chartData']['baseYears'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['polution']['emission_of_pollutants_other']['chartData']['baseYearValues'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>
                                            <x-cards.cards-list-value-unit :value="$charts['polution']['emission_of_pollutants_other']['chartData']['reportYearValues'][$listkey]" :unit="__('t')" :isNumber=true xclass='text-sm font-normal text-esg16' /></x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <img src="/images/report/assess/page15.png">
                            </div>
                        </div>
                </div>
            </x-report.page>
            
             <x-report.page title="{{ __('Water and marine  resources') }}" footerborder="border-t-esg2" bgimage="/images/report/screen/page10_bg.png" footerCount="15">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4 pb-40">
                        
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Use of water resources') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach ($dashboardData['water_and_marine']['use_of_water_resources']['list'] as $list)
                                    <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                @if ($list['is_boolean'])
                                                    <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                                @else
                                                    <x-cards.cards-list-value-unit :value="$list['value']" :unit="__('t CO2 eq')" :isNumber=true xclass='text-sm font-normal text-esg16' />
                                                @endif
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Source of water consumed') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['water_and_marine']['source_of_water_consumed'][
                                        'checkbox_lables'
                                    ]" xclass='font-encodesans font-medium text-xl text-esg2' />
                            </div>
                        </div>            
                        <div class="flex-col gap-4 inline-flex">
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Water intensity') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['water_and_marine']['water_intensity']['values']" :unit="__('m3 / €')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                            </div>

                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Water consumption') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{__('Name')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline year')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline value')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Report')}}</x-report.table.td>
                                    </x-report.table.tr>
                                    @foreach ($charts['report']['water_consumed']['chartData']['optionsList'] as $listkey => $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['report']['water_consumed']['chartData']['baseYears']}}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['report']['water_consumed']['chartData']['baseYearValues'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>
                                            <x-cards.cards-list-value-unit :value="$charts['report']['water_consumed']['chartData']['reportYearValues'][$listkey]" :unit="$charts['report']['water_consumed']['chartData']['unit']" :isNumber=true xclass='text-sm font-normal text-esg16' /></x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('water discharged') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Environment to where the waste water is discharged') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['water_and_marine'][
                                    'environment_waste_water_is_discharged'
                                ]['checkbox_lables']"  xclass='font-encodesans font-medium text-xl text-esg2' />
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>
            <x-report.page title="{{ __('Biodiversity and ecosystems') }}" footerborder="border-t-esg2" footerCount="16">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Impacts on biodiversity and ecosystems') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach ($dashboardData['biodiversity_and_ecosystems']['impacts_on_biodiversity_and_ecosystems']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8">{{ __('Operations in or near protected areas') }}</p>
                            @foreach ($dashboardData['biodiversity_and_ecosystems']['operation_protected_areas']['list'] as $list)
                                <div>
                                    <p class="text-base text-esg8">{{ $list['label'] }}</p>
                                    <x-cards.cards-value-unit :value="$list['value']" :unit="__('operations')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <img src="/images/report/assess/page17.png">
                        </div>
                    </div>
                </div>
             </x-report.page>

             <x-report.page title="{{ __('Use of resources and circular economy') }}" footerborder="border-t-esg2" footerCount="17">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Products and Raw materials') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Raw-materials consumption') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach ($dashboardData['use_of_resources']['raw_materials_consumption']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(2)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div>
                                <p class="text-base text-esg8">{{ __('Products and/or raw materials used') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach (array_slice($dashboardData['use_of_resources']['products_materials_used']['list'], 0, 4) as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-list-value-unit :value="$list['value']" :unit="__('t')" :isNumber=true xclass='text-sm font-normal text-esg16' />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <x-report.table.table class="!border-t-esg2">
                                    @foreach (array_slice($dashboardData['use_of_resources']['products_materials_used']['list'], 4) as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-list-value-unit :value="$list['value']" :unit="__('t')" :isNumber=true xclass='text-sm font-normal text-esg16' />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('waste') }}</p>
                                <x-report.table.table class="!border-t-esg2">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{__('Name')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline year')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Baseline value')}}</x-report.table.td>
                                        <x-report.table.td>{{__('Report')}}</x-report.table.td>
                                    </x-report.table.tr>
                                    @foreach ($charts['use_of_resources']['waste']['chartData']['optionsList'] as $listkey => $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['use_of_resources']['waste']['chartData']['baseYears'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>{{ $charts['use_of_resources']['waste']['chartData']['baseYearValues'][$listkey] }}</x-report.table.td>
                                            <x-report.table.td>
                                            <x-cards.cards-list-value-unit :value="$charts['use_of_resources']['waste']['chartData']['reportYearValues'][$listkey]" :unit="__('t')" :isNumber=true xclass='text-sm font-normal text-esg16' /></x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Waste distribution') }}</p>
                                <x-charts.donut id="water_distribution" class="m-auto !h-[180px] !w-[180px] mt-5" />
                                    <div class="grid content-center mt-5" id="water_distribution-legend"></div>
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Total Waste Generated') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['use_of_resources']['total_waste_generated']['values']" :unit="__('t')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg2' xunitClass="text-base text-esg2"/>
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Final destination for radioactive waste') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['use_of_resources']['destination_for_radioactive']['checkbox_lables']" xclass='font-encodesans font-medium text-xl text-esg2' />
                            </div>
                        </div>
                    </div>
                </div>
             </x-report.page>

             <x-report.pagewithrightimage title="{{ __('Use of resources and circular economy') }}" footerborder="border-t-esg2" bgimage="/images/report/assess/page19.png" footerCount="18">
                <div class="py-5 min-h-[560px]">
                    <p class="text-base text-esg8">{{ __('Final destination applied') }}</p>
                    <div class="grid grid-cols-1 gap-10 print:gap-2">
                        <div class="grid content-center justify-center">
                            <div>
                                <canvas id="final_destination_applied" class="m-auto" height="300" width="300"></canvas>
                            </div>
                        </div>
                        <div class="grid content-center h-full" id="final_destination_applied-legend"></div>
                    </div>
                </div>
             </x-report.pagewithrightimage>

             <x-report.pagewithimage url="/images/report/assess/page20.png" footer="true" footerCount="19" header="true" footerborder="border-t-esg1">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg1">{{ __('Social') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Climate change') }}" footerborder="border-t-esg1" footerCount="20">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Workers characterization') }}</p>
                            <p class="text-lg font-bold text-esg8">{{ __('Contract specification') }}</p>
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Category of contract')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="category_contract" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Type of contract')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="type_contract" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                        @php
                            $subpointGender = json_encode([['color' => 'bg-[#21A6E8]', 'text' => __('Male')], ['color' => 'bg-[#C5A8FF]', 'text' => __('Female')], ['color' => 'bg-[#02C6A1]', 'text' => __('Other')]]);
                            $subpointAge = json_encode([['color' => 'bg-[#E86321]', 'text' => __('Less than 30')], ['color' => 'bg-[#FBB040]', 'text' => __('Between 30 and 50')], ['color' => 'bg-[#FDC97B]', 'text' => __('More than 50')]]);
                        @endphp
                        <div class="col-span-2 flex-col gap-4 inline-flex">
                            <div class="col-span-2 flex-col gap-4 inline-flex">
                                <p class="text-lg font-bold text-esg8">{{ __('Total') }}</p>
                                <div class="grid grid-cols-2 gap-10">
                                    <div>
                                        <p class="text-base text-esg8">{{ __('Contracted workers') }}</p>
                                        <x-charts.donut id="contracted_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    </div>
                                    <div>
                                        <p class="text-base text-esg8">{{ __('Subcontracted workers') }}</p>
                                        <x-charts.donut id="subcontracted_workers" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                                    </div>
                                </div>
                                <div>
                                    <img src="/images/report/assess/page21.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Workers in the organization') }}" footerborder="border-t-esg1" footerCount="21">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        @php
                            $subpointGender = json_encode([['color' => 'bg-[#21A6E8]', 'text' => __('Male')], ['color' => 'bg-[#C5A8FF]', 'text' => __('Female')], ['color' => 'bg-[#02C6A1]', 'text' => __('Other')]]);
                            $subpointAge = json_encode([['color' => 'bg-[#E86321]', 'text' => __('Less than 30')], ['color' => 'bg-[#FBB040]', 'text' => __('Between 30 and 50')], ['color' => 'bg-[#FDC97B]', 'text' => __('More than 50')]]);
                        @endphp
                        <div class="col-span-2 flex-col gap-4 inline-flex">
                            <div class="col-span-2 flex-col gap-4 inline-flex">
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Age distribution') }}</p>
                                <div class="grid grid-cols-2">
                                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted workers')]) }}"  class="!h-min !shadow-none !normal-case !bg-transparent !p-0"  titleclass="!normal-case">
                                        <x-charts.bar id="contracted_worker_age" class="m-auto relative !h-full !w-full"/>
                                    </x-cards.card-dashboard-version1-withshadow>

                                    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Subcontracted workers')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                        <x-charts.bar id="subcontracted_worker_age" class="m-auto relative !h-full !w-full"/>
                                    </x-cards.card-dashboard-version1-withshadow>
                                </div>
                                <div>
                                    <img src="/images/report/assess/page22.png">
                                </div>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Minorities') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{__('Workers that belong to minorities')}}</p>
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach ($charts['workers_in_org']['workers_minorities']['labels'] as $lableKey=>$label)
                                    <x-report.table.tr>
                                            <x-report.table.td>{{ $label }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-number :value="$charts['workers_in_org']['workers_minorities']['dataset'][$lableKey]" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Percentage of workers that belong to minorities') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['report']['percentage_worker_minority']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>

                        </div>
                        
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Workers in the organization') }}" footerborder="border-t-esg1" footerCount="22">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('management') }}</p>
                            <p class="text-base text-esg8">{{__('Middle management')}}</p>
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted by gender')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="middle_management_gender_contracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                            <div>
                                <img src="/images/report/assess/page23.png">
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted by age')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="middle_management_age_contracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Subcontracted by gender')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="middle_management_gender_subcontracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>


                        <div class="flex-col gap-4 inline-flex">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Subcontracted by age')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="middle_management_age_subcontracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                            
                            <p class="text-base text-esg8">{{ __('Top management') }}</p>
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted by gender')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="top_management_gender_contracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Workers in the organization') }}" footerborder="border-t-esg1" footerCount="23">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Contracted by age')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="top_management_age_contracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Subcontracted by gender')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="top_management_gender_subcontracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Subcontracted by age')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="top_management_age_subcontracted" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                            
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('recruitment and layoffs') }}</p>
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Contracted workers turnover') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['contracted_workers_turnover']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                        </div>


                        <div class="flex-col gap-4 inline-flex">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Distribution of hiring and layoffs')]) }}" class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="hiring_layoffs" class="m-auto relative !h-full !w-full"/>
                            </x-cards.card-dashboard-version1-withshadow>
                            
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('salary') }}</p>
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Gender pay gap') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['gender_pay_gap']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>


            <x-report.page title="{{ __('Workers in the organization') }}" footerborder="border-t-esg1" footerCount="24">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Hourly earning variation')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="earning_variation" class="m-auto relative !h-full !w-full" unit="€" />
                            </x-cards.card-dashboard-version1-withshadow>

                            <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Salary variation')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                <x-charts.bar id="salary_variation" class="m-auto relative !h-full !w-full" unit="€"  />
                            </x-cards.card-dashboard-version1-withshadow>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('safety and health') }}</p>
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Safety and health at work') }}</p>
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach ($dashboardData['workers_in_org']['safety_and_health_at_work']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Accidents at work') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['accidents_at_work']['values']" :unit="__('accidents')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>

                            <div class="">
                                <p class="text-base text-esg8">{{ __('Days lost (injury, accident, death or illness)') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['days_lost']['values']" :unit="__('days')" :isNumber=true  xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('training') }}</p>
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Training for the workers') }}</p>
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach ($dashboardData['workers_in_org']['training_for_the_workers']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                            <div class="">
                                <p class="text-base text-esg8">{{ __('Number of hours on training') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['hours_on_training']['values']" :unit="__('hours')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>

                            <div class="">
                                <p class="text-base text-esg8">{{ __('Contracted workers that attended training)') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['workers_in_org']['workers_attended_training']['values']" :unit="__('workers')" :isNumber=true  xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.pagewithrightimage title="{{ __('Workers in the organization') }}" footerborder="border-t-esg1" bgimage="/images/report/assess/page26.png" footerCount="25">
                <div class="py-5 min-h-[560px]">
                    <div class="flex-col gap-4 inline-flex">
                        
                        <div>
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('value chain workers') }}</p>
                            <x-report.table.table class="!border-t-esg1">
                                @foreach ($dashboardData['workers_value_chain']['value_chain_workers']['list'] as $list)
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                @endforeach
                            </x-report.table.table>
                        </div>
                        <div>
                        <p class="text-base font-bold text-esg8 uppercase">{{ __('Topics covered in the policies to manage impacts, risks and opportunities') }}</p>
                        <x-cards.cards-checkbox-list :list="$dashboardData['workers_value_chain']['topics_covered_in_the_policies']['checkbox_lables']" color="bg-esg1" />
                        </div>
                    </div>
                </div>
            </x-report.pagewithrightimage>


            <x-report.page title="{{ __('Affected communities') }}" footerborder="border-t-esg1" footerCount="26">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Affected communities') }}</p>
                            <div>
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach ($dashboardData['affected_communities']['affected_communities']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            
                            <div>
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Topics covered in the policies to manage impacts, risks and opportunities') }}</p>
                                <x-cards.cards-checkbox-list :list="$dashboardData['affected_communities']['topics_covered_in_the_policies']['checkbox_lables']" color="bg-esg1" />
                            </div>

                            
                            <div>
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Type of investments made in the community') }}</p>
                                <x-cards.cards-checkbox-list :list="$dashboardData['affected_communities']['type_of_investments_made_in_the_community']['checkbox_lables']" color="bg-esg1" />
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Financial investment in the community') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['affected_communities']['operations_of_the_local_community']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>

                            <div>
                                <p class="text-base text-esg8">{{ __('Operations of the local community') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['affected_communities']['financial_investment_in_the_community']['values']" :unit="__('€')" :isCurrency=true  xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>

                            <div>
                                <p class="text-base text-esg8">{{ __('Volunteer hours undertaken by the  contracted workers') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['affected_communities']['volunteer_hours_contracted_workers']['values']" :unit="__('hours')" :isNumber=true  xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>


            <x-report.page title="{{ __('Consumers and end-users') }}" footerborder="border-t-esg1" footerCount="27">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Consumers and End-Users') }}</p>
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach ($dashboardData['consumers_and_end_users']['consumers_and_end_users']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(1)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div>
                                <p class="text-base text-esg8">{{ __('Number of Consumers and/or end-users') }}</p>
                                <x-report.table.table class="!border-t-esg1">
                                    @foreach ($dashboardData['report']['number_of_consumer_end_user_list']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-number :value="$list['value']" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Variation of new consumers and/or end users') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['consumers_and_end_users']['variation_of_new_consumers']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('satisfaction') }}</p>
                            
                            @foreach (array_slice($dashboardData['consumers_and_end_users']['level_of_satisfaction']['list'], 0, 4) as $list)
                            <div>
                                <p class="text-base text-esg8">{{ __($list['label']) }}</p>
                                <x-cards.cards-value-unit :value="$list['value']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                            @endforeach
                            
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            @foreach (array_slice($dashboardData['consumers_and_end_users']['level_of_satisfaction']['list'], 4) as $list)
                            <div>
                                <p class="text-base text-esg8">{{ __($list['label']) }}</p>
                                <x-cards.cards-value-unit :value="$list['value']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg1' xunitClass="text-base text-esg1"/>
                            </div>
                            @endforeach
                            <div>
                                <img src="/images/report/assess/page28.png" />
                            </div>
                            
                        </div>
                    </div>
                </div>
            </x-report.page>
            <x-report.pagewithimage url="/images/report/assess/page29.png" footer="true" footerCount="28" header="true" footerborder="border-t-esg3">
                <div class="">
                    <p class="text-6xl font-extrabold uppercase text-esg3">{{ __('governance') }}</p>
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('performance') }}</p>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Main conduct policies and corporate culture') }}" footerborder="border-t-esg3" footerCount="29">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Highest governance body') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Structure and constitution') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['conduct_policies_corporate_culture'][
                            'highest_governance_body_of_the_organisation']['checkbox_lables']" xclass='font-encodesans font-medium text-xl text-esg3' />
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Gender distribution') }}</p>
                                <x-charts.donut id="gender_high_governance_body" class="m-auto !h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]" legendes="true" grid="1"/>
                            </div>

                            
                        </div>
                        
                        <div class="flex-col gap-4 inline-flex">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Revenue') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Annual revenue') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['conduct_policies_corporate_culture']['annual_revenue']['values']" :unit="__('€')" :isCurrency=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Annual net revenue') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['conduct_policies_corporate_culture']['annual_net_revenue']['values']" :unit="__('€')" :isCurrency=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                                
                            <div>
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('annual reporting') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['conduct_policies_corporate_culture']['annual_reporting']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <img src="/images/report/assess/page30.png" />
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Relations with suppliers and payment practices') }}" footerborder="border-t-esg3" footerCount="30">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8 uppercase">{{ __('Characteristics of suppliers') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['relations_with_suppliers']['characteristics_of_suppliers']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Type of suppliers') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($charts['relations_with_suppliers']['type_of_suppliers']['labels'] as $lableKey => $lable)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $lable }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-number :value="$charts['relations_with_suppliers']['type_of_suppliers']['dataset'][$lableKey]" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Suppliers by continent') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($charts['relations_with_suppliers']['suppliers_by_continent']['labels'] as $lableKey => $lable)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $lable }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-number :value="$charts['relations_with_suppliers']['suppliers_by_continent']['dataset'][$lableKey]" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Suppliers by industry sector') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($charts['relations_with_suppliers']['suppliers_by_industry_sector']['labels'] as $lableKey => $lable)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $lable }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-number :value="$charts['relations_with_suppliers']['suppliers_by_industry_sector']['dataset'][$lableKey]" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>


            <x-report.page title="{{ __('Relations with suppliers and payment practices') }}" footerborder="border-t-esg3" footerCount="31">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('impacts') }}</p>
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Environmental impacts') }}</p>
                                <div>
                                    <p class="text-base text-esg8">{{ __('Suppliers assessed') }}</p>
                                    <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers'][
                                    'percentag_suppliers_environmental']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                                </div>
                            </div>

                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Suppliers causing actual or potential negative impacts') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Improvements have been agreed with as a result of the assessment') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-number :value="$dashboardData['report']['improvements_result_of_the_assessment']['values']" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Organisation ceased business relations') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-number :value="$dashboardData['report']['organisation_ceased_business_relations']['values']" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>

                            </div>

                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Social impacts') }}</p>
                                <div>
                                    <p class="text-base text-esg8">{{ __('Suppliers assessed') }}</p>
                                    <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers'][
                                    'percentag_suppliers_social']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                                </div>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Suppliers causing actual or potential negative impacts') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Improvements have been agreed with as a result of the assessment') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-number :value="$dashboardData['report']['social_improvements_result_of_the_assessment']['values']" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Organisation ceased business relations') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-number :value="$dashboardData['report']['social_organisation_ceased_business_relations']['values']" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>

                            </div>

                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('payment') }}</p>
                            
                            <p class="text-lg font-bold text-esg8">{{ __('Average annual monetary value') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Payments made to suppliers') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers']['payments_made_to_suppliers']['values']" :unit="__('€')" :isCurrency=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Going to local suppliers') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers']['going_to_local_suppliers']['values']" :unit="__('€')" :isCurrency=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Payment ratio to local suppliers') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers'][
                                'payment_ratio_to_local_suppliers']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            
                        </div>

                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-lg font-bold text-esg8">{{ __('Timings for payment') }}</p>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Timings for payment to suppliers')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0">
                                    <x-charts.bar id="supplier_payment_timing" class="m-auto relative !h-full !w-full"/>
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Lawsuits for late payment to suppliers') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['relations_with_suppliers']['lawsuits_for_late_payment_to_suppliers']['values']" xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Corruption and bribery prevention and detection') }}" footerborder="border-t-esg3" footerCount="32">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Prevention') }}</p>
                            <div>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['corruption_prevention_detection']['corruption_prevention_detection']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('cases') }}</p>
                            <div>
                                <p>{{ __('Confirmed cases in which contracts with business partners were terminated or not renewed as a result of violations') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['corruption_prevention_detection']['business_partners_were_terminated']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-number :value="$list['value']" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                            <div>
                                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Cases of corruption and/or bribery')]) }}"  class="!h-min !shadow-none !bg-transparent !p-0" titleclass="!normal-case">
                                    <x-charts.bar id="corruption_bribery" class="m-auto relative !h-full !w-full"/>
                                </x-cards.card-dashboard-version1-withshadow>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('legal impact') }}</p>  
                            <div>
                                <p class="text-base text-esg8">{{ __('Number of convictions') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['corruption_prevention_detection']['number_of_convictions']['values']" :unit="__('convictions')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Monetary value of the fines imposed') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['corruption_prevention_detection']['monetary_value_of_the_fines_imposed']['values']" :unit="__('€')" :isCurrency=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Number of legal proceedings initiated against the organisation or its workers') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['corruption_prevention_detection']['proceedings_against_organisation']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-number :value="$list['value']" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Risk Management') }}" footerborder="border-t-esg3" footerCount="33">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('incidents of discrimination') }}</p>
                            <div>
                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Incidents of discrimination, in particular resulting in the application of sanctions') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['risk_management']['incidents_of_discrimination_sanctions']['values']" :color="color(3)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Incidents of discrimination') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['risk_management']['incidents_of_discrimination']['values']" :unit="__('incidents')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Corruption and bribery') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Corruption and bribery assessment and risks') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['risk_management']['corruption_assessment_and_risks']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>

                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Operations assessed for risks of corruption') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['risk_management']['operation_assesed_for_risk']['values']" :unit="__('operations')" :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Types of risks identified in the corruption and/or bribery assessment') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['risk_management']['types_of_risks_identified']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Human rights, forced labor and modern slavery') }}</p>
                            <div>
                                <p class="text-base text-esg8">{{ __('Human rights, forced labor and modern slavery risks and assessment') }}</p>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['risk_management']['human_rights']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.page title="{{ __('Risk Management') }}" footerborder="border-t-esg3" footerCount="34">
                <div class="py-5 min-h-[560px]">
                    <div class="grid grid-cols-3 print:grid-cols-3 gap-10 mt-4">
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Operations assessed for Human rights, forced labor and modern slavery risks') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['risk_management']['operations_human_rights']['values']" :unit="__('%')" xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>

                            <div>
                                <p class="text-base text-esg8">{{ __('Investments that include human rights clauses or undergone assessment') }}</p>
                                <x-cards.cards-value-unit :value="$dashboardData['risk_management']['investments_human_rights']['values']" :unit="__('investiments')"  :isNumber=true xclass='font-encodesans font-medium text-4xl text-esg3' xunitClass="text-base text-esg3" />
                            </div>
                            
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('social and environmental impacts') }}</p>
                            <div>
                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Studies regarding the relevant social and environmental impacts of the organisation') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['risk_management']['studies_relevant_social_impacts']['values']" :color="color(3)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Existence of vulnerable groups impacted by the organisation') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['risk_management']['vulnerable_groups_impacted']['values']" :color="color(3)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Creation of due diligence processes for environment and social significant impacts') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['risk_management']['creation_of_due_diligence_processes']['values']" :color="color(3)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>

                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <div>
                                <p class="text-base text-esg8">{{ __('Impacts considered in the studies related to environment and social') }}</p>
                                <x-cards.cards-checkbox-list :list="$dashboardData['risk_management']['studies_related_to_environment']['checkbox_lables']" color="bg-esg3" />
                            </div>
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('Affected stakeholders') }}</p>
                            <div>
                                <x-report.table.table class="!border-t-esg3">
                                    <x-report.table.tr>
                                        <x-report.table.td>{{ __('Affected stakeholders identified by the organisation activities') }}</x-report.table.td>
                                        <x-report.table.td>
                                            <x-cards.cards-checkbox :value="$dashboardData['risk_management']['affected_stakeholders']['values']" :color="color(3)" />
                                        </x-report.table.td>
                                    </x-report.table.tr>
                                </x-report.table.table>
                            </div>
                            <div>
                                <p class="text-base text-esg8">{{ __('Types of affected stakeholders identified by the organisation activities') }}</p>
                                <x-cards.cards-checkbox-list :list="$dashboardData['risk_management']['types_affected_stakeholders']['checkbox_lables']" color="bg-esg3" />
                            </div>
                        </div>
                        <div class="flex-col gap-4 inline-flex">
                            <p class="text-lg font-bold text-esg8 uppercase">{{ __('economic impacts') }}</p>
                            <div>
                                <x-cards.cards-checkbox-list :list="$dashboardData['risk_management']['organisation_economic_impacts']['checkbox_lables']"  color="bg-esg3" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-report.page>

            <x-report.pagewithrightimage title="{{ __('ESG’s scores') }}" footerborder="border-t-esg3" bgimage="/images/report/assess/page36.png" footerCount="35">
                <div class="py-5 min-h-[560px]">
                    <div class="">
                        <p class="text-lg font-bold text-esg8 uppercase">{{ __('Unethical corporate behavior') }}</p>
                        <div class="flex-col justify-start items-start gap-4 inline-flex">
                            <div>
                                <x-report.table.table class="!border-t-esg3">
                                    @foreach ($dashboardData['risk_management']['unethical_corporate_behavior']['list'] as $list)
                                        <x-report.table.tr>
                                            <x-report.table.td>{{ $list['label'] }}</x-report.table.td>
                                            <x-report.table.td>
                                                <x-cards.cards-checkbox :value="$list['value']" :color="color(3)" />
                                            </x-report.table.td>
                                        </x-report.table.tr>
                                    @endforeach
                                </x-report.table.table>
                            </div>
                        </div>
                    </div>
                </div>
             </x-report.pagewithrightimage>
            <x-report.pagewithimage url="/images/report/assess/page37.png" footer="true" footerCount="" header="true" footerborder="border-t-esg3">
                <div class="">
                    <p class="text-6xl text-esg16 mt-3 uppercase">{{ __('statement of') }}</p>
                    <p class="text-6xl text-esg5 mt-3 font-extrabold uppercase">{{ __('responsability') }}</p>
                </div>
            </x-report.page>

            <x-report.pagewithrightimage title="{{ __('statement') }}" footerborder="border-t-esg3" bgimage="/images/report/assess/page37.png" footerCount="36">
                <div class="py-5 min-h-[560px]">
                    <div class="">
                        <p class="text-base text-esg8 mb-4">{{ __('Interdum et malesuada fames ac ante ipsum primis in faucibus. Aliquam elementum sollicitudin dui, et semper eros faucibus in. Morbi arcu ex, facilisis ut dolor eu, dignissim viverra elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec dapibus, libero vitae tincidunt luctus, ex erat pulvinar arcu, in venenatis magna massa sed neque. Aliquam ac nunc gravida, maximus elit id, ') }}</p>
                    </div>
                </div>
             </x-report.pagewithrightimage>

             <x-report.page title="{{ __('governance') }}" footerborder="border-t-esg3" lastpage="true" noheader="true" nofooter="true" footerCount="">
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
