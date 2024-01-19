<div class="mt-10 {{ $graphOption != 'eligible' ? 'hidden' : '' }}">
    <x-cards.garbtar.card text="{{ json_encode([ __('Eligible')]) }}"
        subpoint="{{ json_encode([[ 'text' => __('Set of assets that correspond to the financial institution`s exposure to activities or assets eligible for the European Union`s Green Taxonomy.') ]]) }}"
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
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'entity'"
                        :class="option == 'entity' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Entity types') }}
                    </button>
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'main'"
                        :class="option == 'main' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Main sectors') }}
                    </button>
                </div>
            </div>

            <div class="" x-show="option == 'asset'">
            @php
                    $sector1 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['1'];
                    $sector2 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['2'];
                    $sector3 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['3'];
                    $sector4 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['4'];
                    $sector5 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['5'];
                    $sector6 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['6'];
                    $sector7 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['7'];
                    $sector8 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['8'];
                    $sector9 = $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ELIGIBLE]['9'];
                    $dataGraph = [
                        [
                            'text' => __('Loans and prepayments to companies'),
                            'background' => null,
                            'main' => true,
                            'value' => $sector1,
                            'filter' => [
                                'type' => '1'
                            ],
                        ],
                        [
                            'text' => __('Debt securities, including participation units'),
                            'background' => null,
                            'main' => true,
                            'value' => $sector2,
                            'filter' => [
                                'type' => '2'
                            ],
                        ],
                        [
                            'text' => __('Equity instruments'),
                            'background' => null,
                            'main' => true,
                            'value' => $sector3,
                            'filter' => [
                                'type' => '3'
                            ],
                        ],
                        [
                            'text' => __('Loans to households'),
                            'background' => 'bg-[#EBF9ED]',
                            'main' => true,
                            'value' => $sector4 + $sector5 + $sector6,
                            'filter' => [
                                'type' => '4,5,6'
                            ],
                        ],
                        [
                            'text' => __('Loans to households secured by residential property'),
                            'background' => null,
                            'main' => false,
                            'value' => $sector4,
                            'filter' => [
                                'type' => '4'
                            ],
                        ],
                        [
                            'text' => __('Loans to households for the renovation of buildings'),
                            'background' => null,
                            'main' => false,
                            'value' => $sector5,
                            'filter' => [
                                'type' => '5'
                            ],
                        ],
                        [
                            'text' => __('Loans to households for the purchase of cars'),
                            'background' => null,
                            'main' => false,
                            'value' => $sector6,
                            'filter' => [
                                'type' => '6'
                            ],
                        ],
                        [
                            'text' => __('Loans to the local public sector'),
                            'background' => 'bg-[#EBF9ED]',
                            'main' => true,
                            'value' => round($sector7 + $sector8 + $sector9, 2),
                            'filter' => [
                                'type' => '7,8,9'
                            ],
                        ],
                        [
                            'text' => __('Loans to the public sector for housing construction'),
                            'background' => null,
                            'main' => false,
                            'value' => $sector7,
                            'filter' => [
                                'type' => '7'
                            ],
                        ],
                        [
                            'text' => __('Other Loans to the local public sector'),
                            'background' => null,
                            'main' => false,
                            'value' => $sector8,
                            'filter' => [
                                'type' => '8'
                            ],
                        ],
                        [
                            'text' => __('Residential and commercial real estate obtained by acquiring ownership'),
                            'background' => null,
                            'main' => false,
                            'value' => $sector9,
                            'filter' => [
                                'type' => '9'
                            ],
                        ],
                    ];
                    if ($kpi === 'gar') {
                        $institucoes_credito = $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::CREDIT_INSTITUTIONS][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE];
                        $empresas_investimento = $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INVESTMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE];
                        $sociedades_gestoras = $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::MANAGEMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE];
                        $empresas_seguros = $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INSURANCE_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE];
                        $dataGraph = array_merge($dataGraph, [
                            [
                                'text' => __('Financial companies'),
                                'background' => 'bg-[#EBF9ED]',
                                'main' => true,
                                'value' => round($institucoes_credito + $empresas_investimento + $sociedades_gestoras + $empresas_seguros, 2),
                                'filter' => [
                                    'entity' => '1,2,3,4',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Credit institutions'),
                                'background' => null,
                                'main' => false,
                                'value' => $institucoes_credito,
                                'filter' => [
                                    'entity' => '1',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Investment companies'),
                                'background' => null,
                                'main' => false,
                                'value' => $empresas_investimento,
                                'filter' => [
                                    'entity' => '2',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Management Companies'),
                                'background' => null,
                                'main' => false,
                                'value' => $sociedades_gestoras,
                                'filter' => [
                                    'entity' => '3',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Insurance companies'),
                                'background' => null,
                                'main' => false,
                                'value' => $empresas_seguros,
                                'filter' => [
                                    'entity' => '4',
                                    'type' => '1,2,3',
                                ],
                            ],
                        ]);
                    }
                @endphp
                <x-garbtar.asset_types :dataGraph="$dataGraph" idGraph='eligible_asset' />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10" x-show="option == 'entity'">
                @php
                    $labels = [
                        __('Credit institutions'),
                        __('Investment companies'),
                        __('Management Companies'),
                        __('Insurance companies'),
                        __('Non-financial companies'),
                        __('Loans to households'),
                        __('Loans to the local public sector'),
                        __('Residential and commercial real estate obtained by acquiring ownership'),
                    ];

                    $dataGraph = [
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::CREDIT_INSTITUTIONS][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INVESTMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::MANAGEMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INSURANCE_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::NON_FINANCIAL_COMPANIES_SUBJECT_NFRD][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::FAMILIES][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::REAL_STATE][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE],
                    ];

                    $entities = [1, 2, 3, 4, 5, 6, 7, 8, 9];

                    if ($kpi === 'btar') {
                        $entities = [1, 2, 3, 4, 5, 5, 6, 7, 8, 9];
                        $labels[4] = __('Others Non-financial companies');
                        array_splice($labels, 4, 0, [__('Non-financial companies subject to the CSRD')]);
                        array_splice($dataGraph, 5, 0, [$data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::BTAR][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD][App\Models\Tenant\GarBtar\BankAssets::ELIGIBLE]]);
                    }
                @endphp
                <x-garbtar.entity_types :dataGraph="$dataGraph" :labels="$labels" :entities="$entities" idGraph="eligible_entity_type" />

            </div>

            <div class="mt-10" x-show="option == 'main'">
                @php
                    $dataGraph = [
                        'info' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE],
                        'nace' => $data['nace'],
                    ];
                @endphp
                <x-garbtar.main_sectors :data="$dataGraph" colorGraph="#59AB6B" colorLabel="#1F5734" idGraph="eligible_main_type" :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />
            </div>
        </div>
    </x-cards.garbtar.card>
</div>
