<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Regulatory Tables') }}" dataTest="data-header" class="!bg-transparent" textcolor="text-esg5" iconcolor="{{ color(5) }}" click="#">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="w-full">
        <x-tables.table class="w-full">
            <x-slot name="thead">
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left w-[70%]">{{ __('Name') }}</x-tables.th>
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left w-[20%]">&nbsp;</x-tables.th>
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left w-[10%]">&nbsp;</x-tables.th>
            </x-slot>
            @foreach($listTables as $table)
            <x-tables.tr>
                <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                    <a href="{{ $table['link'] ?? '#' }}">{{ $table['title'] }}</a>
                </x-tables.td>
                <x-tables.td class="!py-1.5 !w-6 !border-b-esg7/40">
                    <span>{{ $table['regulation'] }}</span>
                </x-tables.td>
                <x-tables.td class="!py-1.5 text-sm !border-b-esg7/40">
                    <div class="flex flex-row">
                        <x-buttons.a-icon href="{{ $table['link'] ?? '#' }}" title="{!! __('View') !!}" class="cursor-pointer">
                            @include('icons.eye-line-v1', ['color' => color(16)])
                        </x-buttons.a-icon>
                        <x-buttons.a-icon href="#" title="{{ __('Download') }}" class="cursor-pointer">
                            @include('icons/tables/download', ['color' => color(16)])
                        </x-buttons.a-icon>
                    </div>
                </x-tables.td>
            </x-tables.tr>
            @endforeach

        </x-tables-table>
    </div>
</div>
