<div class="w-full h-full flex flex-row items-center justify-between flatpickr ml-1" wire:key="{{ $columnName }}">

    <x-buttons.a-icon x-on:click="openDateBetweenCalendar('{{ $columnName }}')" title="{{ __('Open calendar') }}">
        @include('icons.calender', [
            'color' => '#757575',
        ])
    </x-buttons.a-icon>

    @php
        $value = null;
        if(isset($this->activeFilters[$columnName][1])){
            $value = $this->activeFilters[$columnName][0];
            $value .= ' to ';
            $value .= $this->activeFilters[$columnName][1];
        }
    @endphp

    <input type="text" id="filter_{{ $columnName }}" name="filter_{{ $columnName }}" class="datepicker absolute mt-4 -ml-2"
        x-ref="{{ $columnName }}" x-init="{{ $columnName }} = flatpickr($refs.{{ $columnName }}, {
            dateFormat: 'Y-m-d',
            allowInput: false,
            mode: 'range',
            onChange: function(selectedDates, dateStr, instance) {
                const beetweenDates = document.getElementById('filter_{{ $columnName }}');
                updateDateBetweenFilterValues('{{ $columnName }}', beetweenDates);
                return;
            }
        });"
    value="{{$value}}">
</div>
