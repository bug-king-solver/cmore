<div>
    <x-slot name="header">
        <x-header dataTest="data-header">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <x-menus.panel :buttons="[
        [
            'route' => 'tenant.data.panel',
            'label' => __('Panel'),
            'icon' => 'panel',
        ],
        [
            'route' => 'tenant.data.index',
            'label' => __('Indicators'),
            'icon' => 'building-v2',
        ],
    ]" activated='tenant.data.panel' />

    <div class="clearfix"></div>

    <div class="grid grid-cols-2 gap-4 mt-6">

        <div class="col-span-1 border px-4 py-6 rounded">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{!! __('Total') !!}</p>
            </div>

            <div class="flex flex-col justify-center items-center self-stretch pt-10 ">

                <div class="flex justify-center">
                    <span style="color: #444;" class="text-center  text-esg16 text-6xl not-italic font-medium co">
                        {{ $totalIndicators }}
                    </span>
                </div>
                <div class="flex justify-center pt-2">
                    <span class="text-center text-lg text-esg16 not-italic font-medium co">
                        {{ __('indicators') }}
                    </span>
                </div>
            </div>

        </div>

        <div class="col-span-1 border px-4 py-6 rounded">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{!! __('Indicators by category') !!}</p>
            </div>
            <div class="flex flex-col justify-center items-center self-stretch pt-6 ">
                <div class="flex justify-center">
                    <div>
                        <x-charts.chartjs id="data_doughnut_chart7" class="" width="350" height="150"
                            x-init="$nextTick(() => {
                                tenantDoughnutChart(
                                    {{ json_encode(array_keys($categoriesIndicatorsCount), true) }},
                                    {{ json_encode(array_values($categoriesIndicatorsCount), true) }},
                                    'data_doughnut_chart7',
                                    ['#99CA3C', '#FBB040', '#06A5B4'], { percentagem: true }
                                );
                            });" />

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-3 gap-4 mt-6">
        <div>
            <p class="mb-2 text-lg text-esg8 font-normal">{{ __('Companies / NIF') }}</p>
            <x-inputs.select-company :extra="['options' => $companiesList]" wire:change="filter()" wire:model="search"
                placeholder="{!! __('Select a company') !!}" />
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-6">

        <div class="col-span-1 border px-4 py-6 rounded">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{!! __('Total') !!}</p>
            </div>
            <div class="flex flex-col justify-center items-center self-stretch pt-6  ">
                @if (empty($totalByCompany) && empty($companyId))
                    <div class="pt-16 pb-16">
                        {!! __('Select the company above') !!}
                    </div>
                @elseif (empty($totalByCompany) && !empty($companyId))
                    <div class="pt-16 pb-16 text-center">
                        {!! __('The selected company does not have this information') !!}
                    </div>
                @else
                    <div class="flex justify-center">
                        <span style="color: #444;" class="text-center  text-esg16 text-6xl not-italic font-medium co">
                            {{ $totalByCompany }}
                        </span>
                    </div>
                    <div class="flex justify-center pt-2">
                        <span class="text-center text-lg text-esg16 not-italic font-medium co">
                            {{ __('indicators') }}
                        </span>
                    </div>
                @endif
            </div>

        </div>
        <div class="col-span-1 border px-4 py-6 rounded">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{!! __('Origin of last update') !!}</p>
            </div>
            <div class="flex flex-col justify-center items-center self-stretch pt-6 ">
                @if (empty(array_filter(array_column($types, 'total'), function($value) { return $value !== 0; })) && empty($companyId))
                    <div class="pt-16 pb-16">
                        {!! __('Select the company above') !!}
                    </div>
                 @elseif (empty(array_filter(array_column($types, 'total'), function($value) { return $value !== 0; })) && !empty($companyId))
                    <div class="pt-16 pb-16 text-center">
                        {!! __('The selected company does not have this information') !!}
                    </div>
                @else
                    <div>
                        <x-charts.chartjs id="data_doughnut_chart2" class="" width="350" height="150"
                            x-init="$nextTick(() => {
                                tenantDoughnutChart(
                                    {{ json_encode(array_column($types, 'type'), true) }},
                                    {{ json_encode(array_column($types, 'total'), true) }},
                                    'data_doughnut_chart2',
                                    ['#E86321', '#153A5B'], { percentagem: true }
                                );
                            });" />

                    </div>
                @endif
            </div>

        </div>

        <div class="col-span-1 border px-4 py-6 rounded">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{!! __('Report frequency') !!}</p>
            </div>
            <div class="flex flex-col justify-center items-center self-stretch pt-6 ">
                <div class="flex justify-center">
                    @if (empty($frequency) && empty($companyId))
                        <div class="pt-16 pb-16">
                            {!! __('Select the company above') !!}
                        </div>
                    @elseif (empty($frequency) && !empty($companyId))
                        <div class="pt-16 pb-16 text-center">
                            {!! __('The selected company does not have this information') !!}
                        </div>
                    @else
                        <div>
                            <x-charts.chartjs id="data_doughnut_chart3" class="" width="350" height="150"
                                x-init="$nextTick(() => {
                                    tenantDoughnutChart(
                                        {{ json_encode(array_column($frequency, 'frequency'), true) }},
                                        {{ json_encode(array_column($frequency, 'qty'), true) }},
                                        'data_doughnut_chart3',
                                        ['#E86321', '#153A5B', '21A6E8'], { percentagem: true }
                                    );
                                });" />

                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <div class="grid grid-cols-3 gap-4 mt-6">

        <div class="col-span-1 border px-4 py-6 rounded">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{!! __('Pending report by category') !!}</p>
            </div>
            <div class="flex flex-col justify-center items-center self-stretch pt-6 ">
                <div class="flex justify-center">
                    @if (empty($indicadorsNeedReport) && empty($companyId))
                        <div class="pt-16 pb-16">
                            {!! __('Select the company above') !!}
                        </div>
                    @elseif (empty($indicadorsNeedReport) && !empty($companyId))
                    <div class="pt-16 pb-16 text-center">
                        {!! __('The selected company does not have this information') !!}
                    </div>
                    @else
                        <div>
                            <x-charts.chartjs id="data_doughnut_chart5" class="" width="350" height="150"
                                x-init="$nextTick(() => {
                                    tenantDoughnutChart(
                                        {{ json_encode(array_column($indicadorsNeedReport, 'category_name'), true) }},
                                        {{ json_encode(array_column($indicadorsNeedReport, 'qtd'), true) }},
                                        'data_doughnut_chart5',
                                        ['#008131'], { percentagem: true }
                                    );
                                });" />

                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-span-1 border px-4 py-6 rounded">
            <div class="flex justify-between">
                <p class="text-lg text-esg16">{!! __('Approaching report deadlines') !!}</p>
            </div>
            <div class="flex flex-col justify-center items-center self-stretch pt-6">
                <div class="flex justify-center">
                    @if (empty($indicadorsNeedReport) && empty($companyId))
                        <div class="pt-16 pb-16">
                            {!! __('Select the company above') !!}
                        </div>
                    @elseif (empty($indicadorsNeedReport) && !empty($companyId))
                    <div class="pt-16 pb-16 text-center">
                        {!! __('The selected company does not have this information') !!}
                    </div>
                    @else
                        <div class="h-48 overflow-y-scroll ">
                            <x-tables.table class="min-w-full">
                                @foreach ($indicatorsDeadline as $indicator)
                                    <x-tables.tr>
                                        <x-tables.td class="text-xs">
                                            {{ $indicator['name'] }}
                                        </x-tables.td>
                                        <x-tables.td>

                                            <div class="flex text-right gap-1  w-28">
                                                @include('icons.calendar', [
                                                    'width' => 20,
                                                    'height' => 20,
                                                    'color' => color(6),
                                                ]){{ $indicator['deadline'] }}</div>
                                        </x-tables.td>

                                    </x-tables.tr>
                                @endforeach
                            </x-tables.table>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
</div>
