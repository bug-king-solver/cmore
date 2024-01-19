<div>

    <x-slot name="header">
        <x-header title="{{ __('Asset listing') }}" dataTest="data-header" class="!bg-esg4" textcolor="text-esg5"
            iconcolor="{{ color(5) }}" click="#">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                <x-buttons.a-icon modal="gar-btar-assets.upload-document" :data="''" data-test="add-data-btn"
                    class="flex place-content-end uppercase">
                    <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                        @include('icons/library/upload', ['color' => color(4)])
                        <label>{{ __('Upload') }}</label>
                    </div>
                </x-buttons.a-icon>
            </x-slot>
        </x-header>
    </x-slot>

    @php
        $buttons = [
            [
                'route' => 'tenant.garbtar.assets',
                'label' => __('Panel'),
                'icon' => 'panel',
            ],
            [
                'route' => 'tenant.garbtar.asset',
                'label' => __('All'),
                'icon' => 'asset',
            ]
        ];
    @endphp

    <x-menus.panel :buttons="$buttons" activated='tenant.garbtar.asset' />

    <x-filters.filter-bar :filters="$availableFilters" :isSearchable="$isSearchable" />

    <div class="mt-10">
        <x-tables.table>
            <x-slot name="thead">
                <x-tables.th class="text-esg6 p-2 text-left border-b !border-y-[#E1E6EF]">
                    <div class="flex items-center gap-2">{{ __('Organisation/NIPC') }}</div>
                </x-tables.th>
                <x-tables.th class="text-esg6 p-2 text-left border-b !border-y-[#E1E6EF]">
                    <div class="flex items-center gap-2"> {{ __('Type of Asset') }}</div>
                </x-tables.th>
                <x-tables.th class="text-esg6 p-2 text-left border-b !border-y-[#E1E6EF]">
                    <div class="flex items-center gap-2"> {{ __('Entity') }}</div>
                </x-tables.th>
                <x-tables.th class="text-esg6 p-2 text-left border-b !border-y-[#E1E6EF]">
                    <div class="flex items-center gap-2"> {{ __('NACE') }}</div>
                </x-tables.th>
                <x-tables.th class="text-esg6 p-2 text-left border-b !border-y-[#E1E6EF]">
                    <div class="flex items-center gap-2"> {{ __('Specific NACE') }}</div>
                </x-tables.th>
                <x-tables.th class="text-esg6 p-2 text-left border-b !border-y-[#E1E6EF]">
                    <div class="flex items-center gap-2"> {{ __('Total') }}</div>
                </x-tables.th>
                <x-tables.th class="text-esg6 p-2 text-left border-b !border-y-[#E1E6EF]"></x-tables.th>
            </x-slot>

            @foreach ($assets->items() as $row)
                @php $loopEncoded = json_encode($loop); @endphp
                <x-tables.tr>
                    <x-tables.td loop="{!! $loopEncoded !!}">
                        <div class="flex flex-col">
                            <span class="text-[#444444] font-medium">{{ $row->company->name }}</span>
                            <span class="text-[#757575] text-xs">{{ $row->company->vat_number }}</span>
                        </div>
                    </x-tables.td>

                    <x-tables.td loop="{!! $loopEncoded !!}" class="text-[#444444] font-medium">
                        {{ $this->assetTypeList[$row[App\Models\Tenant\GarBtar\BankAssets::TYPE]] ?? '' }} </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}" class="text-[#444444] font-medium">
                        {{ $this->entityTypeList[$row[App\Models\Tenant\GarBtar\BankAssets::ENTITY_TYPE]] ?? '' }} </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}" class="text-[#444444] font-medium">
                        {{ $row->nace->parent->name ?? '' }} </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}" class="text-[#444444] font-medium">
                        {{ $row->nace->name ?? '' }} </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}" class="text-[#444444] font-medium text-right">
                        {{ formatToCurrency(floatval($row[App\Models\Tenant\GarBtar\BankAssets::TOTAL_VALUE])) }}
                    </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}">
                        <div class="w-5 h-5 grid place-content-center bg-white rounded border border-slate-200 cursor-pointer"
                            wire:click="showInformationRow({{ $loop->index }})">
                            @if ($loop->index === $rowActive)
                                @include('icons.double_arrow_up')
                            @else
                                @include('icons.double_arrow_up', ['class' => 'rotate-180'])
                            @endif
                        </div>
                    </x-tables.td>
                </x-tables.tr>
                <x-tables.tr class="{{ $loop->index !== $rowActive ? 'hidden' : '' }}">
                    <x-tables.td colspan="7" class="bg-[#FBFBFD]">
                        <x-garbtarassets.detail :row="$row" />
                    </x-tables.td>
                </x-tables.tr>
            @endforeach
        </x-tables.table>
        <div class="mb-10">
            {{ $assets->links() }}
        </div>
    </div>
</div>
