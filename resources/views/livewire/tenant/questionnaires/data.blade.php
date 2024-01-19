<div>

    <x-slot name="header">
        <x-header title="{{ __('Data') }}">
            <x-slot name="left"></x-slot>

        </x-header>
    </x-slot>
    <div class="mt-12">

        <x-filters.filter-bar :filters="$availableFilters" :isSearchable="$isSearchable" />

        <div style="float: right" class="mb-4 pb-4">
            <button id="dropdownDefaultDownloadButton" data-dropdown-toggle="Downloaddropdown"
                class="text-white bg-esg5 text-esg27 hover:bg-esg5 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-esg5 dark:hover:bg-esg5 dark:focus:bg-esg5"
                type="button"><span class="mx-2">@include('icons/tables/download', ['color' => '#ffffff'])</span>{{ __('Download') }}</button>
            <div id="Downloaddropdown"
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-esg5">
    
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultDownloadButton">
                    <li>
                        <a wire:click="exportPdf"
                            class="block px-4 py-2 hover:bg-esg5 dark:hover:bg-esg5 dark:hover:text-white">{{ __('Export as PDF') }}</a>
                    </li>
                    <li>
                        <a wire:click="exportCsv"
                            class="block px-4 py-2 hover:bg-esg5 dark:hover:bg-esg5 dark:hover:text-white">{{ __('Export to CSV') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    
        <x-tables.table>
            <x-slot name="thead">
    
                <x-tables.th>{{ __('Indicator') }}</x-tables.th>
                <x-tables.th>{{ __('Value') }}</x-tables.th>
                <x-tables.th>{{ __('Unit') }}</x-tables.th>
    
                @foreach ($this->sources as $source)
                    <x-tables.th>{{ __($source) }}</x-tables.th>
                @endforeach
            </x-slot>
            @foreach ($data as $row)
                <x-tables.tr class="indicator">
                    <x-tables.td>{{ $row->indicator->name ?? '-' }}</x-tables.td>
                    <x-tables.td>{{ $row->value ?? '-' }}</x-tables.td>
                    <x-tables.td>{{ $row->indicator->unit_default ?? '-' }}
                    </x-tables.td>
                    @foreach ($this->sources as $sourceName)
                        @php
                            $source = $row->indicator->sources->firstWhere('name', $sourceName);
                        @endphp
                        <x-tables.td>
                            @if ($source)
                                {{ $source->pivot->reference ?? '-' }}
                            @else
                                -
                            @endif
                        </x-tables.td>
                    @endforeach
                </x-tables.tr>
            @endforeach
        </x-tables-table>
            <div class="">
                {{ $data->links() }}
            </div>
    </div>
   
</div>
