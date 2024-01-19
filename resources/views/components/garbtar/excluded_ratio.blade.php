<div class="mt-10 {{ $graphOption != 'notaligned4' ? 'hidden' : '' }}">
    <x-cards.garbtar.card text="{{ json_encode([__('Excluded from ratios')]) }}"
        subpoint="{{ json_encode([['text' => __('Set of all the institution`s assets that are not taken into account for the purposes of the numerator or denominator of the ratios. It only includes credits over sovereign entities, exposures to central banks and the Negotiation Portfolio.')]]) }}"
        class="!h-auto" type="grid" contentplacement="none">
        <div class="">
            <div class="flex place-content-center gap-20 pt-10">
                <div>
                    <div class="!h-[250px] !w-[250px] m-auto">
                        @php
                            $structure = [
                                'labels' => [__('Credits over sovereign entities'), __('Central bank exposures'), __('Negotiation portfolio')],
                                'data' => [
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['14'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['15'],
                                    $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE]['16']
                                ],
                                'id' => 'excluded_ratio',
                                'bar_color' => ['#1F5734', '#3C814F', '#59AB6B'],
                                'unit' => 'M€',
                                'position' => [35, 55],
                                'currency' => tenant()->get_default_currency,
                                'locale' => auth()->user()->locale,
                            ];

                            $filters = [
                                ['type' => '14'],
                                ['type' => '15'],
                                ['type' => '16'],
                            ];

                            if (($kpi == 'gar' || $kpi == 'btar') && $stockflow == 'flow') {
                                $structure['labels'][] = __('Stock created before the reporting period');
                                $structure['data'][] = 0;
                                $structure['bar_color'][] = '#76D586';
                                $filters[] = [];
                            }
                            $totalBalance = array_sum($structure['data']);

                            $percentagens = [];
                            foreach($structure['data'] as $item) {
                                $percentagens[] = calculatePercentage($item, $totalBalance, 2);
                            }
                            $totalBalanceP = round(array_sum($percentagens), 0);
                        @endphp
                        <x-charts.donut id="excluded_ratio"
                            data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                            class="m-auto !h-[250px] !w-[250px] mt-5" x-init="$nextTick(() => { pieChartNew('excluded_ratio') });" />
                    </div>
                </div>

                <div class="grid place-content-center">
                    <x-tables.table class="!min-w-full">
                        <x-slot name="thead">
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('asset') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-56">{{ __('value') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                        </x-slot>
                        @foreach($structure['labels'] as $index => $value)
                        <x-tables.tr>
                            <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                <div class="w-3 h-3 rounded-full bg-[{{ $structure['bar_color'][$index] }}]"></div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 ">
                                <div class="flex items-center gap-2">
                                    {{ $value }}
                                </div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                <span class="text-xl font-bold text-[{{ $structure['bar_color'][$index] }}]">
                                    <x-currency :value="$structure['data'][$index]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[{{ $structure['bar_color'][$index] }}] bg-[{{ $structure['bar_color'][$index] }}]/20">{{ $percentagens[$index] }} %</span>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', $filters[$index]) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>
                        @endforeach
                    </x-tables-table>
                </div>
            </div>
        </div>
    </x-cards.garbtar.card>
</div>
