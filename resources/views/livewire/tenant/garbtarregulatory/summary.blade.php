<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Summary of GAR') }}" dataTest="data-header" class="!bg-transparent" textcolor="text-[#39B54A]" iconcolor="#39B54A" click="{{ route('tenant.garbtar.regulatory') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div>
        <h2 class="text-[#44724D] uppercase text-xl font-bold">{{ __('Summary of GAR Key Performance Indicators') }}</h2>

        @foreach($data as $table)
        <x-tables.table class="mt-6 table-fixed">
            <x-slot name="thead">
                <x-tables.tr>
                    <x-tables.td class="!py-1 !px-2 !border-b-0"></x-tables.td>
                    <x-tables.td class="!py-1 !px-2 text-center bg-[#E1E6EF]" colspan="4">{{ $table['title'] }}</x-tables.td>
                </x-tables.tr>
                <x-tables.tr>
                    <x-tables.td class="text-center !py-1 !px-2 !border-t-0"></x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">{{ __('Climate change mitigation') }}</x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">{{ __('Climate change adaptation') }}</x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">{{ __('Total (Climate change mitigation + Climate change adaptation)') }}</x-tables.td>
                    <x-tables.td class="text-center !py-1 !px-2 border">
                        <span class="font-bold">{{ __('% Coverage (Over total assets)*') }}</span>
                    </x-tables.td>
                </x-tables.tr>
            </x-slot>
            @foreach($table['data'] as $item)
            <x-tables.tr wire:key="{{ $item['id'] }}">
                <x-tables.td class="text-[#444444] !py-1 !px-2 border border-[#E1E6EF]">
                    {{ $item['rowTitle'] }}
                </x-tables.td>
                @foreach($item['values'] as $key => $value)
                <x-tables.td class="text-[#444444] !py-1 !px-2 border border-[#E1E6EF] text-center font-bold">{{ $value }} %</x-tables.td>
                @endforeach
            </x-tables.tr>
            @endforeach

        </x-tables.table>
        @endforeach

    </div>
</div>