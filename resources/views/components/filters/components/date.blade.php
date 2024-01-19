<div class="w-full h-full flex flex-row items-center justify-between ml-1" wire:key="{{ $columnName }}">

    <x-buttons.a-icon x-on:click="{{ $columnName }}.open()" title="{{ __('Open calendar') }}">
        @include('icons.calender', [
            'color' => '#757575',
        ])
    </x-buttons.a-icon>

    <input type="text" id="filter_{{ $columnName }}" name="filter_{{ $columnName }}" class="datepicker absolute mt-4 -ml-2"
        x-ref="{{ $columnName }}" x-init="{{ $columnName }} = flatpickr($refs.{{ $columnName }}, {
            dateFormat: 'Y-m-d',
            onChange: function(selectedDates, dateStr) {
                return updateFiltersValues('{{ $columnName }}', dateStr);
            }
        });"
        value="{{ $this->activeFilters[$columnName] ?? '' }}">
</div>
