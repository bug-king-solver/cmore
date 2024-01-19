<div class="w-full">
    @php
        $labelsGraph = [];
        $index = 0;
        foreach($dataGraph as $item) {
            $index++;
            $labelsGraph[] = sprintf("%'02d", $index);
        }

        $structure = [
            'labels' => $labelsGraph,
            'data' => $dataGraph,
            'id' => $idGraph,
            'bar_color' => [$colorGraph ?? '#1F5734'],
            'label_color' => [$labelColor ?? '#39B54A'],
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

        @foreach($labels as $key => $value)
        <x-tables.tr>
            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                <div class="text-[{{ $colorGraph ?? '#1F5734'}}] text-sm font-extrabold">{{ $structure['labels'][$key] }}</div>
            </x-tables.td>
            <x-tables.td class="text-sm !border-b-esg7/40 text-esg8 !py-1.5">{{ $value }}</x-tables.td>
            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5 text-right">
                <span class="text-xl font-bold text-[{{ $colorGraph ?? '#1F5734'}}]">
                    <x-currency :value="$structure['data'][$key]" />
                </span>
                <span class="text-xs p-1 rounded text-[{{ $colorGraph ?? '#1F5734'}}] bg-[{{ $colorGraph ?? '#1F5734'}}]/20">{{ $percentagens[$key] }} %</span>
            </x-tables.td>
            <x-tables.td class="text-sm !border-b-esg7/40 !py-1.5">
                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                    href="{{ route('tenant.garbtar.assets', ['entity' => $entities[$key]]) }}">
                    @include('icons.list')
                </x-buttons.a-icon-simple>
            </x-tables.td>
        </x-tables.tr>
        @endforeach

        <x-tables.tr>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#1F5734'}}]/10"></x-tables.td>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#1F5734'}}]/10">
                <p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p>
            </x-tables.td>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#1F5734'}}]/10 text-right">
                <span class="text-xl font-bold text-[{{ $colorGraph ?? '#1F5734'}}]">
                    <x-currency :value="$totalBalance" />
                </span>
                <span class="text-xs p-1 rounded text-[{{ $colorGraph ?? '#1F5734'}}] bg-[{{ $colorGraph ?? '#1F5734'}}]/10">{{ $totalBalanceP }} %</span>
            </x-tables.td>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#1F5734'}}]/10"></x-tables.td>
        </x-tables.tr>

    </x-tables-table>
</div>