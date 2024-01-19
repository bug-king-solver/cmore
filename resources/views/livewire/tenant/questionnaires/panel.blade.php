<div>
    <x-slot name="header">
        <x-header title="{{ __('Questionnaires') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('questionnaires.create')
                    <div class="flex place-content-end">
                        <x-buttons.a-icon href="{{ route('tenant.questionnaires.form') }}" data-test="add-questionnaires-btn"
                            class="cursor-pointer">
                            <div class="flex gap-2 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                                @include('icons.add', ['width' => 12, 'height' => 12, 'color' => color(4)])
                                <label class="uppercase font-medium text-sm cursor-pointer">{{ __('Add') }}</label>
                            </div>
                        </x-buttons.a-icon>
                    </div>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>

    <x-menus.panel :buttons="[
        [
            'route' => 'tenant.questionnaires.panel',
            'label' => __('PANEL'),
            'icon' => 'panel',
        ],
        [
            'route' => 'tenant.questionnaires.index',
            'label' => __('Ongoing'),
            'params' => [
                's[questionnaire_status][0]' => 'ongoing',
            ],
            'reference' => 'ongoing',
            'icon' => 'performance',
        ],
        [
            'route' => 'tenant.questionnaires.index',
            'label' => __('Submitted'),
            'params' => [
                's[questionnaire_status][0]' => 'submitted',
            ],
            'reference' => 'submitted',
            'icon' => 'checkbox',
        ],
    ]" activated='tenant.questionnaires.panel' />

    <div class="clearfix"></div>

    <div class="grid grid-cols-3 gap-4 mt-6">

        <x-cards.card class="flex-col !p-3">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{{ __('Created') }}</p>
            </div>

            <div class="flex justify-between">
                <div class="flex-grow w-2/5">
                    <p class="mt-2 text-4xl text-esg8 font-medium pt-10 ">{{ $created_stats['count'] }}</p>
                    <p class="text-sm mt-2 text-esg35 font-normal">
                        @php
                            $difference = $created_stats['difference'];
                            if ($difference == 0) {
                                $class = 'text-blue-500';
                            } elseif ($difference < 0) {
                                $class = 'text-red-500';
                            } else {
                                $class = 'text-green-500';
                            }
                        @endphp
                        <span class="{{ $class }}">{{ showGrowth($difference) }}%</span>
                        {{ __('from last month') }}
                    </p>
                </div>
                <div class="w-3/5 pt-2">
                    <canvas id="myChart_created"></canvas>
                </div>
            </div>
        </x-cards.card>

        <x-cards.card class="flex-col !p-3">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{{ __('Ongoing') }}</p>
                <a href="{{ route('tenant.questionnaires.index', ['s[questionnaire_status][0]' => 'ongoing']) }}">
                    <span class="float-right mr-3"> @include('icons.arrow-right') </span>
                </a>
            </div>

            <div class="flex justify-between">
                <div class="flex-grow w-2/5">
                    <p class="mt-2 text-4xl text-esg8 font-medium pt-10 ">{{ $ongoing['count'] }}</p>
                    <p class="text-sm mt-2 text-esg35 font-normal">
                        @php
                            $difference = $ongoing['difference'];
                            if ($difference == 0) {
                                $class = 'text-blue-500';
                            } elseif ($difference < 0) {
                                $class = 'text-red-500';
                            } else {
                                $class = 'text-green-500';
                            }
                        @endphp
                        <span class="{{ $class }}">{{ showGrowth($difference) }}%</span>
                        {{ __('from last month') }}
                    </p>
                </div>
                <div class="w-3/5 pt-2">
                    <canvas id="myChart_ongoing"></canvas>
                </div>
            </div>
        </x-cards.card>

        <x-cards.card class="flex-col !p-3">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{{ __('Submitted') }}</p>
                <a href="{{ route('tenant.questionnaires.index', ['s[questionnaire_status][0]' => 'submitted']) }}">
                    <span class="float-right mr-3"> @include('icons.arrow-right') </span>
                </a>
            </div>

            <div class="flex justify-between">
                <div class="flex-grow w-2/5">
                    <p class="mt-2 text-4xl text-esg8 font-medium pt-10 ">{{ $submitted['count'] }}</p>
                    <p class="text-sm mt-2 text-esg35 font-normal">
                        @php
                            $difference = $submitted['difference'];
                            if ($difference == 0) {
                                $class = 'text-blue-500';
                            } elseif ($difference < 0) {
                                $class = 'text-red-500';
                            } else {
                                $class = 'text-green-500';
                            }
                        @endphp
                        <span class="{{ $class }}">{{ showGrowth($difference) }}%</span>
                        {{ __('from last month') }}
                    </p>
                </div>
                <div class="w-3/5 pt-2">
                    <canvas id="myChart_submitted"></canvas>
                </div>
            </div>
        </x-cards.card>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-6">

        <x-cards.card class="flex-col !p-3">

            <h2 class="text-lg text-esg8 font-medium mb-3">{{ __('Distribution by top 5 sectors') }}</h2>

            @foreach ($business_sectors_questionnaires['business_sectors'] as $business_sectors_questionnaire)
                <div class="flex justify-between">
                    <p class="text-sm text-esg8 p-1">{{ $business_sectors_questionnaire->name }}</p>

                    <p class="text-1xl p-1 flex gap-1"><span
                            class="font-bold text-esg5">{{ round(($business_sectors_questionnaire->questionnaires_count / $business_sectors_questionnaires['business_sectors_questionnaires_count']) * 100) }}%</span>
                        <span class="text-esg8">({{ $business_sectors_questionnaire->questionnaires_count }})</span>
                    </p>
                </div>
            @endforeach
        </x-cards.card>


        <x-cards.card class="flex-col !p-3">

            <h2 class="text-lg text-esg8 font-medium mb-3 pl-3">{{ __('Distribution by progress') }}</h2>

            <div class="flex flex-col p-3">
                @php
                    $percentage_completed_0_50 = $ongoing['count'] !== 0 ? round(($ongoing['on_going_0_50_progress'] / $ongoing['count']) * 100) : 0;
                    $percentage_completed_51_100 = $ongoing['count'] !== 0 ? round(($ongoing['on_going_51_100_progress'] / $ongoing['count']) * 100) : 0;
                @endphp
                <div class="p-1">
                    <div class="flex justify-between mb-1 text-base font-medium dark:text-white">
                        <div class="text-esg8 text-left">0-50% {{ __('completed') }}</div>
                        <div class="text-right "><span
                                class="text-esg5 font-bold">{{ $ongoing['on_going_0_50_progress'] }} </span>/
                            {{ $ongoing['count'] }}
                        </div>
                    </div>

                    <div class="w-full h-4 mb-4 bg-gray-200  dark:bg-gray-700">
                        <div class="h-4  dark:bg-orange-400 bg-esg5 w-[{{ $percentage_completed_0_50 }}%]"></div>
                    </div>
                </div>

                <div class="p-1">
                    <div class="flex justify-between mb-1 text-base font-medium dark:text-white">
                        <div class="text-esg8 text-left">51-100% {{ __('completed') }}</div>
                        <div class="text-right"><span
                                class="text-esg6 font-bold">{{ $ongoing['on_going_51_100_progress'] }} </span>/
                            {{ $ongoing['count'] }}</div>
                    </div>
                    <div class="w-full h-4 mb-4 bg-gray-200  dark:bg-gray-700">
                        <div class="h-4 bg-esg6  dark:bg-gray-500 w-[{{ $percentage_completed_51_100 }}%]">
                        </div>
                    </div>
                </div>
            </div>


        </x-cards.card>

        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg8 font-medium mb-3 pl-2">{{ __('Average time to complete') }}</h2>
            <div class="flex-grow max-h-[200px]">
                <x-charts.chartjs id="dognut_avg_time_submitted" x-init="$nextTick(() => {
                    tenantDoughnutChart(
                        {{ json_encode($chartDataAvgTime['labels'], true) }},
                        {{ json_encode($chartDataAvgTime['datasets'][0]['data'] ?? [], true) }},
                        'dognut_avg_time_submitted',
                        {{ json_encode($chartDataAvgTime['datasets'][0]['backgroundColor'] ?? [], true) }}, {
                            legend: {
                                position: 'right',
                            },
                        }
                    );
                });" />
            </div>
        </x-cards.card>
    </div>
