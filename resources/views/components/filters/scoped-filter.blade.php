<div class="flex items-center gap-1 border-esg10 rounded-md border h-[40px] w-max" data-scoped="{{ $columnName }}">

    @if ($item['component'] == 'select')
        <x-filters.components.select :item="$item" columnName="{{ $columnName }}" :options="$item['options']" />
    @elseif($item['component'] == 'date')
        <x-filters.components.date :item="$item" columnName="{{ $columnName }}" />
    @elseif($item['component'] == 'date-between')
        <x-filters.components.date-between :item="$item" columnName="{{ $columnName }}" />
    @elseif($item['component'] == 'select-color')
        <x-filters.components.select-color :item="$item" columnName="{{ $columnName }}" :options="$item['options']" />
    @endif

    <div class="flex items-center px-2 border-[1px] bg-[#FAFAFA] border-esg61 h-full whitespace-nowrap text-esg16">
        {{ $title }}
    </div>

</div>

<style nonce="{{ csp_nonce() }}">
    #filterBarComponent .ts-control {
        display: none;
    }

    #filterBarComponent .ts-dropdown {
        left: -2.25rem !important;
    }
</style>
