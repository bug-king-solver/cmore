<div class="mt-10 {{ $graphOption != 'aligned' ? 'hidden' : '' }}">
    <x-cards.garbtar.card text="{{ json_encode([ __('aligned')]) }}"
        subpoint="{{ json_encode([[ 'text' => __('Set of assets that correspond to the financial institution`s exposure to activities or assets aligned with the European Union`s green taxonomy.') ]]) }}"
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
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = 'environmental'"
                        :class="option == 'environmental' ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Environmental objectives') }}
                    </button>
                </div>
            </div>

            <div class="" x-show="option == 'asset'">
                @php
                    $dataGraph = [
                        [
                            'text' => __('Loans and prepayments to companies'),
                            'background' => null,
                            'main' => true,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['1'],
                            'filter' => [
                                'type' => '1'
                            ],
                        ],
                        [
                            'text' => __('Debt securities, including participation units'),
                            'background' => null,
                            'main' => true,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['2'],
                            'filter' => [
                                'type' => '2'
                            ],
                        ],
                        [
                            'text' => __('Equity instruments'),
                            'background' => null,
                            'main' => true,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['3'],
                            'filter' => [
                                'type' => '3'
                            ],
                        ],
                        [
                            'text' => __('Loans to households'),
                            'background' => 'bg-[#EBF9ED]',
                            'main' => true,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['4'] + $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['5'] + $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['6'],
                            'filter' => [
                                'type' => '4,5,6'
                            ],
                        ],
                        [
                            'text' => __('Loans to households secured by residential property'),
                            'background' => null,
                            'main' => false,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['4'],
                            'filter' => [
                                'type' => '4'
                            ],
                        ],
                        [
                            'text' => __('Loans to households for the renovation of buildings'),
                            'background' => null,
                            'main' => false,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['5'],
                            'filter' => [
                                'type' => '5'
                            ],
                        ],
                        [
                            'text' => __('Loans to households for the purchase of cars'),
                            'background' => null,
                            'main' => false,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['6'],
                            'filter' => [
                                'type' => '6'
                            ],
                        ],
                        [
                            'text' => __('Loans to the local public sector'),
                            'background' => 'bg-[#EBF9ED]',
                            'main' => true,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['7'] + $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['8'] + $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['9'],
                            'filter' => [
                                'type' => '7,8,9'
                            ],
                        ],
                        [
                            'text' => __('Loans to the public sector for housing construction'),
                            'background' => null,
                            'main' => false,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['7'],
                            'filter' => [
                                'type' => '7'
                            ],
                        ],
                        [
                            'text' => __('Other Loans to the local public sector'),
                            'background' => null,
                            'main' => false,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['8'],
                            'filter' => [
                                'type' => '8'
                            ],
                        ],
                        [
                            'text' => __('Residential and commercial real estate obtained by acquiring ownership'),
                            'background' => null,
                            'main' => false,
                            'value' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ALIGNED]['9'],
                            'filter' => [
                                'type' => '9'
                            ],
                        ],
                    ];
                    if ($kpi === 'gar') {
                        $dataGraph = array_merge($dataGraph, [
                            [
                                'text' => __('Financial companies'),
                                'background' => 'bg-[#EBF9ED]',
                                'main' => true,
                                'value' => $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::CREDIT_INSTITUTIONS][App\Models\Tenant\GarBtar\BankAssets::ALIGNED] + $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INVESTMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED] + $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::MANAGEMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED] + $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INSURANCE_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                                'filter' => [
                                    'entity' => '1,2,3,4',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Credit institutions'),
                                'background' => null,
                                'main' => false,
                                'value' => $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::CREDIT_INSTITUTIONS][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                                'filter' => [
                                    'entity' => '1',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Investment companies'),
                                'background' => null,
                                'main' => false,
                                'value' => $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INVESTMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                                'filter' => [
                                    'entity' => '2',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Management Companies'),
                                'background' => null,
                                'main' => false,
                                'value' => $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::MANAGEMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                                'filter' => [
                                    'entity' => '3',
                                    'type' => '1,2,3',
                                ],
                            ],
                            [
                                'text' => __('Insurance companies'),
                                'background' => null,
                                'main' => false,
                                'value' => $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INSURANCE_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                                'filter' => [
                                    'entity' => '4',
                                    'type' => '1,2,3',
                                ],
                            ],
                        ]);
                    }
                @endphp
                <x-garbtar.asset_types :dataGraph="$dataGraph" idGraph='aligned_asset' />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10" x-show="option == 'entity'">
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
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::CREDIT_INSTITUTIONS][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INVESTMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::MANAGEMENT_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::INSURANCE_COMPANIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::NON_FINANCIAL_COMPANIES_SUBJECT_NFRD][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::FAMILIES][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                        $data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::GAR][App\Models\Tenant\GarBtar\BankAssets::REAL_STATE][App\Models\Tenant\GarBtar\BankAssets::ALIGNED],
                    ];

                    $entities = [1, 2, 3, 4, 5, 6, 7, 8, 9];

                    if ($kpi === 'btar') {
                        $entities = [1, 2, 3, 4, 5, 5, 6, 7, 8, 9];
                        $labels[4] = __('Others Non-financial companies');
                        array_splice($labels, 4, 0, [__('Non-financial companies subject to the CSRD')]);
                        array_splice($dataGraph, 5, 0, [$data[$stockflow][$business][App\Models\Tenant\GarBtar\BankAssets::BTAR][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD][App\Models\Tenant\GarBtar\BankAssets::ALIGNED]]);
                    }
                @endphp
                <x-garbtar.entity_types :dataGraph="$dataGraph" :labels="$labels" :entities="$entities" idGraph="aligned_entity_type" />
            </div>

            <div class="mt-10" x-show="option == 'main'">
                @php
                    $dataGraph = [
                        'info' => $data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED],
                        'nace' => $data['nace'],
                    ];
                @endphp
                <x-garbtar.main_sectors :data="$dataGraph" colorGraph="#59AB6B" colorLabel="#1F5734" idGraph="aligned_main_type" :itemsInMainSectorsGraph="$itemsInMainSectorsGraph" :itensInMainSectorsTable="$itensInMainSectorsTable" />
            </div>

            <div class="" x-show="option == 'environmental'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                    <div class="w-full">
                        @php
                            $structure = [
                                'labels' => ['01', '02', '03', '04', '05', '06'],
                                'data' => [
                                    array_sum($data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::CCM_ALIGNED_LONG]),
                                    array_sum($data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::CCA_ALIGNED_LONG]),
                                    0,
                                    0,
                                    0,
                                    0,
                                ],
                                'id' => 'aligned_enviroment_type',
                                'bar_color' => ['#3C814F'],
                                'label_color' => ['#39B54A'],
                                'unit' => null,
                                'type' => 'single',
                                'view' => 'y',
                                'currency' => tenant()->get_default_currency,
                                'locale' => auth()->user()->locale,
                            ];
                            $labels = [
                                __('Climate change mitigation'),
                                __('Climate change adaptation'),
                                __('Sustainable use and protection of water and marine resources'),
                                __('Transition to a circular economy'),
                                __('Pollution prevention and control'),
                                __('Protecting and restoring biodiversity and ecosystems'),
                            ];
                            $totalBalance = array_sum($structure['data']);
                            $percentagens = [];
                            foreach($structure['data'] as $item) {
                                $percentagens[] = calculatePercentage($item, $totalBalance, 2);
                            }
                            $totalBalanceP = round(array_sum($percentagens), 0);
                        @endphp
                        <x-charts.donut id="aligned_enviroment_type"
                            data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                            class="w-full mt-5"  x-init="$nextTick(() => { barChartNew('aligned_enviroment_type') });" />
                    </div>

                    <div>
                        <x-tables.table class="!min-w-full">
                            <x-slot name="thead">
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left !w-8">&nbsp;</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Entidade') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-60">{{ __('value') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                            </x-slot>

                            @foreach($labels as $key => $value)
                            <x-tables.tr>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    <div class="text-[#3C814F] text-sm font-extrabold">{{ $structure['labels'][$key] }}</div>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 text-esg8 !py-1.5">{{ $value }}</x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                    <span class="text-xl font-bold text-[#3C814F]">
                                        <x-currency :value="$structure['data'][$key]" />
                                    </span>
                                    <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ $percentagens[$key] }} %</span>
                                </x-tables.td>
                                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                    @if ($structure['data'][$key] > 0)
                                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                        href="{{ route('tenant.garbtar.assets', ['type' => '1,2,3,4,5,6,7,8,9']) }}">
                                        @include('icons.list')
                                    </x-buttons.a-icon-simple>
                                    @else
                                    @include('icons.list')
                                    @endif
                                </x-tables.td>
                            </x-tables.tr>
                            @endforeach

                            <x-tables.tr>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#3C814F]/10"></x-tables.td>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#3C814F]/10">
                                    <p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p>
                                </x-tables.td>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#3C814F]/10 text-right">
                                    <span class="text-xl font-bold text-[#3C814F]">
                                        <x-currency :value="$totalBalance" />
                                    </span>
                                    <span class="text-xs p-1 rounded text-[#3C814F] bg-[#1F5734]/10">{{ $totalBalanceP }} %</span>
                                </x-tables.td>
                                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#3C814F]/10"></x-tables.td>
                            </x-tables.tr>

                        </x-tables-table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-10 mt-10">
                    <div class="">
                        <div class="!h-[150px] !w-[150px] m-auto">
                            @php
                                $transition = calculatePercentage($data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ACTIVITIES_TRANSITIONAL_ADAPTING], $totalBalance, 2);
                                $structure = [
                                    'labels' => [ __('Transition activities') ],
                                    'data' => [$transition, (100 - $transition)],
                                    'id' => 'aligned_transition',
                                    'bar_color' => ['#1F5734', '#f6f6f6'],
                                    'unit' => '%',
                                    'position' => [15, 35],
                                    'single' => true,
                                ]
                            @endphp
                            <x-charts.donut id="aligned_transition"
                                data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                                class="m-auto !h-[150px] !w-[150px] mt-5"  x-init="$nextTick(() => { pieChartNew('aligned_transition') });" />
                        </div>
                    </div>
                    <div class="">
                        <div class="!h-[150px] !w-[150px] m-auto">
                            @php
                                $capacitating = calculatePercentage($data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ACTIVITIES_CAPACITING], $totalBalance, 2);
                                $structure = [
                                    'labels' => [ __('Capacitating activities') ],
                                    'data' => [$capacitating, (100 - $capacitating)],
                                    'id' => 'aligned_capacitating',
                                    'bar_color' => ['#3C814F', '#f6f6f6'],
                                    'unit' => '%',
                                    'position' => [15, 35],
                                    'single' => true,
                                ]
                            @endphp
                            <x-charts.donut id="aligned_capacitating"
                                data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                                class="m-auto !h-[150px] !w-[150px] mt-5"  x-init="$nextTick(() => { pieChartNew('aligned_capacitating') });" />
                        </div>
                    </div>
                    <div class="">
                        <div class="!h-[150px] !w-[150px] m-auto">
                            @php
                                $specialised_credit = calculatePercentage($data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SPECIALIZED_CREDIT][App\Models\Tenant\GarBtar\BankAssets::ALIGNED], $totalBalance, 2);
                                $structure = [
                                    'labels' => [ __('Specialised credit') ],
                                    'data' => [$specialised_credit, (100 - $specialised_credit)],
                                    'id' => 'aligned_specialised',
                                    'bar_color' => ['#59AB6B', '#f6f6f6'],
                                    'unit' => '%',
                                    'position' => [15, 35],
                                    'single' => true,
                                ]
                            @endphp
                            <x-charts.donut id="aligned_specialised"
                                data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                                class="m-auto !h-[150px] !w-[150px] mt-5"  x-init="$nextTick(() => { pieChartNew('aligned_specialised') });" />
                        </div>
                    </div>
                    <div class="col-span-2  grid place-content-center">
                        <x-tables.table class="!min-w-full">
                            <x-slot name="thead">
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('asset') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-60">{{ __('value') }}</x-tables.th>
                                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                            </x-slot>
                            <x-tables.tr>
                                <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                    <div class="w-3 h-3 rounded-full bg-[#1F5734]"></div>
                                </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 ">{{ __('Transition activities') }} </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                    <span class="text-xl font-bold text-[#1F5734]">
                                        <x-currency :value="$data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ACTIVITIES_TRANSITIONAL_ADAPTING]" />
                                    </span>
                                    <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{$transition}} %</span>
                                </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                        href="{{ route('tenant.garbtar.assets', ['type' => '1,2,3,4,5,6,7,8,9']) }}">
                                        @include('icons.list')
                                    </x-buttons.a-icon-simple>
                                </x-tables.td>
                            </x-tables.tr>

                            <x-tables.tr>
                                <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                    <div class="w-3 h-3 rounded-full bg-[#3C814F]"></div>
                                </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 ">{{ __('Capacitating activities') }} </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                    <span class="text-xl font-bold text-[#3C814F]">
                                        <x-currency :value="$data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::ACTIVITIES_CAPACITING]" />
                                    </span>
                                    <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ $capacitating }} %</span>
                                </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                        href="{{ route('tenant.garbtar.assets', ['type' => '1,2,3,4,5,6,7,8,9']) }}">
                                        @include('icons.list')
                                    </x-buttons.a-icon-simple>
                                </x-tables.td>
                            </x-tables.tr>

                            <x-tables.tr>
                                <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                    <div class="w-3 h-3 rounded-full bg-[#59AB6B]"></div>
                                </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 ">{{ __('Specialised credit') }} </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                    <span class="text-xl font-bold text-[#59AB6B]">
                                        <x-currency :value="$data[$stockflow][$business][$kpi][App\Models\Tenant\GarBtar\BankAssets::SPECIALIZED_CREDIT][App\Models\Tenant\GarBtar\BankAssets::ALIGNED]" />
                                    </span>
                                    <span class="text-xs p-1 rounded text-[#59AB6B] bg-[#59AB6B]/20">{{ $specialised_credit }} %</span>
                                </x-tables.td>
                                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                        href="{{ route('tenant.garbtar.assets', ['type' => '1,2,3,4,5,6,7,8,9']) }}">
                                        @include('icons.list')
                                    </x-buttons.a-icon-simple>
                                </x-tables.td>
                            </x-tables.tr>
                        </x-tables-table>
                    </div>
                </div>
            </div>
        </div>
    </x-cards.garbtar.card>
</div>
