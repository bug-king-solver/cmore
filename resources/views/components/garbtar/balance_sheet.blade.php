<div class="mt-10 {{ $graphOption != 'balance' ? 'hidden' : '' }}">
    <x-cards.garbtar.card text="{{ json_encode([__('Balance sheet total')]) }}"
        subpoint="{{ json_encode([['text' => __('Set of all the institutions assets, structured according to ESG classification needs.')]]) }}"
        class="!h-auto" type="grid" contentplacement="none">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="w-full">
                @php
                    $labels = [
                        __('Assets excluded from the ratios'),
                        __('Own real estate'),
                        __('Households'),
                        __('Local public sector'),
                        __('Companies'),
                        __('Excluded from the ') . ($kpi == 'gar' ? __('GAR') : __('BTAR')),
                    ];
                    $filters = [
                        [
                            'type' => '14,15,16',
                        ],
                        [
                            'type' => '9',
                        ],
                        [
                            'type' => '4,5,6',
                        ],
                        [
                            'type' => '7,8',
                        ],
                        [
                            'type' => '1,2,3',
                            'entity' => '1,2,3,4',
                        ],
                    ];
                    $structure = [
                        'labels' => [
                            '01', '02', '03', '04', '05', '06'
                        ],
                        'data' => [
                            $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR],
                            $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::REAL_STATE],
                            $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES],
                            $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL],
                            $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES],
                        ],
                        'id' => 'balance_total',
                        'bar_color' => ['#39B54A'],
                        'label_color' => ['#1F5734'],
                        'unit' => null,
                        'type' => 'single',
                        'view' => 'y',
                        'currency' => tenant()->get_default_currency,
                        'locale' => auth()->user()->locale,
                    ];
                    if ($kpi === 'gar') {
                        $structure['data'][] = ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD] + $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR]);
                        $filters[] = [
                            'entity' => '5',
                            'type' => '1,2,3',
                        ];
                    } else {
                        $structure['data'][4] = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES] + $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD];
                        $structure['data'][] = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR];
                        $filters[4] = [
                            'type' => '1,2,3',
                            'entity' => '1,2,3,4,5',
                        ];
                        $filters[] = [
                            'type' => '10,11,12,13',
                        ];
                    }

                    $totalPie = array_sum($structure['data']);
                    $totalBalance = array_sum($structure['data']);
                    $percentagens = [];
                    foreach($structure['data'] as $item) {
                        $percentagens[] = calculatePercentage($item, $totalBalance, 2);
                    }
                    $totalBalanceP = round(array_sum($percentagens), 0);
                @endphp
                <x-charts.donut id="balance_total" data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                    class="w-full mt-5" x-init="$nextTick(() => { barChartNew('balance_total') });" />
            </div>

            <x-tables.table class="!min-w-full">
                <x-slot name="thead">
                    <x-tables.th
                        class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                    <x-tables.th
                        class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('ativo') }}</x-tables.th>
                    <x-tables.th
                        class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-56">{{ __('value') }}</x-tables.th>
                    <x-tables.th
                        class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                </x-slot>

                @foreach($labels as $index => $label)
                <x-tables.tr>
                    <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                        <div class="text-esg6 text-sm font-extrabold">{{ $structure['labels'][$index] }}</div>
                    </x-tables.td>
                    <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 ">
                        <div class="flex items-center gap-2">
                            {{ $label }}
                        </div>
                    </x-tables.td>
                    <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                        <span class="text-xl font-bold text-esg6">
                            <x-currency :value="$structure['data'][$index]" />
                        </span>
                        <span class="text-xs p-1 rounded text-esg6 bg-esg5/20">{{ $percentagens[$index] }}%</span>
                    </x-tables.td>
                    <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                        <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                            href="{{ route('tenant.garbtar.assets', $filters[$index]) }}">
                            @include('icons.list')
                        </x-buttons.a-icon-simple>
                    </x-tables.td>
                </x-tables.tr>
                @endforeach

                <x-tables.tr>
                    <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/5"></x-tables.td>
                    <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/5">
                        <p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p>
                    </x-tables.td>
                    <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/5 text-right">
                        <span class="text-xl font-bold text-[#1F5734]">
                            <x-currency :value="$totalBalance" />
                        </span>
                        <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $totalBalanceP }}%</span>
                    </x-tables.td>
                    <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/5"></x-tables.td>
                </x-tables.tr>

            </x-tables-table>
        </div>

        <div class="mt-10">

            @php
                $totalBalance =  array_sum($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]);
                $totalBalanceP =  array_sum($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_PERCENT]);
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
                    __('Derivatives'),
                    __('Interbank loans on demand'),
                    __('Cash and cash equivalent assets'),
                    __('Other assets (e.g. goodwill, merchandise, etc.)'),
                ];
            @endphp
            <div>
                <x-tables.table class="!min-w-full">
                    <x-slot name="thead">
                        <x-tables.th
                            class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('ativo') }}</x-tables.th>
                        <x-tables.th
                            class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-56">{{ __('value') }}</x-tables.th>
                        <x-tables.th
                            class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                    </x-slot>
                    @foreach($labels as $key => $item)
                    <x-tables.tr>
                        <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                            <div class="flex items-center gap-2">
                                {{ $item }}
                            </div>
                        </x-tables.td>
                        <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                            <span class="text-xl font-bold text-esg6">
                                <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE][($key + 1)]" />
                            </span>
                            <span class="text-xs p-1 rounded text-esg6 bg-esg5/20">
                                {{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_PERCENT][($key + 1)] }} %
                            </span>
                        </x-tables.td>
                        <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                            <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                href="{{ route('tenant.garbtar.assets', ['type' => ($key + 1) ]) }}">
                                @include('icons.list')
                            </x-buttons.a-icon-simple>
                        </x-tables.td>
                    </x-tables.tr>
                    @endforeach

                    <x-tables.tr>
                        <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#1F573426]">
                            <p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p>
                        </x-tables.td>
                        <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#1F573426] text-right">
                            <span class="text-xl font-bold text-[#1F5734]">
                                <x-currency :value="$totalBalance" />
                            </span>
                            <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F573426]/20">
                                {{ $totalBalanceP }}%
                            </span>
                        </x-tables.td>
                        <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#1F573426]"></x-tables.td>
                    </x-tables.tr>
                    </x-tables-table>
            </div>
        </div>
    </x-cards.garbtar.card>
</div>
