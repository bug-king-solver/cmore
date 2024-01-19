@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' => 'bg-esg4'])


@push('body')
    <script nonce="{{ csp_nonce() }}">
        function barCharts(labels, data, id, barColor, view = 'x', type = "single") {
            if (type == 'single') {
                var data = {
                    labels: labels,
                    datasets: [{
                        data: data,
                        lineTension: 0.3,
                        fill: true,
                        backgroundColor: barColor,
                        borderColor: '{{ color(6) }}'
                    }],
                };
            } else {
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
                        right: (view == 'y' ? 60 : 0),
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: (view == 'y' ? 'right' : 'top'),
                        formatter: function(value) {
                            if (value < 1) {
                                value = value.toFixed(2);
                            } else {
                                value = Math.trunc(value);
                            }
                            return formatNumber(value);
                        },
                        backgroundColor: hexToRgbA(barColor),
                        color: barColor,
                        padding: {
                            top: 6,
                            right: 6,
                            left: 6
                        },
                        borderRadius: 3,
                        textStrokeColor: '#F0F0F0',
                        font: {
                            size: '12px',
                            weight: 'Bold'
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
                        offset: view == 'x' ? true : false,
                        ticks: {
                            display: true,
                            color: '{{ color(8) }}'
                        },
                        grid: {
                            display: false,
                            borderColor: '{{ color(8) }}'
                        },
                    }
                }

            };
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: data,
                options: chartOptions,
                plugins: [ChartDataLabels]
            });
        }
    </script>
@endpush

@section('content')
    <div class="px-2 md:px-0">
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
                        {{ __('CO2 Calculator - Results') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-10">
            @include('icons.info', ['color' => color(5)])
            <span class="text-lg text-esg8">{{ __('Methodology') }}</span>
        </div>

        <div class="mt-5">
            <span class="text-base text-esg8">{{ __('The calculation methodology is based on the GHG Protocol') }}</span>
        </div>

        <div class="my-10 border border-esg7/30"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 shadow shadow-esg7/30 rounded-md">
            <div class="w-full h-full">
                @php
                    $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Scope 1')], ['color' => 'bg-[#6AD794]', 'text' => __('Scope 2')], ['color' => 'bg-[#98BDA6]', 'text' => __('Scope 3')]]);
                @endphp
                <x-cards.card-dashboard-version1 text="{{ json_encode([__('GHG emission by category')]) }}"
                    subpoint="{{ $subpoint }}" class="!border-0"
                    titleclass="!uppercase !font-normal !text-esg16 !text-base ">
                    <x-charts.bar id="ghg_emissin" class="m-auto relative !h-full !w-full" x-init="$nextTick(() => {
                        barCharts(
                            ['Scope 1', 'Scope 2', 'Scope 3'],
                            {!! json_encode($totals) !!},
                            'ghg_emissin',
                            ['#008131', '#6AD794', '#98BDA6']
                        );
                    })" />
                    <div class="flex items-end gap-2 text-sm text-esg16 font-normal place-content-center mt-5">
                        <span>{{ __('Total') }}:</span>
                        <span class="text-2xl text-esg8">
                            <x-number :value="$totals->sum()" />
                        </span>
                        <span>{{ __($charts[0]['categories'][0]['unit'] ?? 't CO2 eq') }}</span>
                    </div>
                </x-cards.card-dashboard-version1>
            </div>

            <div class="grid place-content-center w-full">
                <p class="text-center text-xs text-[#008131] font-bold uppercase"> {{ __('Scope 1 Sources') }} </p>
                <div class="mt-4">
                    @foreach ($charts[0]['categories'] as $category)
                        <div class="grid grid-cols-2 gap-4 items-center">
                            <span class="text-xs text-esg16">{{ $category['name'] }}</span>
                            <span class="text-xs text-esg16">
                                <span class="text-[#008131] text-sm font-bold mr-1">
                                    <x-number :value="$category['total']" />
                                </span>
                                {{ __($category['unit'] ?? 't CO2 eq') }}
                            </span>
                        </div>
                    @endforeach

                    {{-- <div class="grid grid-cols-2 gap-4 items-center">
                        <span class="text-xs text-esg16">{{ __('Fluorinated gases') }}</span>
                        <span class="text-xs text-esg16">
                            <span class="text-[#008131] text-sm font-bold mr-1">
                                <x-number :value="$charts[0]['custom']['total']" />
                            </span>
                            {{ __('t CO2 eq') }}
                        </span>
                    </div> --}}


                </div>
            </div>

            <div class="grid place-content-center w-full">
                <p class="text-center text-xs text-[#6AD794] font-bold uppercase"> {{ __('Scope 2 Sources') }} </p>
                <div class="mt-4">
                    @foreach ($charts[1]['categories'] as $category)
                        <div class="grid grid-cols-2 gap-4 items-center">
                            <span class="text-xs text-esg16">{{ $category['name'] }}</span>
                            <span class="text-xs text-esg16">
                                <span class="text-[#008131] text-sm font-bold mr-1">
                                    <x-number :value="$category['total']" />
                                </span>
                                {{ __($category['unit'] ?? 't CO2 eq') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="w-full mt-16">
            <x-cards.card-dashboard-version1 text="{{ json_encode([__('GHG Scope 3 emission by category')]) }}"
                class="!h-full !border-0 shadow shadow-esg7/30 !rounded-md"
                titleclass="!uppercase !font-normal !text-esg16 !text-base "
                contentplacement="none">
                <div class="grid grid-cols-1 md:grid-cols-4">
                    <div class="pt-10 col-span-3 ">
                        @if (!$isEmpty)
                            <x-charts.bar id="ghg_scope_3_emission" class="m-auto relative !w-full"
                                x-init="$nextTick(() => {
                                    barCharts(
                                        {{ json_encode(array_column($categoriesNameScope3, 'name'), true) }},
                                        {{ json_encode(array_column($categoriesNameScope3, 'total'), true) }},
                                        'ghg_scope_3_emission',
                                        ['#98BDA6'],
                                        'y'
                                    );
                                })" />
                        @else
                            <p class="text-center text-lg text-esg16">{{ __('no data available') }}</p>
                        @endif
                    </div>
                    <div class="items-center grid place-content-center">
                        <p class="text-xs font-bold text-[#98BDA6] text-center"> {{ __('Scope 3 Sources') }} </p>
                        <div class="flex items-end gap-2 text-sm text-esg16 font-normal place-content-center mt-3">
                            <span>{{ __('Total') }}:</span>
                            <span class="text-2xl text-esg8">
                                <x-number :value="$charts[2]['total']" />
                            </span>
                            <span>{{ __($category['unit'] ?? 't CO2 eq') }}</span>
                        </div>
                    </div>
                </div>
            </x-cards.card-dashboard-version1>
        </div>
    </div>
@endsection
