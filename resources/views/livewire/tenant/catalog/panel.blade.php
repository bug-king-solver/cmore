@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            pieCharts(
                {!! json_encode([__('Custom'), __('Screen'), __('In-depth'), __('Start-up'), __('Suppliers')]) !!},
                {!! json_encode([30, 25, 25, 10, 10]) !!},
                'questionnaire_type',
                ['#E86321', '#EB8856', '#EFAD8B', '#F2CAB6', '#F5E7E1'],
                'questionnaires'
            );

            barCharts(
                {!! json_encode([__('up to a day'), __('less than a week'), __('up to 4 weeks')]) !!},
                {!! json_encode([
                    ['label' => __('Custom'), 'backgroundColor' => '#E86321', 'data' => [50, 60, 70]],
                    ['label' => __('Screen'), 'backgroundColor' => '#EB8856', 'data' => [10, 20, 30]],
                    ['label' => __('In-depth'), 'backgroundColor' => '#EFAD8B', 'data' => [30, 40, 50]],
                    ['label' => __('Start-up'), 'backgroundColor' => '#F2CAB6', 'data' => [10, 20, 10]],
                    ['label' => __('Suppliers'), 'backgroundColor' => '#F5E7E1', 'data' => [20, 20, 20]]
                ]) !!},
                'client_avg',
                null,
                'x',
                'multi'
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
                        borderColor: '{{ color(6) }}',
                        barPercentage: 0.5
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
                            borderColor: '{{ color(8) }}',
                            offset: true
                        }
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
                                            <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value})</div>
                                        </div>
                                    `;
                                } else {
                                    html += `
                                    <div class="">
                                        <div class="text-center text-sm text-esg8 leading-8"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value} ${centertext})</div>
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
    </script>
