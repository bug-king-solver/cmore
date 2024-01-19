<div class="benchmarking">
    @push('body')
        <style nonce="{{ csp_nonce() }}">
            .benchmarking .ts-wrapper.multi .ts-control>div,
            .benchmarking .ts-wrapper.single .ts-control>div {
                background-color: #E1E6EF;
                color: #444444;
            }
        </style>
        <script nonce="{{ csp_nonce() }}">
            let mainChart = null;
            let distributionChart = null;

            function drawMainChart(chart) {
                const data = {
                    labels: chart.labels,
                    datasets: [],
                    options: {
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: 'rgb(255, 99, 132)'
                                },
                            }
                        }
                    }
                };

                data.datasets.push({
                    type: 'line',
                    label: '{{ __('Minimum') }}',
                    backgroundColor: '#FFA476',
                    hoverBackgroundColor: '#FFA476',
                    borderColor: '#FFA476',
                    data: chart.data.min,
                    datalabels: {
                        align: 'left',
                        anchor: 'end',
                        color: '#FFA476',
                    },
                    datasetType: '{{ __('Min') }}',
                    borderDash: [2, 5],
                });

                data.datasets.push({
                    type: 'line',
                    label: '{{ __('Average') }}',
                    backgroundColor: '#C4F7BF',
                    hoverBackgroundColor: '#0D9401',
                    borderColor: '#C4F7BF',
                    data: chart.data.avg,
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: '#0D9401',
                    },
                    datasetType: '{{ __('Avg') }}',
                    borderDash: [2, 5],
                });

                data.datasets.push({
                    type: 'line',
                    label: '{{ __('Maximum') }}',
                    backgroundColor: '#FFA476',
                    hoverBackgroundColor: '#FFA476',
                    borderColor: '#FFA476',
                    data: chart.data.max,
                    datalabels: {
                        align: 'right',
                        anchor: 'end',
                        color: '#FFA476',
                    },
                    datasetType: '{{ __('Max') }}',
                    borderDash: [2, 5],
                });

                // data.datasets.push({
                //     type: 'bar',
                //     label: '{{ __('Median') }}',
                //     backgroundColor: '{{ color('esg7') }}',
                //     hoverBackgroundColor: '{{ color('esg4') }}',
                //     borderColor: '{{ color(7) }}',
                //     data: chart.data.median,
                //     datalabels: {
                //         align: 'right',
                //         anchor: 'end',
                //         backgroundColor: function(context) {
                //             return context.dataset.backgroundColor;
                //         },
                //         borderRadius: 20,
                //         padding: 3,
                //         color: '{{ color(6) }}',
                //         font: {
                //             weight: 'bold'
                //         },
                //     },
                //     datasetType: null
                // });

                // data.datasets.push({
                //     type: 'bar',
                //     label: '{{ __('Standard Deviation') }}',
                //     backgroundColor: '{{ color('esg7') }}',
                //     hoverBackgroundColor: '{{ color('esg4') }}',
                //     borderColor: '{{ color(7) }}',
                //     data: chart.data.daviation,
                //     datalabels: {
                //         align: 'right',
                //         anchor: 'end',
                //         backgroundColor: function(context) {
                //             return context.dataset.backgroundColor;
                //         },
                //         borderRadius: 20,
                //         padding: 3,
                //         color: '{{ color(6) }}',
                //         font: {
                //             weight: 'bold'
                //         },
                //     },
                //     datasetType: 'Standard Deviation'
                // });

                Object.values(chart.data.companies).map((company) => {
                    data.datasets.push({
                        type: 'bar',
                        label: company.name,
                        backgroundColor: company.color,
                        hoverBackgroundColor: company.color,
                        borderColor: '{{ color(8) }}',
                        data: company.data,
                        datalabels: {
                            display: false
                        },
                    });
                });

                const config = {
                    type: 'bar',
                    data: data,
                    plugins: [
                        ChartDataLabels,
                        {
                            beforeInit: function (chart) {
                                const originalFit = chart.legend.fit;
                                chart.legend.fit = function fit() {
                                    originalFit.bind(chart.legend)();
                                    this.height += 24;
                                }
	                        }
                        }
                    ],
                    options: {
                        plugins: {
                            title: {
                                text: 'Histogram',
                                display: true,
                                color: '{{ color(28) }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.semibold,
                                },

                                align: 'start'
                            },
                            legend: {
                                labels: {
                                    color: '{{ color(8) }}',
                                    usePointStyle: true,
                                    borderRadius: 50,
                                },
                            },

                            tooltip: {
                                displayColors: false,
                                backgroundColor: '#FFF',
                                titleColor: '#757575',
                                borderColor: '#D8D8D8',
                                borderWidth: 1,
                                padding: 10,

                                callbacks: {
                                    labelTextColor: function(context) {
                                        return '#757575';
                                    },

                                    afterLabel: function(context) {
                                        return context.dataset.label == 'Minimum' ? 'Standard deviation: ' + chart.data
                                            .daviation : null;
                                        // return 'Standard deviation: ' + chart.data.daviation  ;
                                    },
                                },
                            },
                            datalabels: {
                                offset: 10,
                                display: function(context) {
                                    return (context.dataIndex === context.dataset.data.length - 1);
                                },
                                formatter: function(value, context) {
                                    return context.dataset.datasetType;
                                },

                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                ticks: {
                                    color: '{{ color(8) }}',

                                },
                                grid: {
                                    display: true,
                                    borderColor: '{{ color(4) }}',
                                    borderDash: [5, 5],

                                },

                            },
                            x: {
                                display: true,
                                offset: true,
                                ticks: {
                                    color: '{{ color(8) }}',

                                },
                                grid: {
                                    display: false,
                                    borderColor: '{{ color(7) }}',
                                },
                            },
                        }
                    }
                };

                var ctx = document.getElementById('main_chart');

                if (mainChart !== null) {
                    mainChart.destroy();
                }

                mainChart = new Chart(ctx, config);
            }


            function drawDistributionChart(chart, years) {
                const data = {
                    labels: chart.labels,
                    datasets: [],

                };

                const year = Math.max(...years);

                Object.values(chart.data.companies).map((company) => {
                    data.datasets.push({
                        label: company.name,
                        backgroundColor: company.color,
                        hoverBackgroundColor: company.color,
                        borderColor: company.color,
                        pointRadius: 8,
                        data: company.data,
                    });
                });

                data.datasets.push({
                    label: '{{ __('Others') }}',
                    backgroundColor: '#D8D8D8',
                    hoverBackgroundColor: '#D8D8D8',
                    borderColor: '#D8D8D8',
                    pointRadius: 8,
                    data: chart.data.data,
                });

                const config = {
                    type: 'scatter',
                    data: data,
                    plugins: [{
                        beforeInit: function (chart) {
                            const originalFit = chart.legend.fit;
                            chart.legend.fit = function fit() {
                                originalFit.bind(chart.legend)();
                                this.height += 24;
                            }
                        }
                    }],
                    options: {

                        plugins: {
                            title: {
                                text: '{{ __('Distribution') }}',
                                display: true,
                                color: '{{ color(28) }}',
                                font: {
                                    family: twConfig.theme.fontFamily.encodesans,
                                    size: 18,
                                    weight: twConfig.theme.fontWeight.semibold,

                                },
                                align: 'start'
                            },
                            legend: {
                                labels: {
                                    color: '{{ color(8) }}',
                                    usePointStyle: true,
                                    borderRadius: 50,
                                },

                            },
                            tooltip: {
                                displayColors: false,
                                backgroundColor: '#FFF',
                                titleColor: '#757575',
                                borderColor: '#D8D8D8',
                                borderWidth: 1,
                                padding: 10,
                                callbacks: {
                                    labelTextColor: function(context) {
                                        return '#757575';
                                    },
                                    label: function(context) {
                                        return 'Company ' + context.dataset.label;
                                    },
                                    afterLabel: function(context) {
                                        return new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: 'USD'
                                        }).format(context.parsed.x) + ', ' + context.parsed.y;
                                    },
                                },
                            },

                        },
                        scales: {
                            y: {
                                min: @this.search.y.min,
                                ticks: {
                                    color: '{{ color(8) }}'
                                },
                                grid: {
                                    display: true,
                                    borderColor: '{{ color(4) }}',
                                    borderDash: [5, 5],

                                },
                            },
                            x: {
                                display: true,
                                offset: true,
                                ticks: {
                                    color: '{{ color(8) }}',
                                    maxTicksLimit : chart.data.data.length+1,
                                    callback: (value, index, values) => {
                                        return new Intl.NumberFormat('en-US', {
                                            style: 'currency',
                                            currency: 'USD',
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0,
                                        }).format(value);
                                    }
                                },
                                grid: {
                                    display: false,
                                    borderColor: '{{ color(7) }}'
                                },
                            },
                        }
                    }
                };

                var ctx = document.getElementById('distribution_chart');

                if (distributionChart !== null) {
                    distributionChart.destroy();
                }

                distributionChart = new Chart(ctx, config);
            }
        </script>
        <script nonce="{{ csp_nonce() }}">
            document.addEventListener('DOMContentLoaded', () => {
                drawMainChart(@this.charts.main);
                if (@this.charts.distribution.data) {
                    drawDistributionChart(@this.charts.distribution, @this.search.years);
                }

                Livewire.hook('message.processed', (el, component) => {
                    drawMainChart(@this.charts.main);
                    if (@this.charts.distribution.data) {
                        drawDistributionChart(@this.charts.distribution, @this.search.years);
                    }
                });
            });

            function acceptOnlyNumbers(event, actual, max, isDecimal = false) {
                actual += "";
                if (window.getSelection().toString() != "") {
                    actual = "";
                }
                let newValue = actual + event.key;
                let regex = isDecimal ? /^\d*\.?\d*$/ : /^\d*$/;
                if (!newValue.match(regex) || newValue > max) {
                    event.preventDefault();
                } else if ((actual == "0" || actual == "") && newValue != "0.") {
                    return "";
                }
                return actual;
            }

            function validateValues(actual, max, min) {
                return actual > max ? max : (actual < min ? min : actual);
            }
        </script>
    @endpush

    <x-slot name="header">
        <x-header title="{{ __('Benchmarking') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="grid grid-cols-3">
        <div class="col-span-2">
            <canvas id="main_chart"></canvas>

            <canvas id="distribution_chart" class="mt-20"></canvas>
        </div>

        <div class="pl-10">

            <div class="mb-10">
                <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Indicators') }}</p>
                <x-inputs.tomselect wire:model="search.indicator" wire:change="filter()" :options="$indicatorsList"
                    plugins="['no_backspace_delete']" :items="$search['indicator']" limit="1"
                    placeholder="Select the indicators" />
            </div>

            <div class="mb-10">
                <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Years') }}</p>
                <x-inputs.tomselect wire:model="search.years" wire:change="filter()" :options="$yearsList"
                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']" :items="$search['years']" multiple
                    placeholder="Select the year" />
            </div>

            <div class="mb-10">
                <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Countries') }}</p>
                <x-inputs.tomselect wire:model="search.countries" wire:change="filter()" :options="$countriesList"
                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']" :items="$search['countries']" multiple
                    placeholder="Select the country" />
            </div>

            <div class="mb-10">
                <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Business Sectors') }}</p>
                <x-inputs.tomselect wire:model="search.businessSectors" wire:change="filter()" :options="$businessSectorsList"
                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']" :items="$search['businessSectors']" multiple
                    placeholder="Select the business sectors" />
            </div>

            <div x-data="{ showCompanyParams: false }">
                <button @click="showCompanyParams = !showCompanyParams" class="mb-8 text-lg font-bold text-esg28">
                    {{ __('Company Parameters') }}
                </button>
                <div x-show="!showCompanyParams" class="float-right text-lg my-3">
                    @include('icons.arrow-menu', ['class' => 'text-esg28'])</div>
                <div x-cloak x-show="showCompanyParams" class="float-right text-lg inline my-3">
                    @include('icons.arrow-up', [
                        'class' => 'text-esg28',
                    ])
                </div>

                <div x-show="showCompanyParams">

                    <div class="mb-10">
                        <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Company Type') }}</p>
                        <x-inputs.radio wire:model="search.companyType" wire:change="updateCompaniesList()"
                            name="companyType" id="company_type_all" value="all" label="{{ __('All') }}"
                            class="mr-1" />
                        <x-inputs.radio wire:model="search.companyType" wire:change="updateCompaniesList()"
                            name="companyTtype" id="company_type_customer" value="customer"
                            label="{{ __('Customer') }}" class="ml-3 mr-1" />
                        <x-inputs.radio wire:model="search.companyType" wire:change="updateCompaniesList()"
                            name="companyTtype" id="company_type_supplier" value="supplier"
                            label="{{ __('Supplier') }}" class="ml-3 mr-1" />
                    </div>

                    <div class="mb-10">
                        <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Compare With') }}</p>
                        <x-inputs.radio wire:model="search.compareWith" wire:change="filter()" name="compareWith"
                            id="compare_with_global" value="global" label="{{ __('Global') }}" class="mr-1" />
                        <x-inputs.radio wire:model="search.compareWith" wire:change="filter()" name="compareWith"
                            id="compare_with_own" value="own" label="{{ __('Own Companies') }}" class="ml-3 mr-1" />
                    </div>

                    <div class="mb-10">
                        <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Companies / NIF') }}</p>
                        <x-inputs.tomselect wire:model="search.companies" wire:change="filter()" :options="$companiesList"
                            plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']" :items="$search['companies']"
                            :wire_ignore="false" multiple placeholder="Select the company" />
                    </div>

                    <div class="mb-10">
                        <div class="flex items-center">
                            <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Lines By') }}</p>
                            <x-information id="{{ __('Lines By') }}">If Max, Avg and Min lines on histogram are related to global companies or to companies from a business sector (if previously selected)</x-information>
                        </div>
                        <x-inputs.tomselect wire:model="search.lineBy" wire:change="filter()" :options="$lineBy"
                            plugins="['no_backspace_delete']" :items="$search['lineBy']" limit="1"
                            placeholder="Select the lines" />
                    </div>
                </div>
            </div>

            <div x-data="{ showFilterValues: false }">
                <button @click="showFilterValues = !showFilterValues" class="mb-8 text-lg font-bold text-esg28">
                    {{ __('Filter values') }}
                </button>
                <div x-show="!showFilterValues" class="float-right text-lg my-3">
                    @include('icons.arrow-menu', ['class' => 'text-esg28'])
                </div>
                <div x-cloak x-show="showFilterValues" class="float-right text-lg inline my-3">
                    @include('icons.arrow-up', [
                        'class' => 'text-esg28',
                    ])
                </div>

                <div x-show="showFilterValues">

                    <div class="mb-10" x-data="{ min: @this.revenueMin, max: @this.revenueMax }">
                        <p class="mb-2 text-lg text-esg8"><span
                                class="font-normal">{{ __('Revenue (in millions)') }}</span>
                        </p>
                        <div class="flex flex-row gap-2">
                            <div>
                                <p class="pb-2 text-sm text-esg8/50">{{ __('Minimum:') }} $<span
                                        x-text="@this.revenueMin" class="font-bold"></span></p>
                                <x-inputs.text wire:model.lazy="search.revenue.min" wire:change="filter()"
                                    x-model="min"
                                    @keypress="min = acceptOnlyNumbers($event, min, {{ $revenueMax }})"
                                    x-on:blur="min = validateValues(parseFloat(min), parseFloat(max), parseFloat({{ $revenueMin }}))"
                                    id="search.revenue.min"
                                    class="w-full h-11 bg-esg4/[.14] border !border-esg8/50 !text-esg8 rounded appearance-none cursor-pointer text-sm"
                                    placeholder="Set a minimum value" />
                            </div>

                            <div>
                                <p class="pb-2 text-sm text-esg8/50">{{ __('Maximum: ') }}<span
                                        x-text="@this.revenueMaxStr" class="font-bold"></span></p>
                                <x-inputs.text wire:model.lazy="search.revenue.max" wire:change="filter()"
                                    x-model="max"
                                    @keypress="max = acceptOnlyNumbers($event, max, {{ $revenueMax }})"
                                    x-on:blur="max = validateValues(parseFloat(max), parseFloat({{ $revenueMax }}), parseFloat(min))"
                                    id="search.revenue.max"
                                    class="w-full h-11 bg-esg4/[.14] border !border-esg8/50 !text-esg8 rounded appearance-none cursor-pointer text-sm"
                                    placeholder="Set a maximum value" />
                            </div>
                        </div>
                    </div>


                    <div class="mb-10" x-data="{ min: @this.employeeMin, max: @this.employeeMax }">
                        <p class="mb-2 text-lg text-esg8"><span
                                class="font-normal">{{ __('Employee') }}</span>
                        </p>

                        <div class="flex flex-row gap-2">
                            <div>
                                <p class="pb-2 text-sm text-esg8/50">{{ __('Minimum:') }}<span
                                        x-text="@this.employeeMin" class="font-bold"></span></p>
                                <x-inputs.text wire:model.lazy="search.employee.min" wire:change="filter()"
                                    x-model="min"
                                    @keypress="min = acceptOnlyNumbers($event, min, {{ $employeeMax }})"
                                    x-on:blur="min = validateValues(parseFloat(min), parseFloat(max), parseFloat({{ $employeeMin }}))"
                                    id="search.employee.min"
                                    class="w-full h-11 bg-esg4/[.14] border !border-esg8/50 !text-esg8 rounded appearance-none cursor-pointer text-sm"
                                    placeholder="Set a minimum value" />
                            </div>

                            <div>
                                <p class="pb-2 text-sm text-esg8/50">{{ __('Maximum:') }}<span
                                        x-text="@this.employeeMax" class="font-bold"></span></p>
                                <x-inputs.text wire:model.lazy="search.employee.max" wire:change="filter()"
                                    x-model="max"
                                    @keypress="max = acceptOnlyNumbers($event, max, {{ $employeeMax }})"
                                    x-on:blur="max = validateValues(parseFloat(max), parseFloat({{ $employeeMax }}), parseFloat(min))"
                                    id="search.employee.max"
                                    class="w-full h-11 bg-esg4/[.14] border !border-esg8/50 !text-esg8 rounded appearance-none cursor-pointer text-sm"
                                    placeholder="Set a maximum value" />
                            </div>
                        </div>
                    </div>

                    <div class="mb-10" x-data="{ min: @this.yMin, max: @this.yMax }">
                        <div class="flex items-center">
                            <p class="mb-2 text-lg text-esg8"><span class="font-normal">{{ __('Y axis') }}</span></p>
                            <x-information id="{{ __('Y axis') }}">Range of Y axis values on distribution chart.</x-information>
                        </div>
                        <div class="flex flex-row gap-2">
                            <div>
                                <p class="pb-2 text-sm text-esg8/50">{{ __('Minimum:') }} <span x-text="@this.yMin"
                                        class="font-bold"></span></p>
                                <x-inputs.text wire:model.lazy="search.y.min" wire:change="filter()" x-model="min"
                                    @keypress="min = acceptOnlyNumbers($event, min, {{ $yMax }}, true)"
                                    x-on:blur="min = validateValues(parseFloat(min), parseFloat(max), parseFloat({{ $yMin }}))"
                                    id="search.y.min"
                                    class="w-full h-11 bg-esg4/[.14] border !border-esg8/50 !text-esg8 rounded appearance-none cursor-pointer text-sm"
                                    placeholder="Set a minimum value" />
                            </div>

                            <div>
                                <p class="pb-2 text-sm text-esg8/50">{{ __('Maximum:') }} <span x-text="@this.yMax"
                                        class="font-bold"></span></p>
                                <x-inputs.text wire:model.lazy="search.y.max" wire:change="filter()" x-model="max"
                                    @keypress="max = acceptOnlyNumbers($event, max, {{ $yMax }}, true)"
                                    x-on:blur="max = validateValues(parseFloat(max), parseFloat({{ $yMax }}), parseFloat(min))"
                                    id="search.y.max"
                                    class="w-full h-11 bg-esg4/[.14] border !border-esg8/50 !text-esg8 rounded appearance-none cursor-pointer text-sm"
                                    placeholder="Set a maximum value" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
