@if ((isset($filters) && count($filters) > 0) || $isSearchable ?? false)
    <div class="relative flex flex-col w-full h-full pb-5" id="filterBarComponent">
        <div class="flex flex-row flex-wrap gap-5 justify-items-center mb-5">
            <div class="border border-esg61 rounded w-8/12">
                @if ($isSearchable ?? false)
                    <x-inputs.text id='searchValue' class="border border-esg61 h-full flex outline-none px-4 rounded"
                        modelmodifier='.debounce.500ms' placeholder="{{ __('Type here to search') }}" />
                @endif
            </div>

            @if (isset($this->availableSorts) && count($this->availableSorts) > 0)
                <x-filters.filter-sort :dropdownSorts="$this->availableSorts ?? []" />
            @endif

            @if (count($filters) > 0)

                <x-filters.filter-selection :dropdowFilters="$filters" />

                @foreach ($this->filterSelected as $columnName => $item)
                    <div class="flex flex-row items-center h-[40px] w-fit">
                        <x-filters.scoped-filter :item="$item" :title="$item['title']" :columnName="$columnName" />
                    </div>
                @endforeach
            @endif

        </div>

        @if (isset($this->activeFiltersParseValues) && count($this->activeFiltersParseValues) > 0)
            <div class="flex flex-row flex-wrap gap-2 pb-5">
                <x-filters.filter-active :activeFiltersParseValues="$this->activeFiltersParseValues" :sort="$this->sort" />
            </div>
        @endif
    </div>
@endif

@push('head')
    <style nonce="{{ csp_nonce() }}">
        #filterBarComponent .ts-control {
            border: none !important;
            width: 1px;
        }

        #filterBarComponent .ts-control input {
            color: #FFF !important;
        }

        #filterBarComponent .ts-wrapper .ts-dropdown {
            top: 1.25rem;
            color: #444444;
            font-size: 14px;
        }

        #tomselect-1-ts-dropdown>div>div {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        #filterBarComponent input.datepicker {
            width: 0px;
            color: #fff;
            visibility: hidden;
            padding: inherit;
        }

        #filterBarComponent .plugin-dropdown_input .dropdown-input {
            border: 1px solid #E1E6EF;
            border-radius: 4px;
            outline: none;
        }

        #filterBarComponent .dropdown-input-wrap {
            padding: 4px;
        }
    </style>
@endpush