</div>

@push('child-scripts')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {

            var ctx3 = document.getElementById('myChart_ongoing').getContext('2d');
            var chart3 = new Chart(ctx3, {
                type: 'line',
                data: @json($onGoingChart),
                options: {
                    responsive: true,
                    layout: {
                        padding: {
                            top: 20
                        },
                    },
                    borderWidth: 1,
                    plugins: {

                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: ''
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y + '';
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                borderWidth: 0,

                            },
                            ticks: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false,
                                borderWidth: 0,
                            },
                            ticks: {
                                display: false
                            }
                        }
                    },
                    cubicInterpolationMode: 'monotone',
                    elements: {
                        point: {
                            radius: 0,
                            pointStyle: false,
                        },

                    }
                }
            });

            var ctx4 = document.getElementById('myChart_submitted').getContext('2d');
            var chart4 = new Chart(ctx4, {
                type: 'line',
                data: @json($onSubmittedChart),


                options: {
                    responsive: true,
                    layout: {
                        padding: {
                            top: 20
                        },
                    },
                    borderWidth: 1,
                    plugins: {

                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: ''
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y + '';
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                borderWidth: 0,

                            },
                            ticks: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false,
                                borderWidth: 0,
                            },
                            ticks: {
                                display: false
                            }
                        }
                    },
                    cubicInterpolationMode: 'monotone',
                    elements: {
                        point: {
                            radius: 0,
                            pointStyle: false,
                        },

                    }
                }
            });

            var ctx5 = document.getElementById('myChart_created').getContext('2d');
            var chart4 = new Chart(ctx5, {
                type: 'line',
                data: @json($onCreatedChart),


                options: {
                    responsive: true,
                    layout: {
                        padding: {
                            top: 20
                        },
                    },
                    borderWidth: 1,
                    plugins: {

                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: ''
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y + '';
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                borderWidth: 0,

                            },
                            ticks: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false,
                                borderWidth: 0,
                            },
                            ticks: {
                                display: false
                            }
                        }
                    },
                    cubicInterpolationMode: 'monotone',
                    elements: {
                        point: {
                            radius: 0,
                            pointStyle: false,
                        },

                    }
                }
            });
        });
    </script>
@endpush
