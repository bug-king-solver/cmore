@foreach ($activeFiltersParseValues as $filter => $selectedValues)
    <div class="flex flex-row items-center px-1 rounded-md w-max h-[27px] gap-2 whitespace-nowrap">
        {{ $selectedValues['title'] }}:
    </div>

    @foreach ($selectedValues['values'] ?? [] as $itemKey => $item)
        <div class="flex flex-row items-center bg-esg10 px-2 rounded w-fit h-[27px] gap-2">
            <span class="whitespace-nowrap text-sm font-medium text-esg8">
                @if ($selectedValues['component'] == 'date-between')
                    {{ implode(' to ', $selectedValues['values']) }}
                @elseif (str_contains($item, 'rgba') === 0)
                    {{ rgbaToHex($item) }}
                @else
                    {!! $item !!}
                @endif
            </span>

            @php
                $filterName = $filter;
                $filterValue = $selectedValues['component'] == 'date-between' ? implode(',', $selectedValues['values']) : $item;
                $component = $selectedValues['component'];
            @endphp
            <span
                class="flex items-center justify-center text-md cursor-pointer px-1 hover:text-esg6 pb-[3px] text-esg16 font-normal"
                x-on:click="removeItemFromFilter('{{ $filter }}', '{{ $filterValue }}', '{{ $itemKey }}','{{ $component }}')">
                x
            </span>
            @if ($selectedValues['component'] == 'date-between')
            @break
        @endif
    </div>
@endforeach
@endforeach
