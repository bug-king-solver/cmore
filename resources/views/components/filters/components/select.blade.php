<div class="w-full h-full flex flex-row items-center justify-between ml-1" wire:key="{{ $columnName }}">

    <x-buttons.a-icon x-on:click="openTomSelect('{{ $columnName }}', 'filter_{{ $columnName }}')">
        @include('icons.filter', [
            'color' => '#757575',
            'class' => 'hover:fill-[#FFFF]',
            'width' => '16',
            'height' => '16',
        ])
    </x-buttons.a-icon>

    <x-inputs.tomselect id="filter_{{ $columnName }}" name="filter_{{ $columnName }}" :label="null"
        :optgroups="false" plugins="['dropdown_input', 'checkbox_options', 'remove_button']" :options="$options"
        :items="$this->activeFilters[$columnName] ?? []" class="border-esg0 outline-none"
        x-on:change="updateSelectFilterValues($event.target, '{{ $columnName }}')"
        placeholder="{{ __('Type here to search') }}" :prop="false">


    </x-inputs.tomselect>
</div>
