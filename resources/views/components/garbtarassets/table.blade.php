@props([
    'letters' => [],
    'unit' => '',
    'firstTitle' => '',
    'secondTitle' => '',
    'totalAtFirstColumn' => '',
    'totalAtLastColumn' => '',
    'data' => [],
    'frameworkList' => [],
    'framework' => 'ccm',
    'showTotalAtFirst' => false,
    'showTotalAtLast' => false,
    'optionsKpi' => [],
    'kpi' => 'stock',
    'formatToCurrency' => true,
    'numberVisibleLettersColumns' => 0,
    'unitValue' => '%',
    'modelKpi' => 'kpi',
    'modelFramework' => 'framework',
])
@php
$showTotalAtFirst = !empty($totalAtFirstColumn);
$showTotalAtLast = !empty($totalAtLastColumn);
@endphp
<x-tables.table class="mt-6">
    <x-slot name="thead">
        <x-tables.tr>
            <x-tables.td class="!py-1 !px-2 w-[4%]"></x-tables.td>
            <x-tables.td class="!py-1 !px-2 w-[21%]"></x-tables.td>
            @foreach($letters as $letter)
			<x-tables.td class="!py-1 !px-2 w-[12.5%] border text-center">{{ $letter }}</x-tables.td>
			@endforeach
		</x-tables.tr>
		<x-tables.tr>
            <x-tables.td class="!py-1 !px-2 border text-center" colspan="2" rowspan="5">{{ $unit }}</x-tables.td>
            <x-tables.td class="!py-1 !px-2 border" colspan="6">
                <x-inputs.select id="{{ $modelKpi }}" input="select" class="!py-1 !px-2 !border-0 !text-base p-1 text-center" :extra="['options' => $optionsKpi, 'show_blank_opt' => false ]" />
            </x-tables.td>
		</x-tables.tr>
		<x-tables.tr>
            @if($showTotalAtFirst)
			<x-tables.td class="!py-1 !px-2 border text-center" rowspan="4">{{ $totalAtFirstColumn }}</x-tables.td>
            @endif
			<x-tables.td class="!py-1 !px-2 border" colspan="{{$numberVisibleLettersColumns}}">
                <x-inputs.select id="{{ $modelFramework }}" input="select" class="!border-0 !text-base p-1 text-center" :extra="['options' => $frameworkList, 'show_blank_opt' => false ]" />
            </x-tables.td>
		</x-tables.tr>
		<x-tables.tr>
			<x-tables.td class="!py-1 !px-2 border border-r-0" rowspan="3"></x-tables.td>
			<x-tables.td class="!py-1 !px-2 border border-l-0 text-center" colspan="4">{{ $firstTitle }}</x-tables.td>
            @if($showTotalAtLast)
			<x-tables.td class="!py-1 !px-2 border text-center" rowspan="3">{{ $totalAtLastColumn }}</x-tables.td>
            @endif
		</x-tables.tr>
		<x-tables.tr>
			<x-tables.td class="!py-1 !px-2 border border-r-0" rowspan="2"></x-tables.td>
			<x-tables.td class="!py-1 !px-2 border border-l-0 text-center" colspan="3">{{ $secondTitle }}</x-tables.td>
		</x-tables.tr>
		<x-tables.tr>
			<x-tables.td class="!py-1 !px-2 border text-center">{{ __('Specialised lending') }}</x-tables.td>
			<x-tables.td class="!py-1 !px-2 border text-center">{{ __('Transitional') }}</x-tables.td>
			<x-tables.td class="!py-1 !px-2 border text-center">{{ __('Enabling') }}</x-tables.td>
		</x-tables.tr>
    </x-slot>
    @foreach($data as $item)
    <x-tables.tr wire:key="{{ $item['id'] }}">
        @php
        $classBg = '';
        if (isset($item['background'])) {
            $classBg = 'bg-[' . $item['background'] . ']';
        }
        @endphp

        @if(($item['singleRow']))
        <x-tables.td colspan="{{ sizeof($letters) + 2 }}" class="text-[#444444] !py-1 !px-2  border border-[#E1E6EF] {{ ($item['title']['class'] ?? '') }} {{ $classBg }}">
            {{ $item['title']['text'] }}
            @if(isset($item['title']['subtitle']))
            <span @if(isset($item['title']['subtitle']['color'])) class="text-[{{$item['title']['subtitle']['color']}}]"@endif>
                {{ $item['title']['subtitle']['text'] }}
            </span>
            @endif
        </x-tables.td>
        @else
        <x-tables.td class="text-[#444444] text-center !py-1 !px-2 border border-[#E1E6EF] {{ $classBg }}">
            {{ $item['rowNumber'] }}
        </x-tables.td>
        <x-tables.td class="flex !py-1 !pr-1 !pl-{{ ($item['title']['level'] ?? 1 ) * 2 }} border border-[#E1E6EF] {{ $classBg }}">
            <span class="text-[{{ $item['title']['color'] ?? '#444444'}}] {{ ($item['title']['class'] ?? '') }}">{{ $item['title']['text'] }}
            @if(isset($item['title']['subtitle']))
                @php
                    $classSpan = $item['title']['subtitle']['class'] ?? '';
                    if(isset($item['title']['subtitle']['color'])){
                        $classSpan .= " text-[" . $item['title']['subtitle']['color'] . "]";
                    }
                @endphp
                <span class="{{ $classSpan }}">{{ $item['title']['subtitle']['text'] }}</span>
            @endif
            </span>
        </x-tables.td>
            @foreach($item['values'] as $value)
            @php
            $classDisabled = '';
            if (isset($value['background'])) {
                $classDisabled = 'bg-[' . $value['background'] . ']';
            }
            @endphp
        <x-tables.td class="{{ empty($classDisabled) ? 'text-[#444444]' : 'text-[#757575]' }} text-center !py-1 !px-2 border border-[#E1E6EF] {{ $classBg }} {{ $classDisabled }}">{{ $formatToCurrency ? formatToCurrency($value['value'] ?? '') : (($value['value'] !== '') ? $value['value'] . ' ' . $unitValue : '') }}</x-tables.td>
            @endforeach
        @endif
    <!-- End Row -->
    </x-tables.tr>
    @endforeach

</x-tables.table>
