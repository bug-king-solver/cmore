<div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    <div class="w-full">
        @php
            try {
                arsort($data['info']);
            } catch (\Error $e) {}
            $index = 1;
            $labelsGraph = [];
            $dataGraph = [];
            $labels = [];
            foreach($data['info'] as $key => $item) {
                if ($index <= $itemsInMainSectorsGraph) {
                    $dataGraph[] = $item;
                    $labelsGraph[] = sprintf("%'02d", $index);
                    $index++;
                } else {
                    $labelsGraph[$itemsInMainSectorsGraph] = sprintf("%'02d", $index);
                    $dataGraph[$itemsInMainSectorsGraph] = round(($dataGraph[$itemsInMainSectorsGraph] ?? 0) + $item, 2);
                }
                $labels[] = $key;
            }
            $structure = [
                'labels' => $labelsGraph,
                'data' => $dataGraph,
                'id' => $idGraph,
                'bar_color' => [$colorGraph],
                'label_color' => [$colorLabel],
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
        <x-charts.donut id="{{ $idGraph }}"
            data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
            class="w-full mt-5"  x-init="$nextTick(() => { barChartNew('{{ $idGraph }}') });" />
    </div>

    <div>
        <x-tables.table class="!min-w-full">
            <x-slot name="thead">
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left !w-8">&nbsp;</x-tables.th>
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Entidade') }}</x-tables.th>
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-60">{{ __('value') }}</x-tables.th>
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
            </x-slot>
            @foreach($dataGraph as $key => $value)
            <x-tables.tr>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                    <div class="text-[{{ $colorGraph }}] text-sm font-extrabold">{{ $labelsGraph[$key] }}</div>
                </x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 text-esg8 !py-1.5">
                    @if($key < $itemsInMainSectorsGraph)
                    {{ $data['nace'][$labels[$key]]['name'] ?? $labels[$key] }}
                    @else
                    {{ __('Other Sectors') }}
                    @endif
                </x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                    <span class="text-xl font-bold text-[{{ $colorGraph }}]">
                        <x-currency :value="$value" />
                    </span>
                    <span class="text-xs p-1 rounded text-[{{ $colorGraph }}] bg-[{{ $colorGraph }}]/20">{{ $percentagens[$key] }} %</span>
                </x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                    @if($key < $itemsInMainSectorsGraph && ($data['nace'][$labels[$key]]['code'] ?? false))
                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                        href="{{ route('tenant.garbtar.assets', ['nace' => $data['nace'][$labels[$key]]['code']]) }}">
                        @include('icons.list')
                    </x-buttons.a-icon-simple>
                    @endif
                </x-tables.td>
            </x-tables.tr>
            @endforeach

            <x-tables.tr>
                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10"></x-tables.td>
                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10">
                    <p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p>
                </x-tables.td>
                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10 text-right">
                    <span class="text-xl font-bold text-esg6">
                        <x-currency :value="$totalBalance" />
                    </span>
                    <span class="text-xs p-1 rounded text-esg6 bg-esg5/20">{{ $totalBalanceP }} %</span>
                </x-tables.td>
                <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[#39B54A]/10"></x-tables.td>
            </x-tables.tr>

        </x-tables-table>
    </div>
</div>

<div class="mt-10">
    <x-tables.table class="!min-w-full">
        <x-slot name="thead">
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left !w-8">#</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase !w-10">{{ __('code') }}</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('Name') }}</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-36">{{ __('value') }}</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase !w-10">%</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
        </x-slot>
        @for($i = 0; ($i < $itensInMainSectorsTable && sizeof($labels) > $i); $i++)
            <x-tables.tr>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-2">{{ ($i + 1) }}</x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-2">
                    @if($data['nace'][$labels[$i]]['code'] ??false )
                        <span class="px-2 py-1 bg-[{{ $colorGraph }}]/20 text-esg8 rounded">{{ $data['nace'][$labels[$i]]['code'] ?? $labels[$i] }}</span>
                    @endif
                </x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-2"> {{ $data['nace'][$labels[$i]]['name'] ?? $labels[$i] }} </x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-2 text-right">
                    <span class="text-base font-bold text-esg16">
                        <x-currency :value="$data['info'][$labels[$i]]" />
                    </span>
                </x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-2 text-right">
                    <span class="text-base font-extrabold text-[{{ $colorGraph }}]">{{ calculatePercentage($data['info'][$labels[$i]], $totalBalance, 2) }}%</span>
                </x-tables.td>
                <x-tables.td class="text-sm !border-b-esg7/40 !py-2">
                    @if($data['nace'][$labels[$i]]['code'] ?? false)
                    <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                        href="{{ route('tenant.garbtar.assets', ['nace' => $data['nace'][$labels[$i]]['code']]) }}">
                        @include('icons.list')
                    </x-buttons.a-icon-simple>
                    @endif
                </x-tables.td>
            </x-tables.tr>
        @endfor

    </x-tables-table>
</div>
