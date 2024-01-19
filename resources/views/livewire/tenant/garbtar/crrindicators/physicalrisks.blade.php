<div>
    <x-slot name="header">
        <x-header title="{{ __('ESG/CRR Indicators') }}" dataTest="data-header" class="!bg-esg4" textcolor="text-esg5"
            iconcolor="{{ color(5) }}" click="#">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                <x-buttons.a-icon modal="gar-btar-assets.upload-document" :data="''" data-test="add-data-btn"
                    class="flex place-content-end uppercase">
                    <div
                        class="flex gap-1 items-center bordered border-esg4 py-2 px-4 cursor-pointer rounded-md text-esg4">
                        @include('icons.edit', ['color' => color(4)])
                        <label>{{ __('Edit') }}</label>
                    </div>
                </x-buttons.a-icon>
            </x-slot>
        </x-header>
    </x-slot>

    @php
        $buttons = [
            [
                'route' => 'tenant.garbtar.crr.panel',
                'label' => __('CO2 Emissions'),
                'icon' => 'emissoes-gee',
            ],
            [
                'route' => 'tenant.garbtar.crr.metrics',
                'label' => __('Alignment Metrics'),
                'icon' => 'metrics',
            ],
            [
                'route' => 'tenant.garbtar.crr.physical_risks',
                'label' => __('Physical Risks'),
                'icon' => 'alert',
            ],
        ];
    @endphp

    <x-menus.panel :buttons="$buttons" activated='tenant.garbtar.crr.physical_risks' />

    @php
        $colors = [['hex' => color(5), 'color' => 'esg5'], ['hex' => hex2rgba(color(5), 0.6), 'color' => 'esg5/60']];
    @endphp

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg5 font-bold pb-6 align-self-start uppercase">{{ __('Physical Risks') }}</h2>
            <div class="flex justify-evenly flex-row items-center mb-6">
                <x-charts.chartjs id="physical_risks" class="max-w-[150px] max-h-[300px]" x-init="$nextTick(() => {
                    tenantDoughnutChart(
                        {{ json_encode([__('Chronic'), __('Acute')], true) }},
                        {{ json_encode([513.1, 751.03], true) }},
                        'physical_risks',
                        {{ json_encode(array_column($colors, 'hex'), true) }}, {
                            legend: {
                                display: false,
                            },
                        }
                    );
                });" />
                <div class="w-[400px] h-full flex items-center">
                    <table class="table-fixed w-full">
                        <thead class="h-9 py-2 border-b border-esg5">
                            <th class="w-5 text-esg8 text-sm text-normal text-left"></th>
                            <th class="w-60 text-esg8 text-sm font-normal text-left uppercase"></th>
                            <th class="text-esg8 text-sm font-normal text-left uppercase">{{ __('Value') }}</th>
                        </thead>
                        <tbody>
                            @php
                                $data = [
                                    [
                                        'name' => __('Chronic'),
                                        'total' => 751.03,
                                        'color' => 'orange-500',
                                    ],
                                    [
                                        'name' => __('Acute'),
                                        'total' => 513.1,
                                        'color' => 'orange-600',
                                    ],
                                ];
                                $total = 2064.13;
                            @endphp
                            @foreach ($data as $index => $row)
                                <tr class="h-10 py-2 border-b border-esg10 text-left last:border-b-0">
                                    <td class="p-2 w-5">
                                        <div class="w-2.5 h-2.5 rounded-[100px] bg-{{ $colors[$index]['color'] }}">
                                        </div>
                                    </td>
                                    <td class="p-2 w-60 grow shrink basis-0 text-esg8 text-sm font-normal">
                                        {{ $row['name'] }}</td>
                                    <td class="p-2 text-right">
                                        <span class="text-xl font-bold text-{{ $colors[$index]['color'] }}">
                                            {{ number_format((float) $row['total'], 2, '.', '') }}
                                        </span>
                                        <span class="text-xs font-bold text-{{ $colors[$index]['color'] }}">
                                            €
                                        </span>
                                        <div
                                            class="px-1 py-0.5 bg-{{ $colors[$index]['color'] }}/20 rounded flex-col justify-center
                                            items-center gap-2.5 inline-flex text-xs text-{{ $colors[$index]['color'] }} font-bold">
                                            {{ calculatePercentage($row['total'], $total) }} %
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- filter --}}

            <div class="flex-grow flex items-center pb-2 px-6 mt-6">
                <div class="w-full h-full flex items-center">
                    <table class="table-fixed bordered w-full">
                        <thead class="h-9 py-2">
                            <tr class="h-10 py-2 border-b border bg-esg5/10">
                                <th class="p-2 text-esg5 border text-left font-normal text-md w-2/5">
                                    {{ __('Sectors') }}</th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">
                                    {{ __('Asset') }}
                                </th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">
                                    {{ __('Acute') }}
                                </th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">
                                    {{ __('Chronic') }}
                                </th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">
                                    {{ __('Acute + Chronic') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('A - Agriculture, forestry and fisheries') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('B - Extractive industries') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('C - Manufacturing industries​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('D - Production and distribution of electricity, gas, steam and air conditioning') }}
                                </td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('E - Water supply, sanitation, waste management and depollution') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('F - Construction​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('G - Wholesale and retail trade, repair of motor vehicles and motorcycles​') }}
                                </td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('H - Transport and storage') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('L - Real estate activities​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000.000 €</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </x-cards.card>
    </div>
</div>
