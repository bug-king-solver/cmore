@extends(customInclude('layouts.tenant'), ['title' => __('Dashboard'), 'mainBgColor' => 'bg-esg4'])

@php
    $categoryIconUrl = global_asset('images/icons/categories/{id}.svg');
    $genderIconUrl = global_asset('images/icons/genders/{id}.svg');
@endphp
@push('body')
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            // ghg_emissin
            barCharts(
                {!! json_encode(['Scope 1', 'Scope 2', 'Scope 3']) !!},
                {!! json_encode([520, 300, 1520]) !!},
                'ghg_emissin',
                ["#008131", "#6AD794", "#98BDA6"]
            );

            barCharts(
                {!! json_encode([
                    __('Purchased goods and services'),
                    __('Capital goods'),
                    __('Fuel- and energy-related activities'),
                    __('Upstream transportation and distribution'),
                    __('Waste generated in operations'),
                    __('Business travel'),
                    __('Employee commuting'),
                    __('Upstream leased assets'),
                    __('Downstream transportation and distribution'),
                    __('Processing of sold products'),
                    __('Use of sold products'),
                    __('End-of-life treatment of sold products'),
                    __('Downstream leased assets'),
                    __('Franchises'),
                    __('Investments'),
                ]) !!},
                {!! json_encode([80, 0, 0, 370, 222, 8, 68, 0, 35, 235, 0, 272, 0, 130, 70]) !!},
                'emissin_3',
                ["#98BDA6"],
                'y',
            );
        });

        // Common function for bar charts
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
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: (view == 'y' ? 'center' : 'top'),
                        formatter: function(value) {
                            return value;
                        },
                        backgroundColor: [
                            barColor[0]
                        ],
                        color: '#F0F0F0',
                        opacity: 0.8,
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
                        ticks: {
                        reverse: false,
                            stepSize: 200
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

            new Chart(document.getElementById(id).getContext("2d"), {
                type: 'bar',
                data: data,
                options: chartOptions,
                plugins: [ChartDataLabels]
            });
        }
    </script>
@endpush

@section('content')
    <div class="px-4 md:px-0">
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

        <div class="grid grid-cols-1 md:grid-cols-7 gap-8 shadow shadow-esg7/30 rounded-md">
            <div class="col-span-3">
                @php
                    $subpoint = json_encode([['color' => 'bg-[#008131]', 'text' => __('Scope 1')], ['color' => 'bg-[#6AD794]', 'text' => __('Scope 2')], ['color' => 'bg-[#98BDA6]', 'text' => __('Scope 3')]]);
                @endphp
                <x-cards.card-dashboard-version1 text="{{ json_encode([__('GHG emission by category')]) }}"
                    subpoint="{{ $subpoint }}" class="!h-min !border-0"
                    titleclass="!uppercase !font-normal !text-esg16 !text-base ">
                    <div class="pt-10">
                        <x-charts.bar id="ghg_emissin" class="m-auto relative !h-full !w-full" />
                    </div>
                    <div class="flex items-end gap-2 text-sm text-esg16 font-normal place-content-center mt-5">
                        <span>{{ __('Total') }}:</span>
                        <span class="text-2xl text-esg8 font-medium">2 340</span>
                        <span>{{ __('t CO2 eq') }}</span>
                    </div>
                </x-cards.card-dashboard-version1>
            </div>

            <div class="grid w-full p-4 col-span-2">
                <p class="text-center text-xs text-[#008131] font-bold uppercase"> {{ __('Scope 1 Sources') }} </p>
                <div class="mt-4">
                    <div class="flex justify-between  items-center">
                        <span class="text-xs text-esg16 w-8/12">{{ __('Natural Gas') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#008131] text-sm font-bold mr-1">14</span>{{ __('t CO2 eq') }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-esg16 w-8/12">{{ __('Aviation fuel (jet fuel)') }}</span>
                        <span class="text-xs text-esg16 text-right"><span
                                class="text-[#008131] text-sm font-bold mr-1">100</span>{{ __('t CO2 eq') }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-esg16 w-8/12">{{ __('Diesel (average biofuel blend)') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#008131] text-sm font-bold mr-1">350</span>{{ __('t CO2 eq') }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-esg16 w-8/12">{{ __('Petrol (average biofuel blend)') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#008131] text-sm font-bold mr-1">30</span>{{ __('t CO2 eq') }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-esg16 w-8/12">{{ __('Petrol (100% mineral petrol)') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#008131] text-sm font-bold mr-1">5 </span>{{ __('t CO2 eq') }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-esg16 w-8/12">{{ __('Electric vehicle (charged outside the organisation)') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#008131] text-sm font-bold mr-1">1 </span>{{ __('t CO2 eq') }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-esg16 w-8/12">{{ __('Fluorinated gases') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#008131] text-sm font-bold mr-1">20 </span>{{ __('t CO2 eq') }}</span>
                    </div>
                </div>
            </div>

            <div class="grid w-full col-span-2 p-8">
                <p class="text-center text-xs text-[#6AD794] font-bold uppercase"> {{ __('Scope 2 Sources') }} </p>
                <div class="mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-esg16">{{ __('Eletricity') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#6AD794] text-sm font-bold mr-1">160 t</span>{{ __('CO2 eq') }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-esg16">{{ __('Steam') }}</span>
                        <span class="text-xs text-esg16"><span
                                class="text-[#6AD794] text-sm font-bold mr-1">140 t</span>{{ __('CO2 eq') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full mt-16">
            <x-cards.card-dashboard-version1 text="{{ json_encode([__('GHG Scope 3 emission by category')]) }}"
                class="!h-full !border-0 shadow shadow-esg7/30 !rounded-md"
                titleclass="!uppercase !font-normal !text-esg16 !text-base ">
                <div class="grid grid-cols-1 md:grid-cols-4">
                    <div class="pt-10 col-span-3">
                        <x-charts.bar id="emissin_3" class="m-auto relative !h-full !w-full" />
                    </div>
                    <div class="items-center grid place-content-center">
                        <p class="text-xs font-bold text-[#98BDA6] text-center"> {{ __('Scope 3 Sources') }} </p>
                        <div class="flex items-end gap-2 text-sm text-esg16 font-normal place-content-center mt-3">
                            <span>{{ __('Total') }}:</span>
                            <span class="text-2xl text-esg8 font-medium">800</span>
                            <span>{{ __('t CO2 eq') }}</span>
                        </div>
                    </div>
                </div>
            </x-cards.card-dashboard-version1>
        </div>
    </div>
@endsection
