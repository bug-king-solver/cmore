<div class="mt-10 {{ $graphOption != 'notaligned3' ? 'hidden' : '' }}">
    @php
        $subtext = null;
        if ($kpi == 'gar' && ($stockflow == 'stock' || $stockflow == 'flow')) {
            $subtext = json_encode([
                [ 'text' => __('Set of assets that are considered in the denominator of the GAR calculation but are by nature excluded from the numerator. Includes assets on entities not subject to the CSRD, essentially SMEs and foreign entities, and some special asset classes.') ]
            ]);
        } else if ($kpi == 'btar' && ($stockflow == 'stock' || $stockflow == 'flow')) {
            $subtext = json_encode([
                [ 'text' => __('Set of assets that are considered in the denominator of the BTAR calculation but are by nature excluded from the numerator. Includes some special asset classes.') ]
            ]);
        } else {
            $subtext = json_encode([
                ['text' => __('Set of assets that are considered in the denominator of the GAR calculation but are by nature excluded from the numerator. Includes assets on entities not subject to the CSRD, essentially SMEs and foreign entities, and some special asset classes.') ]
            ]);
        }
    @endphp
    <x-cards.garbtar.card text="{{ json_encode([ __('Excluded from the ') . ($kpi == 'gar' ? __('GAR') : __('BTAR'))]) }}"
        subpoint="{{ $subtext }}"
        class="!h-auto"
        type="grid"
        contentplacement="none">
        <div class="" x-data="{option: 'asset'}">
            <div class="">
                <div class="inline-flex rounded-md shadow-sm bg-esg6/10 p-1" role="group">
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'asset'"
                        :class="option == 'asset' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Asset types') }}
                    </button>
                    @if($kpi === 'gar')
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'main'"
                        :class="option == 'main' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Main sectors') }}
                    </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10" x-show="option == 'asset'">
                @if ($kpi == 'btar' && ($stockflow == 'stock' || $stockflow == 'flow'))
                    <div class="!h-[250px] !w-[250px] m-auto">
                        @php
                            $labels = [
                                __('Derivatives'),
                                __('Interbank demand loans'),
                                __('Cash and cash equivalents'),
                                __('Other assets (e.g. goodwill, commodities, etc.)'),
                            ];
                            $structure = [
                                'labels' => ['01', '02', '03', '04'],
                                'data' => [
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['10'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['11'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['12'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['13'],
                                ],
                                'id' => 'exculded_asset_type_new',
                                'bar_color' => ['#1F5734', '#3C814F', '#59AB6B', '#76D586'],
                                'unit' => 'M€',
                                'position' => [35, 55],
                                'currency' => tenant()->get_default_currency,
                                'locale' => auth()->user()->locale,
                            ];
                            $totalBalance = array_sum($structure['data']);
                            $percentagens = [];
                            foreach($structure['data'] as $item) {
                                $percentagens[] = calculatePercentage($item, $totalBalance, 2);
                            }
                            $totalBalanceP = round(array_sum($percentagens), 0);
                            $filters = [
                                ['type' => '10'],
                                ['type' => '11'],
                                ['type' => '12'],
                                ['type' => '13'],
                            ];
                        @endphp
                        <x-charts.donut id="exculded_asset_type_new"
                            data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                            class="m-auto !h-[250px] !w-[250px] mt-5"  x-init="$nextTick(() => { pieChartNew('exculded_asset_type_new') });" />
                    </div>

                    <div>
                        <x-tables.table class="!min-w-full">
                            <x-slot name="thead">
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left !w-8">&nbsp;</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Entidade') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-56">{{ __('value') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                            </x-slot>

                            @foreach($labels as $key => $value)
                            <x-tables.tr>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <div class="text-esg6 text-sm font-extrabold">{{ $structure['labels'][$key] }}</div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 text-esg8 !py-1.5">{{ $value }}</x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                    <span class="text-xl font-bold text-esg6">
                                        <x-currency :value="$structure['data'][$key]" />
                                    </span>
                                    <span class="text-xs p-1 rounded text-esg6 bg-esg5/20">{{ $percentagens[$key] }} %</span>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                        href="{{ route('tenant.garbtar.assets', $filters[$key]) }}">
                                        @include('icons.list')
                                    </x-buttons.a-icon-simple>
                                </x-tables.td>
                            </x-tables.tr>
                            @endforeach
                        </x-tables-table>
                    </div>
                @else
                    <div class="w-full">
                        @php
                            $structure = [
                                'labels' => ['01', '02', '03', '04', '05', '06'],
                                'data' => [
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['10'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['11'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['12'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['13'],
                                ],
                                'id' => 'exculded_asset_type',
                                'bar_color' => ['#39B54A'],
                                'unit' => null,
                                'type' => 'single',
                                'view' => 'y',
                                'currency' => tenant()->get_default_currency,
                                'locale' => auth()->user()->locale,
                            ];
                            $totalPie = array_sum($structure['data']);
                            $percentagens = [];
                            $labels = [
                                __('Companies not subject to CSRD'),
                                __('Non-European companies not subject to CSRD'),
                                __('Derivatives'),
                                __('Interbank demand loans'),
                                __('Cash and cash equivalents'),
                                __('Other assets (e.g. goodwill, commodities, etc.)'),
                            ];
                            $filters = [
                                [
                                    'entity' => '5',
                                    'type' => '1,2,3'
                                ],
                                [
                                    'entity' => '5',
                                    'type' => '1,2,3'
                                ],
                                ['type' => '10'],
                                ['type' => '11'],
                                ['type' => '12'],
                                ['type' => '13'],
                            ];
                            foreach($structure['data'] as $item) {
                                $percentagens[] = calculatePercentage($item, $totalPie, 2);
                            }
                            $totalBalanceP = round(array_sum($percentagens), 0);
                        @endphp
                        <x-charts.bar id="exculded_asset_type"
                            data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                            class="w-full mt-5"  x-init="$nextTick(() => { barChartNew('exculded_asset_type') });" />
                    </div>

                    <div>
                        <x-tables.table class="!min-w-full">
                            <x-slot name="thead">
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left !w-8">&nbsp;</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Entidade') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-56">{{ __('value') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                            </x-slot>

                            @foreach($labels as $key => $value)
                            <x-tables.tr>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <div class="text-esg6 text-sm font-extrabold">{{ $structure['labels'][$key] }}</div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 text-esg8 !py-1.5">{{ $value }}</x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                    <span class="text-xl font-bold text-esg6">
                                        <x-currency :value="$structure['data'][$key]" />
                                    </span>
                                    <span class="text-xs p-1 rounded text-esg6 bg-esg5/20">{{ $percentagens[$key] }} %</span>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                        href="{{ route('tenant.garbtar.assets', $filters[$key]) }}">
                                        @include('icons.list')
                                    </x-buttons.a-icon-simple>
                                </x-tables.td>
                            </x-tables.tr>
                            @endforeach

                            <x-tables.tr>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10"></x-tables.td>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10"><p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p></x-tables.td>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10 text-right">
                                    <span class="text-xl font-bold text-esg6">
                                        <x-currency :value="$totalPie" />
                                </span>
                                <span class="text-xs p-1 rounded text-esg6 bg-[#1F5734]/10">{{ $totalBalanceP }} %</span></x-tables.td>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10"></x-tables.td>
                            </x-tables.tr>

                        </x-tables-table>
                    </div>
                @endif
            </div>

            <div class="mt-10" x-show="option == 'main'">
                @php
                    $dataGraph = [
                        'info' => $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_NOT_COVERED] ?? [],
                        'nace' => $data['nace'],
                    ];
                @endphp
                <x-garbtar.main_sectors :data="$dataGraph" colorGraph="#1F5734" colorLabel="#39B54A" idGraph="exculded_main_type" :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />

            </div>
        </div>
    </x-cards.garbtar.card>
</div>
