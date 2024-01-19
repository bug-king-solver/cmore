<div class="mt-10 {{ $graphOption != 'relevant' ? 'hidden' : '' }}">

    @php
        $subtext = null;
        if ($kpi == 'gar' && $stockflow == 'stock') {
            $subtext = json_encode([['text' => __('All the assets on the institution`s balance sheet, from which are excluded only those that under regulatory terms should not be used either in the numerator or in the denominator of the calculation of the GAR and BTAR.')]]);
        } elseif ($kpi == 'gar' && $stockflow == 'flow') {
            $subtext = json_encode([['text' => __('All the assets on the institution`s balance sheet, from which are excluded only those that under regulatory terms should not be used either in the numerator or in the denominator of the calculation of the GAR and BTAR. This "Flow" option also excludes from the calculation base all assets that are not new because they did not originate in the reporting period.')]]);
        } elseif ($kpi == 'btar' && $stockflow == 'stock') {
            $subtext = json_encode([['text' => __('Set of all the assets on the institution`s balance sheet from which are excluded only those that under regulatory terms should not be used either in the numerator or in the denominator of the calculation of the GAR and BTAR.')]]);
        } elseif ($kpi == 'btar' && $stockflow == 'flow') {
            $subtext = json_encode([['text' => __('Set of all the assets on the institution`s balance sheet from which are excluded only those that under regulatory terms should not be used either in the numerator or in the denominator of the calculation of the GAR and BTAR. This "Flow" option also excludes from the calculation base all assets that are not new because they did not originate in the reporting period.')]]);
        } else {
            $subtext = json_encode([['text' => __('All the assets on the institution`s balance sheet, from which are excluded only those that under regulatory terms should not be used either in the numerator or in the denominator of the calculation of the GAR and BTAR. This `Flow` option also excludes from the calculation base all assets that are not new because they did not originate in the reporting period.')]]);
        }
    @endphp

    <x-cards.garbtar.card text="{{ json_encode([__('Relevant for ratio calculations')]) }}" subpoint="{{ $subtext }}"
        class="!h-auto" type="grid" contentplacement="none">
        <div class="" x-data="{ option: false }">

            <div class="">
                <div class="inline-flex rounded-md shadow-sm bg-esg6/10 p-1" role="group">
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = !option" :class="!option ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Simplified analysis') }}
                    </button>
                    <button type="button" class="px-4 py-1 text-base !font-medium rounded-md"
                        x-on:click="option = !option" :class="option ? 'text-esg6 bg-white shadow' : 'text-esg8'">
                        {{ __('Full analysis') }}
                    </button>
                </div>
            </div>

            <div class="flex place-content-center gap-20 pt-10">
                <div>
                    <div class="!h-[250px] !w-[250px] m-auto">
                        @php
                            $labels = [
                                __('Business assets excluded from the numerator'),
                                __('Others excluded from the numerator'),
                                __('Business assets covered'),
                                __('Assets of households (covered)'),
                                __('Others Covered'),
                            ];
                            $dataGraph = [
                                $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU] + $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU],
                                $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR],
                                $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES],
                                $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES],
                                ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::REAL_STATE]) + ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL])
                            ];
                            $filters = [
                                [
                                    'type' => '1,2,3',
                                    'entity' => '5',
                                ],
                                [
                                    'type' => '10,11,12,13',
                                ],
                                [
                                    'type' => '1,2,3',
                                    'entity' => '1,2,3,4'
                                ],
                                [
                                    'type' => '4,5,6',
                                ],
                                [
                                    'type' => '7,8,9',
                                ],
                            ];
                            if ($kpi === 'btar') {
                                array_shift($labels);
                                array_shift($dataGraph);
                                $dataGraph[1] = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES] + $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD];
                            }
                            $structure = [
                                'labels' => $labels,
                                'data' => $dataGraph,
                                'id' => 'ratio_calculations',
                                'bar_color' => ['#1F5734', '#3C814F', '#59AB6B', '#76D586', '#A1E0A9'],
                                'unit' => 'M€',
                                'position' => [40, 65],
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
                        <x-charts.donut id="ratio_calculations"
                            data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
                            class="m-auto !h-[250px] !w-[250px] mt-5" x-init="$nextTick(() => { pieChartNew('ratio_calculations') });" />
                    </div>
                </div>

                <div class="grid place-content-center">
                    <x-tables.table class="!min-w-full">
                        <x-slot name="thead">
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('ativo') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-60">{{ __('value') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                        </x-slot>
                        <x-tables.tr>
                            <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                <div class="w-3 h-3 rounded-full bg-[#1F5734]"></div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 ">
                                <div class="flex items-center gap-2">
                                    {{ $labels[0] }}
                                </div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                <span class="text-xl font-bold text-[#1F5734]">
                                    <x-currency :value="$structure['data'][0]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $percentagens[0] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', $filters[0]) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                <div class="w-3 h-3 rounded-full bg-[#3C814F]"></div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <div class="flex items-center gap-2">
                                    {{ $labels[1] }}
                                </div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                <span class="text-xl font-bold text-[#3C814F]">
                                    <x-currency :value="$structure['data'][1]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ $percentagens[1] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', $filters[1]) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                <div class="w-3 h-3 rounded-full bg-[#59AB6B]"></div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <div class="flex items-center gap-2">
                                    {{ $labels[2] }}
                                </div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                <span class="text-xl font-bold text-[#59AB6B]">
                                    <x-currency :value="$structure['data'][2]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#59AB6B] bg-[#59AB6B]/20">{{ $percentagens[2] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', $filters[2]) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                <div class="w-3 h-3 rounded-full bg-[#76D586]"></div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <div class="flex items-center gap-2">
                                    {{ $labels[3] }}
                                </div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                <span class="text-xl font-bold text-[#59AB6B]">
                                    <x-currency :value="$structure['data'][3]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#76D586] bg-[#76D586]/20">{{ $percentagens[3] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', $filters[3]) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        @if($kpi === 'gar')
                        <x-tables.tr>
                            <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                                <div class="w-3 h-3 rounded-full bg-[#A1E0A9]"></div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <div class="flex items-center gap-2">
                                    {{ $labels[4] }}
                                </div>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                                <span class="text-xl font-bold text-[#A1E0A9]">
                                    <x-currency :value="$structure['data'][4]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#A1E0A9] bg-[#A1E0A9]/20">{{ $percentagens[4] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', $filters[4]) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>
                        @endif
                    </x-tables.table>
                </div>
            </div>

            <div class="mt-10" x-show="option">
                <div>
                    <x-tables.table class="!min-w-full">
                        <x-slot name="thead">
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('asset') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-60">{{ __('value') }}</x-tables.th>
                            <x-tables.th
                                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
                        </x-slot>

                        @if($kpi === 'gar')
                        <x-tables.tr>
                            <x-tables.td colspan="3"
                                class="!py-1.5 text-sm font-black !text-[#1F5734] bg-[#1F5734]/20 uppercase">
                                {{ __('Business assets excluded from the numerator') }}
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Loans and prepayments to companies') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#1F5734]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED]['1'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED]['1'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] ?? 0 }}%</span>
                                </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '1', 'entity' => '5']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Debt securities, including participation units') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#1F5734]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED]['2'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]  ?? 0" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED]['2'][App\Models\Tenant\GarBtar\BankAssets::PERCENT]  ?? 0 }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '2', 'entity' => '5']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5"> {{ __('Equity instruments') }}
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#1F5734]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED]['3'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED]['3'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] ?? 0 }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '3', 'entity' => '5']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>
                        @endif


                        <x-tables.tr>
                            <x-tables.td colspan="3"
                                class="!py-1.5 text-sm font-black !text-[#3C814F] bg-[#3C814F]/20 uppercase">
                                {{ __('Others excluded from the numerator') }}
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5"> {{ __('Derivatives') }}
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#3C814F]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['10'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['10'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '10']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5"> {{ __('Interbank demand loans') }}
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#3C814F]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['11'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['11'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '11']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Cash and cash equivalent assets') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#3C814F]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['12'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['12'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '12']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Other assets (e.g. goodwill, commodities, etc.)') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#3C814F]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['13'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED]['13'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '13']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>





                        <x-tables.tr>
                            <x-tables.td colspan="3"
                                class="!py-1.5 text-sm font-black !text-[#59AB6B] bg-[#59AB6B]/20 uppercase">
                                {{ __('Business assets covered') }}
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Loans and prepayments to companies') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#59AB6B]">
                                    @if($kpi === 'gar')
                                        <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['1'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                    @else
                                    @php
                                    $loans = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['1'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] +
                                        ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU_DETAILED]['1'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0) +
                                        ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_DETAILED]['1'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0);
                                    @endphp
                                    <x-currency :value="$loans" />
                                    @endif
                                </span>
                                <span class="text-xs p-1 rounded text-[#59AB6B] bg-[#59AB6B]/20">
                                    @if($kpi === 'gar')
                                    {{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['1'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}
                                    @else
                                    {{ calculatePercentage($loans, $dataGraph[1], 2) }}
                                    @endif
                                    %
                                </span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '1', 'entity' => '1,2,3,4']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Debt securities, including participation units') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#59AB6B]">
                                    @if($kpi === 'gar')
                                        <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['2'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                    @else
                                    @php
                                    $loans = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['2'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] +
                                        ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU_DETAILED]['2'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0) +
                                        ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_DETAILED]['2'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0);
                                    @endphp
                                        <x-currency :value="$loans" />
                                    @endif
                                </span>
                                <span class="text-xs p-1 rounded text-[#59AB6B] bg-[#59AB6B]/20">
                                    @if($kpi === 'gar')
                                    {{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['2'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}
                                    @else
                                    {{ calculatePercentage($loans, $dataGraph[1], 2) }}
                                    @endif
                                    %
                                </span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '2', 'entity' => '1,2,3,4']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5"> {{ __('Equity instruments') }}
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#59AB6B]">
                                    @if($kpi === 'gar')
                                        <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['3'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                    @else
                                    @php
                                    $loans = $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['3'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] +
                                        ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EU_DETAILED]['3'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0) +
                                        ($data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_DETAILED]['3'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE] ?? 0);
                                    @endphp
                                        <x-currency :value="$loans" />
                                    @endif
                                </span>
                                <span class="text-xs p-1 rounded text-[#59AB6B] bg-[#59AB6B]/20">
                                    @if($kpi === 'gar')
                                    {{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::COMPANIES_DETAILED]['3'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}
                                    @else
                                    {{ calculatePercentage($loans, $dataGraph[1], 2) }}
                                    @endif
                                    %
                                </span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '3', 'entity' => '1,2,3,4']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>




                        <x-tables.tr>
                            <x-tables.td colspan="3"
                                class="!py-1.5 text-sm font-black !text-[#76D586] bg-[#76D586]/20 uppercase">
                                {{ __('Assets of households (covered)') }}
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Loans to households secured by residential property') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#76D586]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES_DETAILED]['4'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#76D586] bg-[#76D586]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES_DETAILED]['4'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '4']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Loans to households for the renovation of buildings') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#76D586]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES_DETAILED]['5'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#76D586] bg-[#76D586]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES_DETAILED]['5'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '5']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Loans to households for the purchase of cars') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#76D586]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES_DETAILED]['6'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#76D586] bg-[#76D586]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::FAMILIES_DETAILED]['6'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '6']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>




                        <x-tables.tr>
                            <x-tables.td colspan="3"
                                class="!py-1.5 text-sm font-black !text-[#A1E0A9] bg-[#A1E0A9]/20 uppercase">
                                {{ __('Others Covered') }}
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Loans to the public sector for housing construction') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#A1E0A9]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['7'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#A1E0A9] bg-[#A1E0A9]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['7'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '7']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Other Loans to the local public sector') }} </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#A1E0A9]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['8'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#A1E0A9] bg-[#A1E0A9]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['8'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '8']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>

                        <x-tables.tr>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                {{ __('Residential and commercial real estate obtained by acquiring ownership') }}
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                                <span class="text-xl font-bold text-[#A1E0A9]">
                                    <x-currency :value="$data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['9'][App\Models\Tenant\GarBtar\BankAssets::ABSOLUTE_VALUE]" />
                                </span>
                                <span class="text-xs p-1 rounded text-[#A1E0A9] bg-[#A1E0A9]/20">{{ $data[$stockflow][App\Models\Tenant\GarBtar\BankAssets::BANK_BALANCE][App\Models\Tenant\GarBtar\BankAssets::PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED]['9'][App\Models\Tenant\GarBtar\BankAssets::PERCENT] }}%</span>
                            </x-tables.td>
                            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                                    href="{{ route('tenant.garbtar.assets', ['type' => '9']) }}">
                                    @include('icons.list')
                                </x-buttons.a-icon-simple>
                            </x-tables.td>
                        </x-tables.tr>
                        </x-tables-table>
                </div>
            </div>


        </div>
    </x-cards.garbtar.card>
</div>
