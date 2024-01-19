<div class="mt-10 {{ $graphOption != 'covered' ? 'hidden' : '' }}">
    @php
        $subtext = null;
        if ($kpi == 'gar' && ($stockflow == 'stock' || $stockflow == 'flow')) {
            $subtext = json_encode([['text' => __('Assets that could theoretically be included in the numerator of the GAR. This leaves out assets that by their nature are not used for ratio calculations and assets that although they are used for the denominator cannot be included in the numerator of the GAR.')]]);
        } elseif ($kpi == 'btar' && ($stockflow == 'stock' || $stockflow == 'flow')) {
            $subtext = json_encode([['text' => __('Assets that could theoretically be included in the numerator of the GAR. This leaves out assets that by their nature are not used for ratio calculations and assets that although they are used for the denominator cannot be included in the BTAR numerator.')]]);
        } else {
            $subtext = json_encode([['text' => __('Assets that could theoretically be included in the numerator of the GAR. This leaves out assets that by their nature are not used for ratio calculations and assets that although they are used for the denominator cannot be included in the numerator of the GAR.')]]);
        }
    @endphp
    <x-cards.garbtar.card text="{{ json_encode([__('Covered by the ') . ($kpi == 'gar' ? 'GAR' : 'BTAR')]) }}"
        subpoint="{{ $subtext }}" class="!h-auto" type="grid" contentplacement="none">
        <div class="" x-data="{ option: 'entity' }">
            <div class="">
                <div class="inline-flex rounded-md shadow-sm bg-esg6/10 p-1" role="group">
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'entity'"
                        :class="option == 'entity' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Entity types') }}
                    </button>
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'asset'"
                        :class="option == 'asset' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Asset types') }}
                    </button>
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'main'"
                        :class="option == 'main' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Main sectors') }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10" x-show="option == 'entity'">
                @php
                    $labels = [
                        __('Credit institutions'),
                        __('Investment companies'),
                        __('Management Companies'),
                        __('Insurance companies'),
                        __('Non-financial companies'),
                        __('Households'),
                        __('Local authorities'),
                        __('Own real estate'),
                    ];

                    $dataGraph = [
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::CREDIT_INSTITUTIONS],
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::INVESTMENT_COMPANIES],
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::MANAGEMENT_COMPANIES],
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::INSURANCE_COMPANIES],
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::NON_FINANCIAL_COMPANIES],
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES],
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL],
                        $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::REAL_STATE],
                    ];

                    $entities = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                    if ($kpi === 'btar') {
                        $entities = [1, 2, 3, 4, 5, 5, 6, 7, 8, 9];
                        $labels[4] = __('Others Non-financial companies');
                        array_splice($labels, 5, 0, [__('Non-financial companies subject to the CSRD')]);
                        array_splice($dataGraph, 5, 0, [$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD]]);
                    }
                @endphp
                <x-garbtar.entity_types :dataGraph="$dataGraph" :labels="$labels" :entities="$entities" colorGraph="#39B54A" labelColor="#1F5734" idGraph="entity_type" />

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10" x-show="option == 'asset'">
                <div class="w-full">

                    @php
                        $index = 1;
                        $labelsGraph = [];
                        $dataGraph = [];
                        $labels = [
                            __('Loans and prepayments to companies'),
                            __('Debt securities, including participation units'),
                            __('Equity instruments'),
                            __('Loans to households secured by residential property'),
                            __('Loans to households for the renovation of buildings'),
                            __('Loans to households for the purchase of cars'),
                            __('Loans to the public sector for housing construction'),
                            __('Other Loans to the local public sector'),
                            __('Residential and commercial real estate obtained by acquiring ownership'),
                        ];
                        foreach($data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_COVERED] as $key => $item) {
                            $dataGraph[] = $item;
                            $labelsGraph[] = sprintf("%'02d", $index);
                            $index++;
                        }
                        $filters = [
                            [
                                'type' => '1',
                                'entity' => '5',
                            ],
                            [
                                'type' => '2',
                                'entity' => '5',
                            ],
                            [
                                'type' => '3',
                                'entity' => '5',
                            ],
                            [
                                'type' => '4',
                            ],
                            [
                                'type' => '5',
                            ],
                            [
                                'type' => '6',
                            ],
                            [
                                'type' => '7',
                            ],
                            [
                                'type' => '8',
                            ],
                            [
                                'type' => '9',
                            ],
                        ];
                        if ($kpi === 'gar') {
                            $labels[] = __('Credit institutions');
                            $labels[] = __('Investment companies');
                            $labels[] = __('Management Companies');
                            $labels[] = __('Insurance companies');
                            $dataGraph[] = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::CREDIT_INSTITUTIONS];
                            $labelsGraph[] = '10';
                            $dataGraph[] = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::INVESTMENT_COMPANIES];
                            $labelsGraph[] = '11';
                            $dataGraph[] = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::MANAGEMENT_COMPANIES];
                            $labelsGraph[] = '12';
                            $dataGraph[] = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::INSURANCE_COMPANIES];
                            $labelsGraph[] = '13';
                            $filters = array_merge($filters, [
                                [
                                    'type' => '1,2,3',
                                    'entity' => '5'
                                ],
                                [
                                    'type' => '1,2,3',
                                    'entity' => '4'
                                ],
                                [
                                    'type' => '1,2,3',
                                    'entity' => '3'
                                ],
                                [
                                    'type' => '1,2,3',
                                    'entity' => '2'
                                ],
                                [
                                    'type' => '1,2,3',
                                    'entity' => '1'
                                ],
                            ]);
                        }
                        $structure = [
                            'labels' => $labelsGraph,
                            'data' => $dataGraph,
                            'id' => 'asset_type',
                            'bar_color' => ['#1F5734'],
                            'label_color' => ['#39B54A'],
                            'unit' => null,
                            'type' => 'single',
                            'view' => 'y',
                            'currency' => tenant()->get_default_currency,
                            'locale' => auth()->user()->locale,
                        ];
                        $totalBalance = array_sum($structure['data']);
                        $percentagens = [];
                        foreach($structure['data'] as $item) {
                            $percentagens[] = calculatePercentage($item, $totalBalance, 2);
                        }
                        $totalBalanceP = round(array_sum($percentagens), 0);
                    @endphp
                    <x-charts.donut id="asset_type" data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                        class="w-full mt-5" x-init="$nextTick(() => { barChartNew('asset_type') });" />
                </div>

                <div>
                    <x-tables.table class="!min-w-full">
                        <x-slot name="thead">
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left !w-8">&nbsp;</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Entidade') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-56">{{ __('value') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                        </x-slot>

                        @foreach($dataGraph as $key => $value)
                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <div class="text-[#1F5734] text-sm font-extrabold">{{ $labelsGraph[$key] }}</div>
                            </x-tables.td>
                            <x-tables.td
                                class="text-sm !border-b-esg7/40 text-esg8 !py-1.5">{{ $labels[$key] }}</x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#1F5734]">
                                    <x-currency :value="$value" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $percentagens[$key] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', $filters[$key]) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>
                        @endforeach

                        <x-tables.tr>
                            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#1F5734]/20"></x-tables.td>
                            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#1F5734]/20">
                                <p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p>
                            </x-tables.td>
                            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#1F5734]/20 text-right">
                                <span class="text-xl font-bold text-[#1F5734]">
                                    <x-currency :value="$totalBalance" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $totalBalanceP }}%</span>
                            </x-tables.td>
                            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#1F5734]/20"></x-tables.td>
                        </x-tables.tr>

                        </x-tables-table>
                </div>
            </div>

            <div class="mt-10" x-show="option == 'main'">
                @php
                    $first = ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_COVERED_GAR]) - ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES]);
                    if ($kpi === 'btar') {
                        $first = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_COVERED_BTAR] - ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES] + $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU] + $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU]);
                    }
                    $dataGraph = [
                        'info' => array_merge(["NA" => $first], $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_COVERED]),
                        'nace' => array_merge([
                            "NA" => [
                                'name' => __('Non-business assets'),
                                'code' => '',
                            ]
                        ], $data['nace']),
                    ];
                @endphp
                <x-garbtar.main_sectors :data="$dataGraph" colorGraph="#59AB6B" colorLabel="#1F5734" idGraph="main_type" :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />
            </div>
        </div>
    </x-cards.garbtar.card>
</div>
