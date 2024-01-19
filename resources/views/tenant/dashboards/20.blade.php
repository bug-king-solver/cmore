@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' => 'bg-esg4'])

@php
    $categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
    $genderIconUrl = global_asset('images/icons/genders/{id}.svg');
@endphp
@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            actionPlan();
        });

        // action plan
        function actionPlan() {
            var color_code = twConfig.theme.colors.esg7;
            var color_tick = "#C4C4C4";
            var options = {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'center',
                        align: 'center',
                        color: '#FFF',
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(context) {
                            return context.labelShort;
                        },
                        offset: 2,
                        padding: 0
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem['raw']['labelFull'] + ' : ( ' + tooltipItem.parsed.x + ', ' +
                                    tooltipItem.parsed.y + ' )';
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        min: 0,
                        max: 6,
                        display: true,
                        'type': "linear",
                        title: {
                            display: true,
                            text: "{{ __('IMPACT MATERIIALITY') }}",
                            color: twConfig.theme.colors.esg8,
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: '18px',
                                lineHeight: 1.2,
                            },
                            padding: {
                                top: 20,
                                left: 20,
                                right: 00,
                                bottom: 0
                            }
                        },
                        grid: {
                            borderColor: color_tick,
                            borderWidth: 2,
                            font: {
                                weight: 'bold'
                            },
                            color: color_tick,
                            borderDash: [4, 1],
                        },
                        ticks: {
                            color: ['#66B44E', '#C2D234', '#FDC729', '#EA6F22', '#D52029'],
                            display: true,

                        }
                    },
                    y: {
                        min: 0,
                        max: 6,
                        display: true,
                        title: {
                            display: true,
                            text: "{{ __('FINANCIAL MATERIALITY') }}",
                            color: twConfig.theme.colors.esg8,
                            font: {
                                family: twConfig.theme.fontFamily.encodesans,
                                size: '18px',
                            },
                            padding: {
                                top: 0,
                                left: 0,
                                right: 0,
                                bottom: 20
                            }
                        },
                        grid: {
                            borderColor: color_tick,
                            borderWidth: 2,
                            font: {
                                weight: 'bold'
                            },
                            color: color_tick,
                            borderDash: [4, 1],
                        },
                        ticks: {
                            display: true,
                            color: ['#66B44E', '#C2D234', '#FDC729', '#EA6F22', '#D52029'],
                        }
                    }
                },
            };

            var actionPlanChart = new Chart(document.getElementById("actions_plan"), {
                type: 'scatter',
                data: {
                    datasets: {!! json_encode($charts) !!}
                },
                options,
                plugins: [ChartDataLabels]
            });
        }
    </script>
@endpush

@section('content')
    <div class="px-4 lg:px-0">

        <div class="mt-10">
            <div class="w-full flex justify-between">
                <div class="">
                    <a href="{{ route('tenant.questionnaires.panel') }}"
                        class="text-esg5 w-fit text-2xl font-bold flex flex-row gap-2 items-center">
                        @include('icons.back', [
                            'color' => color(5),
                            'width' => '20',
                            'height' => '16',
                        ])
                        {{ __(' Dashboard') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="">
            <div class="mt-10">
                @php
                    foreach ($charts as $chart) {
                        $subpoint[] = ['color' => $chart['color'], 'text' => $chart['label']];
                    }
                    $subpoint = json_encode($subpoint);
                @endphp
                <x-cards.card-dashboard-version1-withshadow text="{{ json_encode([__('double materiality Matrix')]) }}"
                    subpoint="{{ $subpoint }}" class="!h-auto">

                    <div class="grid grid-cols-1 lg:grid-cols-2 mt-10 gap-5">

                        <div x-data="{ showExtraLegend: false }" class="md:col-span-1 lg:p-5 xl:p-10 ">
                            <div class="relative w-full">
                                <div class="h-[350px] w-[350px] sm:!h-[500px] sm:!w-[500px]">
                                    <div></div>
                                    <canvas id="actions_plan" class="m-auto relative !h-full !w-full"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="pt-24">
                            @foreach ($charts as $chart)
                                @foreach ($chart['data'] as $subcategory)
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 {{ $chart['color'] }} rounded-full"></div>
                                        <div class="text-xs text-esg8">
                                            {{ $subcategory['fullName'] }}
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="" x-data="{ open: true }">

                            <div class="flex items-center gap-5">
                                <span>@include('icons.info', [
                                    'width' => 14,
                                    'height' => 14,
                                    'color' => color(5),
                                ])</span>
                                <span class="text-lg text-esg8 uppercase">{{ __('Scores’ details') }}</span>

                                <span class="text-sm text-esg16 underline cursor-pointer" x-show="open"
                                    x-on:click="open = ! open">{{ __('Hide') }}</span>
                                <span class="text-sm text-esg16 underline cursor-pointer" x-show="!open"
                                    x-on:click="open = ! open">{{ __('Show') }}</span>
                            </div>

                            <div x-show="open">
                                <div class="mt-3">
                                    <p class="text-sm font-bold text-esg8 underline underline-offset-4 uppercase">
                                        {{ __('Impact materiality:') }} <span class="font-normal normal-case">
                                            {{ __('impact on the environment and people') }} </span></p>
                                </div>

                                <div class="mt-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-5 h-5 bg-[#66B44E] rounded-md grid place-content-center text-esg4 text-xs">
                                            1</div>
                                        <div class="text-sm text-esg8">{{ __('Minimal') }}</div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#C2D234] rounded-md grid place-content-center text-esg4 text-xs">
                                            2</div>
                                        <div class="text-sm text-esg8">{{ __('Informative') }}</div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#FDC729] rounded-md grid place-content-center text-esg4 text-xs">
                                            3</div>
                                        <div class="text-sm text-esg8">{{ __('Important') }}</div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#EA6F22] rounded-md grid place-content-center text-esg4 text-xs">
                                            4</div>
                                        <div class="text-sm text-esg8">{{ __('Significant') }}</div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#D52029] rounded-md grid place-content-center text-esg4 text-xs">
                                            5</div>
                                        <div class="text-sm text-esg8">{{ __('Critical') }}</div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <p class="text-sm font-bold text-esg8 underline underline-offset-4 uppercase">
                                        {{ __('Financial materiality:') }} <span class="font-normal normal-case">
                                            {{ __('impact on business, operations or financial performance of the organization') }}
                                        </span></p>
                                </div>

                                <div class="mt-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-5 h-5 bg-[#66B44E] rounded-md grid place-content-center text-esg4 text-xs">
                                            1</div>
                                        <div class="text-sm text-esg8">
                                            {{ __('Without identified consequence in the short, medium and long term') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#C2D234] rounded-md grid place-content-center text-esg4 text-xs">
                                            2</div>
                                        <div class="text-sm text-esg8">
                                            {{ __('Possible in the short, medium and long term') }}</div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#FDC729] rounded-md grid place-content-center text-esg4 text-xs">
                                            3</div>
                                        <div class="text-sm text-esg8">
                                            {{ __('Possible in the short term, costly in the medium term, very costly in the long term') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#EA6F22] rounded-md grid place-content-center text-esg4 text-xs">
                                            4</div>
                                        <div class="text-sm text-esg8">
                                            {{ __('Possible but costly in the short term, very costly or lacking in the medium term, impossible in the long term') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-5 h-5 bg-[#D52029] rounded-md grid place-content-center text-esg4 text-xs">
                                            5</div>
                                        <div class="text-sm text-esg8">
                                            {{ __('Impossible, very costly or not feasible in the short term') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-cards.card-dashboard-version1-withshadow>
            </div>
        </div>
    </div>
@endsection