@endpush

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <x-cards.catalog.card-v1 class="!grid cursor-pointer transition ease-in-out duration-300 hover:z-10 hover:bg-gray-100" @click="#">
        <x-slot:data>
            <div class="flex items-center justify-between">
                <span class="text-lg text-esg16">{{ __('Created') }}</span>
                @include('icons.right-arrow')
            </div>

            <div class="flex items-end gap-3 mt-4">
                <label class="text-esg8 text-4xl font-bold">200</label>
                <span class="bg-[#165DFF]/10 text-[#165DFF] text-xs font-medium px-2.5 py-1 rounded-full ">0 %</span>
            </div>
        </x-slot:data>
    </x-cards.catalog.card-v1>

    <x-cards.catalog.card-v1 class="!grid cursor-pointer transition ease-in-out duration-300 hover:z-10 hover:bg-gray-100" @click="#">
        <x-slot:data>
            <div class="flex items-center justify-between">
                <span class="text-lg text-esg16">{{ __('Ongoing') }}</span>
                @include('icons.right-arrow')
            </div>

            <div class="flex items-end gap-3 mt-4">
                <label class="text-esg8 text-4xl font-bold">100</label>
                <span class="bg-[#F44336]/10 text-[#F44336] text-xs font-medium px-2.5 py-1 rounded-full ">-11.2 %</span>
            </div>
        </x-slot:data>
    </x-cards.catalog.card-v1>

    <x-cards.catalog.card-v1 class="!grid cursor-pointer transition ease-in-out duration-300 hover:z-10 hover:bg-gray-100" @click="#">
        <x-slot:data>
            <div class="flex items-center justify-between">
                <span class="text-lg text-esg16">{{ __('Submitted') }}</span>
                @include('icons.right-arrow')
            </div>

            <div class="flex items-end gap-3 mt-4">
                <label class="text-esg8 text-4xl font-bold">100</label>
                <span class="bg-[#00AE4E]/10 text-[#00AE4E] text-xs font-medium px-2.5 py-1 rounded-full ">+11.2 %</span>
            </div>
        </x-slot:data>
    </x-cards.catalog.card-v1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-6">
    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Top 5 business sectors ')]) }}" class="!h-auto" contentplacement="none">
        <div class="">
            <div class="flex justify-between mb-1">
                <span class="text-sm text-esg16">{{ __('Sector name small') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-full bg-esg7 rounded-full h-2.5">
                    <div class="bg-esg5 h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-esg5">80%</span>
                    <span class="text-xs text-esg16">(123)</span>
                </div>
            </div>
        </div>

        <div class="w-full mt-1">
            <div class="mb-1 w-3/12">
                <span class="text-sm text-esg16 truncate ">{{ __('Sector name with a looooooooooooooooooooooooooooooooot ddsdsd') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-full bg-esg7 rounded-full h-2.5">
                    <div class="bg-esg5 h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-esg5">80%</span>
                    <span class="text-xs text-esg16">(123)</span>
                </div>
            </div>
        </div>

        <div class="mt-1">
            <div class="flex justify-between mb-1">
                <span class="text-sm text-esg16">{{ __('Sector name small') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-full bg-esg7 rounded-full h-2.5">
                    <div class="bg-esg5 h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-esg5">80%</span>
                    <span class="text-xs text-esg16">(123)</span>
                </div>
            </div>
        </div>

        <div class="mt-1">
            <div class="flex justify-between mb-1">
                <span class="text-sm text-esg16">{{ __('Sector name small') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-full bg-esg7 rounded-full h-2.5">
                    <div class="bg-esg5 h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-esg5">80%</span>
                    <span class="text-xs text-esg16">(123)</span>
                </div>
            </div>
        </div>

        <div class="mt-1">
            <div class="flex justify-between mb-1">
                <span class="text-sm text-esg16">{{ __('Sector name small') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-full bg-esg7 rounded-full h-2.5">
                    <div class="bg-esg5 h-2.5 rounded-full" style="width: 45%"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-esg5">80%</span>
                    <span class="text-xs text-esg16">(123)</span>
                </div>
            </div>
        </div>
    </x-cards.card-dashboard-version1-withshadow>

    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Questionnaire by type')]) }}" class="!h-full" type="grid mt-16">
        <x-charts.donut id="questionnaire_type" class="m-auto !w-[200px] !h-[200px]" legendes="true"/>
    </x-cards.card-dashboard-version1-withshadow>
</div>

<div class="mt-6">
    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Client’s average time to complete a questionnaire')]) }}" contentplacement="none" class="!h-full" type="grid !mt-6">
        <div class="grid grid-cols-5 gap-5">
            <div class="col-span-4">
                <x-charts.bar id="client_avg" class="m-auto w-full h-[300px]" />
            </div>
            <div class="grid place-content-center">
                <div class="flex items-center">
                    <div class="mr-4 inline-block rounded-full p-2 text-left bg-[#E86321]"></div>
                    <div class="inline-block text-sm text-esg8">{{ __('Custom') }}</div>
                </div>

                <div class="flex items-center mt-2">
                    <div class="mr-4 inline-block rounded-full p-2 text-left bg-[#EB8856]"></div>
                    <div class="inline-block text-sm text-esg8">{{ __('Screen') }}</div>
                </div>

                <div class="flex items-center mt-2">
                    <div class="mr-4 inline-block rounded-full p-2 text-left bg-[#EFAD8B]"></div>
                    <div class="inline-block text-sm text-esg8">{{ __('In-depth') }}</div>
                </div>

                <div class="flex items-center mt-2">
                    <div class="mr-4 inline-block rounded-full p-2 text-left bg-[#F2CAB6]"></div>
                    <div class="inline-block text-sm text-esg8">{{ __('Start-up') }}</div>
                </div>

                <div class="flex items-center mt-2">
                    <div class="mr-4 inline-block rounded-full p-2 text-left bg-[#F5E7E1]"></div>
                    <div class="inline-block text-sm text-esg8">{{ __('Start-up') }}</div>
                </div>
            </div>
        </div>
    </x-cards.card-dashboard-version1-withshadow>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-6">
    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Top 5 clients by questionnaires answered')]) }}" class="!h-auto" contentplacement="none">
        <div class="">
            <div class="flex justify-between items-center bg-esg1/20 p-2 rounded-md">
                <div class="flex items-center gap-2">
                    @include('icons.trophy')
                    <span class="text-sm text-esg8"> {{ __('Amazing Client XPTO') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-xl font-bold text-esg1"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('questionnaires') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Second place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('questionnaires') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Third place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('questionnaires') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Fourth place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('questionnaires') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Fifth place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('questionnaires') }} </span>
                </div>
            </div>
        </div>
    </x-cards.card-dashboard-version1-withshadow>

    <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('Top 5 clients by avg time to complete a questionnaire')]) }}" class="!h-auto" contentplacement="none">
        <div class="">
            <div class="flex justify-between items-center bg-esg1/20 p-2 rounded-md">
                <div class="flex items-center gap-2">
                    @include('icons.medal')
                    <span class="text-sm text-esg8"> {{ __('Fastest Client XPTO') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-xl font-bold text-esg1"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('days') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Second place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('days') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Third place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('days') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Fourth place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('days') }} </span>
                </div>
            </div>

            <div class="flex justify-between items-center mt-2 p-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-esg8 font-bold"> #2 </span>
                    <span class="text-sm text-esg8"> {{ __('Fifth place client') }} </span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-base font-bold text-esg8"> 123 </span>
                    <span class="text-xs text-esg16"> {{ __('days') }} </span>
                </div>
            </div>
        </div>
    </x-cards.card-dashboard-version1-withshadow>
</div>