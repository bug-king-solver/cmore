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

    <x-menus.panel :buttons="$buttons" activated='tenant.garbtar.crr.metrics' />

    @php
        $colors = [color(1), color(5), color(13), color(20), color(7)];
    @endphp

    <div class="grid grid-cols-1 gap-4 mt-6">
        <x-cards.card class="flex-col !p-3">
            <h2 class="text-lg text-esg5 font-bold pb-6 align-self-start uppercase">{{ __("Sector's List") }}</h2>

            {{-- filter --}}

            <div class="flex-grow flex items-center pb-2 px-6 mt-6">
                <div class="w-full h-full flex items-center">
                    <table class="table-fixed bordered w-full">
                        <thead class="h-9 py-2">
                            <tr class="h-10 py-2 border-b border bg-esg5/10">
                                <th class="p-2 text-esg5 border text-left font-normal text-md w-2/5">{{ __('Sectors') }}
                                </th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">{{ __('Asset') }}
                                </th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">{{ __('Metric') }}
                                </th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">
                                    {{ __('Weighted Average') }}</th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">
                                    {{ __('Reference year') }}</th>
                                <th class="p-2 text-esg5 border text-center font-normal text-md">
                                    {{ __('Distance to NZ2050') }}</th>
                                <th class="p-2 text-esg5 border text-center font-normal text">
                                    <span class="flex flex-row items-center justify-center">
                                        {{ __('Target') }}
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Energy') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Combustion of fossil fuels') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Automobile​​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Aviation​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Maritime transport') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Production of cement, clinker and lime​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Production of iron and steel, coke and metallic ore ​​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">000000</td>
                            </tr>
                            <tr class="h-10 py-2 border">
                                <td class="p-2 text-esg8 border text-left font-normal text-md w-2/5">
                                    {{ __('Chemicals​') }}</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                                <td class="p-2 text-esg8 border text-center font-normal text-md">0000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-cards.card>
    </div>

</div>
