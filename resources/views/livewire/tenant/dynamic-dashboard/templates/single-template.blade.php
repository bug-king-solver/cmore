<x-slot name="header">
    <x-header class="w-full fixed h-40 z-40" title="{{ __('Dashboard Template Preview') }}">
        <x-slot name="left">
        </x-slot>
    </x-header>
</x-slot>

<div class="col-span-2 pt-48">
    <div class="flex justify-between border-b pb-4 border-b-esg7 mb-4">
        <div class="">
            <div class="pt-4">
                <p class="text-lg font-semibold text-esg8"> {{ $dashboardTemplate->name }} </p>
                {{ $dashboardTemplate->description }}
            </div>
        </div>

        <div class="ml-4 pt-2 text-esg8">
            <a href="{{ route('tenant.dynamic-dashboard.index') }}">
                <button type="button"
                    class="text-esg16 bg-esg4 hover:bg-esg4 border border-esg16 focus:outline-none focus:ring-esg16 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-4 mb-2">
                    {{ __('Cancel') }}
                </button>
            </a>
        </div>
    </div>
    @if ($dashboardTemplate->layout)
        @foreach (json_decode($dashboardTemplate->layout, true) as $row)
            <div class=" grid grid-cols-{{ sizeOf($row) }} gap-4 mb-6">
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
                            $chart_struct = json_decode(App\Models\Chart::where('slug', $col['type'])->first()->structure, true);
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
        There is no layout defined for the template
    @endif
</div>
