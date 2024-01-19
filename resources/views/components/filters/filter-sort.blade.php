@php
    $sortKey = array_key_first($this->sort);
    $sortName = implode(' ', explode('_', $sortKey));
    $sortName = __(ucfirst($sortName));
    $sortValue = $this->sort[$sortKey];
@endphp

<div class="h-[40px] w-fit">
    <x-buttons.btn-alt id="dropDownSortFilter" data-dropdown-toggle="dropdownSort"
        class="rounded-md !border-esg10 !rounded-md !border border outline-none h-full dropdown-toggle hover:bg-[#cccccc84]">
        <x-slot name="buttonicon">
            <div class="px-1 py-1 justify-between items-center inline-flex">
                <div class="justify-start items-center gap-1 flex mr-3">
                    <div class="mr-2">
                        @include('icons/sort-v2', [
                            'width' => '26',
                            'height' => '26',
                            'color' => color(5),
                            'up' => $sortValue == 'asc',
                        ])
                    </div>
                    <div class="text-center text-esg16 normal-case text-sm font-normal">
                        {{ array_key_first($this->sort) && isset($dropdownSorts[array_key_first($this->sort)]) ? __($dropdownSorts[array_key_first($this->sort)]) : __('Sort') }}
                    </div>
                </div>
                <div class="">
                    @include('icons/drop-down')
                </div>
            </div>
        </x-slot>
    </x-buttons.btn-alt>

    <div id="dropdownSort" class="hidden z-10 w-44 bg-white rounded-md shadow-bxesg1 divide-y divide-esg61 p-1">
        <ul class="text-sm text-gray-700" aria-labelledby="dropDownSortFilter">
            <li class="relative px-2 py-1 hover:bg-gray-100">
                <x-buttons.btn-text
                    class="w-full inline-flex items-center p-0 {{ $this->sortDirection == 'asc' ? 'text-esg5' : '!text-esg8' }}"
                    wire:click="updateAvailableSortDirection('asc')">
                    {{ __('Ascending') }}
                </x-buttons.btn-text>
            </li>
            <li class="relative px-2 py-1 hover:bg-gray-100">
                <x-buttons.btn-text
                    class="w-full inline-flex items-center p-0 {{ $this->sortDirection == 'desc' ? 'text-esg5' : '!text-esg8' }}"
                    wire:click="updateAvailableSortDirection('desc')">
                    {{ __('Descending') }}
                </x-buttons.btn-text>
            </li>
        </ul>
        <ul class="text-sm text-gray-700" aria-labelledby="dropDownSortFilter">
            @foreach ($dropdownSorts as $key => $item)
                <li class="relative px-2 py-1 hover:bg-gray-100">
                    <x-buttons.btn-text
                        class="w-full inline-flex items-center p-0 {{ array_key_first($this->sort) == $key ? 'text-esg5' : '!text-esg8' }}"
                        wire:click="updateAvailableSort('{{ $key }}')">
                        {{ __($item) }}
                    </x-buttons.btn-text>
                </li>
            @endforeach
        </ul>
    </div>
</div>
