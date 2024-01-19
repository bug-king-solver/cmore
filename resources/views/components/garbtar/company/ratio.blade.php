<div class="mt-10">
    <x-cards.garbtar.card class="!h-auto" type="grid" contentplacement="none">
        <div class="">
            <div class="grid grid-cols-1  gap-10">
                <div class="w-full">
                    @php
                        $accordionItems = [
                            [
                                'text' => __('Climate change mitigation'),
                                'eligible' => $company->bankAssetsEligibleCCM($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                'aligned' => $company->bankAssetsAlignedCCM($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                'transitional' => $company->bankAssetsTransitional($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                'enabling' => $company->bankAssetsEnablingCCM($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                            ],
                            [
                                'text' => __('Climate change adaptation'),
                                'eligible' => $company->bankAssetsEligibleCCA($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                'aligned' => $company->bankAssetsAlignedCCA($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                'transitional' => $company->bankAssetsAdaptation($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                'enabling' => $company->bankAssetsEnablingCCA($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                            ],
                            [
                                'text' => __('Sustainable use and protection of water and marine resources'),
                                'eligible' => 0,
                                'aligned' => 0,
                                'transitional' => 0,
                                'enabling' => 0,
                            ],
                            [
                                'text' => __('Transition to a circular economy'),
                                'eligible' => 0,
                                'aligned' => 0,
                                'transitional' => 0,
                                'enabling' => 0,
                            ],
                            [
                                'text' => __('Pollution prevention and control'),
                                'eligible' => 0,
                                'aligned' => 0,
                                'transitional' => 0,
                                'enabling' => 0,
                            ],
                            [
                                'text' => __('Protecting and restoring biodiversity and ecosystems'),
                                'eligible' => 0,
                                'aligned' => 0,
                                'transitional' => 0,
                                'enabling' => 0,
                            ],
                        ];
                        $labels = [__('Total exposure to the company'), __('Taxonomy-eligible assets'), __('Taxonomy Aligned Assets'), __('Transitional'), __('Enabling')];
                        $structure = [
                            'labels' => [__('Exposure'), __('Eligible'), __('Aligned'), $labels[3], $labels[4]],
                            'data' => [
                                $company->bankAssetsTotal($kpi === 'simulation' ? $simulation->id : null)[$stockflow],
                                $company->bankAssetsEligible($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                $company->bankAssetsAligned($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                $company->bankAssetsTransitionalAdaptation($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business],
                                $company->bankAssetsEnabling($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business]
                            ],
                            'id' => 'ratio',
                            'bar_color' => ['#1F5734', '#3C814F', '#59AB6B', '#76D586', '#A1E0A9'],
                            'unit' => null,
                            'type' => 'single',
                            'view' => 'x',
                            'currency' => tenant()->get_default_currency,
                            'locale' => auth()->user()->locale,
                        ];
                    @endphp
                    <x-charts.bar id="ratio" data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                        class="w-full mt-5" x-init="$nextTick(() => { barChartNew('ratio') });" />
                </div>

                <div>
                    <x-tables.table class="!min-w-full" x-data="{detail1: false, detail2: false, detail3: false, detail4: false}">
                        <x-slot name="thead">
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left !w-8">&nbsp;</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Asset types') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Value') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                        </x-slot>

                        @php
                        $totalBankAssets = $company->bankAssetsTotal($kpi === 'simulation' ? $simulation->id : null)[$stockflow];
                        @endphp
                        @foreach ($structure['data'] as $index => $item)
                            <x-tables.tr>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <div class="w-3 h-3 rounded-full bg-[{{ $structure['bar_color'][$index] }}]"></div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <div class="text-esg8 text-sm">{!! $labels[$index] !!}</div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                    <span class="text-xl font-bold text-esg6">
                                        <x-currency :value="$item" />
                                    </span>
                                    <span
                                        class="text-xs p-1 rounded text-[{{ $structure['bar_color'][$index] }}]">({{ calculatePercentage($item, $totalBankAssets, 2) }}%)</span>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 w-7 !p-1">
                                    @if ($index > 0)
                                        <div class="flex items-center justify-center" x-on:click="detail{{$index}} = ! detail{{$index}}">
                                            <div x-cloak class="p-1 border border-esg7/40 w-6 h-6 rounded text-center content-center bg-esg4 grid justify-items-center cursor-pointer" :class="detail{{$index}} ? '' : 'rotate-180'">
                                            @include('icons.double_arrow_up', [
                                                'color' => color(8),
                                            ])
                                            </div>
                                        </div>
                                    @endif
                                </x-tables.td>
                            </x-tables.tr>
                            @if ($index > 0)
                            @php
                            $keyname = 'eligible';
                            if ($index === 2) {
                                $keyname = 'aligned';
                            } else if ($index === 3) {
                                $keyname = 'transitional';
                            } else if ($index === 4) {
                                $keyname = 'enabling';
                            }
                            @endphp
                            <x-tables.tr x-cloak x-show="detail{{$index}}">
                                <x-tables.td colspan="4" class="!border-b-0 !py-0 bg-[#FBFBFD]">
                                    <div class="pl-8 pr-6">
                                        <x-tables.table class="!min-w-full">
                                            @foreach($accordionItems as $indexSubmenu => $submenu)
                                            <x-tables.tr>
                                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 w-4 text-[{{ $structure['bar_color'][$index] }}]">
                                                    {{ sprintf("%'02d", $indexSubmenu + 1) }}
                                                </x-tables.td>
                                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                                    {{ $submenu['text'] }}
                                                </x-tables.td>
                                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                                    <span class="text-xl font-bold text-esg6">
                                                        <x-currency :value="$submenu[$keyname]" />
                                                    </span>
                                                    <span
                                                        class="text-xs p-1 rounded text-[{{ $structure['bar_color'][$index] }}]">({{ calculatePercentage($submenu[$keyname], $totalBankAssets, 2) }}%)</span>
                                                </x-tables.td>
                                            </x-tables.tr>
                                            @endforeach
                                        </x-tables.table>
                                    </div>
                                </x-tables.td>
                            </x-tables.tr>
                            @endif
                        @endforeach

                        </x-tables-table>
                </div>
            </div>

            <div class="mt-10">
                <x-tables.table class="!min-w-full">
                    <x-slot name="thead">
                        <x-tables.th class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal">&nbsp;</x-tables.th>
                        <x-tables.th class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal text-center uppercase px-2">
                            <div class="flex gap-1 justify-center">
                                <div class="self-center">
                                    @include('icons.garbtar.gar', ['width' => '19', 'heigth' => '20'])
                                </div>
                                <span class="self-center">{{ __('GAR') }}</span>
                            </div>
                        </x-tables.th>
                        <x-tables.th class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal text-center uppercase px-2">
                            <div class="flex gap-1">
                                <div class="self-center">
                                    @include('icons.garbtar.btar', ['width' => '26', 'heigth' => '20'])
                                </div>
                                <span class="self-center">{{ __('BTAR') }}</span>
                            </div>
                        </x-tables.th>
                    </x-slot>

                    <x-tables.tr>
                        <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                            <div class="text-sm text-esg8">{!! __('Customers alignment (as a percentage of total customer´s assets)') !!}</div>
                        </x-tables.td>
                        <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                            <span class="text-sm text-esg8">
                                <span class="text-xs p-1 rounded text-[#39B54A] bg-[#39B54A]/20">
                                    {{ calculatePercentage(array_sum($company->bankAssetsAlignedByGARBTAR($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR]), $company->bankAssetsCalculations($kpi === 'simulation' ? $simulation->id : null)[$stockflow], 2) }}%
                                </span>
                            </span>
                        </x-tables.td>
                        <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                            <span class="text-sm text-esg8">
                                <span class="text-xs p-1 rounded text-[#39B54A] bg-[#39B54A]/20">
                                    {{ calculatePercentage(array_sum($company->bankAssetsAlignedByGARBTAR($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::BTAR]), $company->bankAssetsCalculations($kpi === 'simulation' ? $simulation->id : null)[$stockflow], 2) }}%
                                </span>
                            </span>
                        </x-tables.td>
                    </x-tables.tr>

                    <x-tables.tr>
                        <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                            <div class="text-sm text-esg8">{!! __('Customer impact on the Banks overall total ratio') !!}</div>
                        </x-tables.td>
                        <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                            <span class="text-xs p-1 rounded text-[#39B54A] bg-[#39B54A]/20">
                                {{ formatNumber(calculatePercentage(array_sum($company->bankAssetsAlignedByGARBTAR($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR]), $denominatorGAR[$stockflow], 6), 6) }}%
                            </span>
                        </x-tables.td>
                        <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                            <span class="text-xs p-1 rounded text-[#39B54A] bg-[#39B54A]/20">
                                {{ formatNumber(calculatePercentage(array_sum($company->bankAssetsAlignedByGARBTAR($kpi === 'simulation' ? $simulation->id : null)[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::BTAR]), $denominatorBTAR[$stockflow], 6), 6) }}%
                            </span>
                        </x-tables.td>
                    </x-tables.tr>
                </x-tables.table>
            </div>
        </div>
    </x-cards.garbtar.card>

    <div class="mt-6">
        <x-cards.garbtar.card class="!h-auto" type="grid !-mt-5" contentplacement="none">
            <div class="">
                <div class="flex justify-between">
                    <div class="flex items-center gap-5">
                        <label class="text-base font-bold text-esg6 uppercase">{!! __('Asset list') !!}</label>
                        @if ($kpi === 'simulation')
                            @php $data = json_encode(["simulation" => $simulation->id ?? '']); @endphp
                            <x-buttons.btn-icon-text modal="companies.modals.ratios.assets" :data="$data"
                                class="cursor-pointer !bg-esg4 !normal-case !text-[#757575] !border-0 !font-normal"
                                :param="json_encode(['color' => color(16), 'width' => 14, 'height' => 14])">
                                <x-slot:buttonicon>+</x-slot:buttonicon>
                                <x-slot:slot>{!! __('Add asset') !!}</x-slot:slot>
                            </x-buttons.btn-icon-text>
                        @endif
                    </div>
                </div>
                <div class="mt-6">
                    <x-tables.table class="!min-w-full">
                        <x-slot name="thead">
                            <x-tables.th
                                class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal text-left">{{ __('Type') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal text-left">{{ __('Asset type') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal text-left">{{ __('Value') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal text-center">{{ __('Specific purpose') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-[#44724D] text-esg6 text-sm pb-2 font-normal text-left"></x-tables.th>
                        </x-slot>
                        @php
                            $data = $company->listAssets($kpi === 'simulation' ? $simulation->id : null);
                        @endphp
                        @foreach ($data as $asset)
                            @php
                                $colorText = 'text-esg8';
                                $colorBg = 'bg-esg5/20';
                                $typeAsset = 'Real';
                                if ($kpi === 'simulation' || isset($asset['simulation'])) {
                                    if ($asset['simulation']['bank'] ?? false) {
                                        if ($asset['simulation']['real'] ?? false) {
                                            $colorText = 'text-esg8';
                                            $colorBg = 'bg-esg5/20';
                                            $typeAsset = 'Real - Bank';
                                        } else {
                                            $colorText = 'text-esg8';
                                            $colorBg = 'bg-esg5/50';
                                            $typeAsset = 'Real - Manual';
                                        }
                                    } else {
                                        $colorText = 'text-[#B1B1B1]';
                                        $colorBg = 'bg-[#FD8D3C]/20';
                                        $typeAsset = 'Simulation';
                                    }
                                }
                            @endphp
                            <x-tables.tr>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-2">
                                    <span
                                        class="text-sm {{ $colorText }} {{ $colorBg }} px-2 py-1 rounded text-center">{!! __($typeAsset) !!}</span>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-2">
                                    <div class="text-sm {{ $colorText }}">{{ $this->assetTypeList[$asset[App\Models\Tenant\GarBtar\BankAssets::TYPE]] ?? '' }}
                                    </div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-2 text-left">
                                    <span class="text-sm {{ $colorText }}">
                                        <x-currency :value="$stockflow === 'stock' ? $asset[App\Models\Tenant\GarBtar\BankAssets::STOCK_FIELD] : $asset[App\Models\Tenant\GarBtar\BankAssets::FLOW_FIELD]" />
                                    </span>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-2 text-center">
                                    <label class="inline-flex items-center font-encodesans">
                                        {{ $asset[App\Models\Tenant\GarBtar\BankAssets::SPECIFIC_PURPOSE] === App\Models\Tenant\GarBtar\BankAssets::YES ? __('Yes') : __('No') }}
                                    </label>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-2 text-right">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1 border border-esg7/40 rounded text-center bg-esg4 grid justify-items-center cursor-pointer"
                                            wire:click="showInformationRow({{ $loop->index }})">
                                            @if ($loop->index === $rowActive)
                                                @include('icons.double_arrow_up', ['color' => color(8)])
                                            @else
                                                @include('icons.double_arrow_up', [
                                                    'color' => color(8),
                                                    'class' => 'rotate-180',
                                                ])
                                            @endif
                                        </div>
                                        @if ($kpi === 'real')
                                        @if ($asset->tipo != '3')
                                        @php $data = json_encode(['asset' => $asset->id]); @endphp
                                            <div class="text-center bg-esg4 grid justify-items-center cursor-pointer">
                                                <x-buttons.edit modal="companies.modals.ratios.real" :data="$data"
                                                    class="px-2 py-1" class="cursor-pointer" :param="json_encode(['stroke' => color(8)])" />
                                            </div>
                                        @endif
                                        @elseif ($kpi === 'simulation')
                                            @php $data = json_encode(["simulation" => $simulation->id ?? '', 'asset' => $asset->id]); @endphp
                                            <div class="text-center bg-esg4 grid justify-items-center cursor-pointer">
                                                <x-buttons.edit modal="companies.modals.ratios.assets" :data="$data"
                                                    class="px-2 py-1" class="cursor-pointer" :param="json_encode(['stroke' => color(8)])" />
                                            </div>
                                            <div class="text-center bg-esg4 grid justify-items-center cursor-pointer">
                                                <x-buttons.trash modal="companies.modals.ratios.delete"
                                                    :data="$data" class="px-2 py-1" class="cursor-pointer"
                                                    :param="json_encode(['stroke' => color(8)])" />
                                            </div>
                                        @endif
                                    </div>
                                </x-tables.td>
                            </x-tables.tr>
                            <x-tables.tr class="{{ $loop->index !== $rowActive ? 'hidden' : '' }}">
                                <x-tables.td colspan="7" class="bg-[#FBFBFD]">
                                    <x-garbtarassets.detail :row="$asset" />
                                </x-tables.td>
                            </x-tables.tr>
                        @endforeach

                    </x-tables.table>
                </div>
            </div>
        </x-cards.garbtar.card>
    </div>
</div>
