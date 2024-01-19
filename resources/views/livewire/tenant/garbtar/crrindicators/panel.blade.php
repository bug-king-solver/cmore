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

    <x-menus.panel :buttons="$buttons" activated='tenant.garbtar.crr.panel' />

    @php
        $colors = [['hex' => color(5), 'color' => 'esg5'], ['hex' => hex2rgba(color(5), 0.60), 'color' => 'esg5/60'], ['hex' => hex2rgba(color(5), 0.3), 'color' => 'esg5/40']];
    @endphp

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg5 font-bold pb-6 align-self-start uppercase">{{ __('Financed CO2 Emissions') }}
            </h2>
            <div class="flex justify-evenly flex-row items-center mb-6">
                <x-charts.chartjs id="financed_co2_emissions" class="max-w-[150px] max-h-[300px]"
                    x-init="$nextTick(() => {
                        tenantDoughnutChart(
                            {{ json_encode([__('Scope 1'), __('Scope 2'), __('Scope 3')], true) }},
                            {{ json_encode([513.1, 751.03, 800.0], true) }},
                            'financed_co2_emissions',
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
                            <th class="text-esg8 text-sm font-normal text-left uppercase"></th>
                            <th class="w-50 text-esg8 text-sm font-normal text-left uppercase">{{ __('Value') }}</th>
                        </thead>
                        <tbody>
                            @php
                                $data = [
                                    [
                                        'name' => __('Scope 1'),
                                        'total' => 513.1,
                                    ],
                                    [
                                        'name' => __('Scope 2'),
                                        'total' => 751.03,
                                    ],
                                    [
                                        'name' => __('Scope 3'),
                                        'total' => 800.0,
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
                                    <td
                                        class="p-2 grow shrink basis-0 text-esg8 text-sm font-normal font-{{ $colors[$index]['color'] }}">
                                        {{ $row['name'] }}
                                    </td>
                                    <td class="p-2 w-50 text-right">
                                        <span class="text-xl font-bold text-{{ $colors[$index]['color'] }}">
                                            {{ number_format((float) $row['total'], 2, '.', '') }}
                                        </span>
                                        <span class="text-xs font-bold text-{{ $colors[$index]['color'] }}">
                                            t CO2 eq
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
                            <th colSpan="1" class="border-none w-2/5"></th>
                            <th colSpan="5" class="text-esg5 text-sm font-normal text-center border">
                                {{ __('FINANCED (tCO2eq)') }}
                            </th>
                        </thead>
                        <tbody>
                            <tr class="h-10 py-2 border-b text-left border bg-esg5/10">
                                <th class="p-2 text-esg5 border text-left font-normal text-md w-2/5">
                                    {{ __('Sectors') }}
                                </th>
                                <th class="p-2 text-esg5 border text-left font-normal text-md">
                                    {{ __('Scope 1') }}
                                </th>
                                <th class="p-2 text-esg5 border text-left font-normal text-md">
                                    {{ __('Scope 2') }}
                                </th>
                                <th class="p-2 text-esg5 border text-left font-normal text-md">
                                    {{ __('Scope 3') }}
                                </th>
                                <th class="p-2 text-esg5 border text-left font-normal text-md">
                                    {{ __('Total') }}
                                </th>
                                <th class="p-2 text-esg5 border text-left font-normal text-md">
                                    {{ __('% estimated') }}
                                </th>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('A - Agriculture, forestry and fisheries') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('B - Extractive industries') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('C - Manufacturing industries​') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('D - Production and distribution of electricity, gas, steam and air conditioning') }}
                                </td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('E - Water supply, sanitation, waste management and depollution') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('F - Construction​') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('G - Wholesale and retail trade, repair of motor vehicles and motorcycles​') }}
                                </td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('H - Transport and storage') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('I - Accommodation and restaurant activities​') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('J - Information and services communication') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('K - Financial and business activities insurance') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('L - Real estate activities​') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('M - Consulting services, scientific and technical') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('N - Administrative services and other support services') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('O - Administration services public and defense; security mandatory social') }}
                                </td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('P - Education services') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Q - Health and support services Social') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('R - Artistic, recreational services and spectacle') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('S - Outras atividades de serviços') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('T - Family activities staff employers domestic; activities of production of goods and services by families for enjoyment own') }}
                                </td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('U - Activities of organizations and extraterritorial entities') }}</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-left font-normal text-md">000000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-cards.card>
    </div>

</div>
