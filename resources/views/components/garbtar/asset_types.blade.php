<div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    <div class="w-full">
        @php
            $labelsGraph = [];
            $valuesGraph = [];
            $index = 0;
            foreach($dataGraph as $item) {
                if ($item['main']) {
                    $index++;
                    $labelsGraph[] = sprintf("%'02d", $index);
                    $valuesGraph[] = round($item['value'], 2);
                }
            }
            $structure = [
                'labels' => $labelsGraph,
                'data' => $valuesGraph,
                'id' => $idGraph,
                'bar_color' => [$colorGraph ?? '#39B54A'],
                'label_color' => [$colorLabel ?? '#1F5734'],
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
        <x-charts.donut id="{{ $idGraph }}" data-json="{{ json_encode($structure, JSON_UNESCAPED_UNICODE) }}"
            class="w-full mt-5" x-init="$nextTick(() => { barChartNew('{{ $idGraph }}') });" />
    </div>
    <x-tables.table class="!min-w-full">
        <x-slot name="thead">
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('asset') }}</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-60">{{ __('value') }}</x-tables.th>
            <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
        </x-slot>
        @php $index = 0; @endphp
        @foreach($dataGraph as $item)
        @if(!$item['main'])
            @continue
        @endif
        <x-tables.tr>
            <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                <div class="text-[{{ $colorGraph ?? '#39B54A'}}] text-sm font-extrabold">{{ $labelsGraph[$index] }}</div>
            </x-tables.td>
            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 ">{{ $item['text'] }} </x-tables.td>
            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40 text-right">
                <span class="text-xl font-bold text-[{{ $colorGraph ?? '#39B54A'}}]">
                    <x-currency :value="$item['value']" />
                </span>
                <span class="text-xs p-1 rounded text-[{{ $colorGraph ?? '#39B54A'}}] bg-[{{ $colorGraph ?? '#39B54A'}}]/20">{{ $percentagens[$index] }} %</span>
            </x-tables.td>
            <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                    href="{{ route('tenant.garbtar.assets', $item['filter']) }}">
                    @include('icons.list')
                </x-buttons.a-icon-simple>
            </x-tables.td>
        </x-tables.tr>
        @php $index += 1; @endphp
        @endforeach
        <x-tables.tr>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#39B54A'}}]/5"></x-tables.td>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#39B54A'}}]/5">
                <p class="font-black text-esg8 uppercase text-right text-sm">{{ __('Total') }}</p>
            </x-tables.td>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#39B54A'}}]/5 text-right">
                <span class="text-xl font-bold text-[#1F5734]">
                    <x-currency :value="$totalBalance" />
                </span>
                <span class="text-xs p-1 rounded text-[#1F5734] bg-[#1F5734]/20">{{ $totalBalanceP }}%</span>
            </x-tables.td>
            <x-tables.td class=" !py-1.5 text-sm !border-b-esg7/40 bg-[{{ $colorGraph ?? '#39B54A'}}]/5"></x-tables.td>
        </x-tables.tr>
    </x-tables-table>
</div>
<div class="w-full mt-10">
    <x-tables.table class="!min-w-full">
        <x-slot name="thead">
            <x-tables.th
                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase">{{ __('asset') }}</x-tables.th>
            <x-tables.th
                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left uppercase w-60">{{ __('value') }}</x-tables.th>
            <x-tables.th
                class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left w-4">&nbsp;</x-tables.th>
        </x-slot>

        @foreach($dataGraph as $item)
        @php
            $background = '';
            $paddingX = '!px-4';
            if (isset($item['background'])) {
                $background = ' ' . $item['background'];
            }
            if (!$item['main']) {
                $paddingX = '!pl-8';
            }
        @endphp
        <x-tables.tr>
            <x-tables.td class="text-sm !border-b-esg7/40 !py-2 {{ $paddingX }}{{ $background }}"> {{ $item['text'] }}</x-tables.td>
            <x-tables.td class="text-sm !border-b-esg7/40 !py-2 !px-4{{ $background }} text-right">
                @if(!isset($item['background']))
                <span class="text-xl font-bold text-[#3C814F]">
                    <x-currency :value="$item['value']" />
                </span>
                <span class="text-xs p-1 rounded text-[#3C814F] bg-[#3C814F]/20">{{ calculatePercentage($item['value'], $totalBalance, 2) }}%</span>
                @endif
            </x-tables.td>
            <x-tables.td class="text-sm !border-b-esg7/40 !py-2 !px-4{{ $background }}">
                @if(!isset($item['background']))
                <x-buttons.a-icon-simple class="text-sm p-0 border-none font-bold text-esg29 hover:bg-esg7/50"
                    href="{{ route('tenant.garbtar.assets', $item['filter']) }}">
                    @include('icons.list')
                </x-buttons.a-icon-simple>
                @endif
            </x-tables.td>
        </x-tables.tr>
        @endforeach

    </x-tables-table>
</div>
