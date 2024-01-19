<x-slot name="header">
    <x-header class="w-full h-40 z-40" title="{!! __('Dashboard') !!}">
        <x-slot name="left">
        </x-slot>
    </x-header>
</x-slot>

<div class="col-span-2 ">
    <div class="flex justify-between border-b pb-4 border-b-esg7 mb-4">
        <div class="">
            <div class="pt-4">
                <p class="text-lg font-semibold text-esg8"> {{ $dashboard->name }} </p>
                {{ $dashboard->description }}
            </div>
        </div>
        <div class="pt-2 text-esg8">
            <button wire:click="save"
                class="text-esg16 bg-esg4 hover:bg-esg4 border border-esg16 focus:outline-none focus:ring-esg16 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-4 mb-2">
                {{ __('Save') }}
            </button>
            <button
                onclick="Livewire.emit('openModal', 'report.modals.dashboard-save-as', {{ json_encode(['dashboard' => $dashboard]) }})"
                param="json_encode(['stroke' => color(8), 'width' => 14, 'height' => 18])"
                class="text-esg16 bg-esg4 hover:bg-esg4 border border-esg16 focus:outline-none focus:ring-esg16 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-4 mb-2">
                {{ __('Save as') }}
            </button>
            <a href="{{ route('tenant.dynamic-dashboard.index') }}">
                <button type="button"
                    class="text-esg16 bg-esg4 hover:bg-esg4 border border-esg16 focus:outline-none focus:ring-esg16 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-4 mb-2">
                    {{ __('Cancel') }}
                </button>
            </a>
        </div>
    </div>
    <div class="col-span-1 md:col-span-2 w-full mb-5">
        <div class="flex">
            <div class="mr-2 grow max-w-[33%]">
                <x-inputs.tomselect wire:model="search.company" wire:change="filter()" :options="$companiesList"
                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']" :items="$search['company']" multiple
                    placeholder="Company / NIF" />
            </div>

            <div class="mr-2 grow max-w-[33%]">
                <x-inputs.tomselect wire:model="search.businessSectors" wire:change="filter()" :options="$businessSectorsList"
                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']" :items="$search['businessSectors']" multiple
                    placeholder="Business Sector" />
            </div>

            <div class="grow max-w-[33%]">
                <x-inputs.tomselect wire:model="search.countries" wire:change="filter()" :options="$countriesList"
                    plugins="['no_backspace_delete', 'remove_button', 'checkbox_options']" :items="$search['countries']" multiple
                    placeholder="Country" />
            </div>
        </div>
    </div>

    @if ($dashboard->layout)
        @foreach (json_decode($dashboard->layout, true) as $row)
            <div class="mb-6 w-full row grid gap-4 h-auto grid-cols-{{ sizeOf($row) <= 2 ? sizeOf($row) : 2 }}">
                @foreach ($row as $index => $col)
                    @if ($col['type'] == 'graph')
                        <div class="border p-4 flex flex-row m-auto w-full items-center">
                            @livewire(
                                'charts.report-charts.chart-struct',
                                [
                                    'info' => $col,
                                    'companies' => $this->companiesIds,
                                    'index' => $index,
                                ],
                                key($col['value'])
                            )
                        </div>
                    @else
                        @php
                            $chart_struct = App\Models\Chart::where('slug', $col['type'])->first()->structure;
                        @endphp
                        <div class="">
                            <{{ $col['type'] }} {!! $chart_struct['attributes'] !!}>
                                {{ $col['value'] }}
                                </{{ $col['type'] }}>
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    @else
        {!! __("There is no layout defined for the template") !!}
    @endif
</div>
