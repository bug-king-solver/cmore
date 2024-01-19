<div>
    <x-slot name="header">
        <x-header title="{{ __('Reports') }}" data-test="questionnaires-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('companies.create')
                    <x-buttons.a-icon href="{{ route('tenant.exports.create') }}" data-test="add-data-btn" class="flex place-content-end uppercase">
                        <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                            @include('icons.add', ['color' => color(4)])
                            <label>{{ __('Add') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>

    <x-filters.filter-bar :filters="[]" />

    @if ($reports->count() == 0)
    <div class="flex justify-center items-center p-6">
        <h3 class="w-fit text-md">{{ __('No data available. Click the "add" button to create a reports.') }}</h3>
    </div>
    @else
    <x-tables.table>
        <x-slot name="thead">
            <x-tables.th>{{ __('Framework') }}</x-tables.th>
            <x-tables.th>{{ __('Company') }}</x-tables.th>
            <x-tables.th>{{ __('Date') }}</x-tables.th>
            <x-tables.th>&nbsp;</x-tables.th>
        </x-slot>
        @foreach ($reports as $row)
            @php $loopEncoded = json_encode($loop); @endphp
            <x-tables.tr>
                <x-tables.td loop="{!! $loopEncoded !!}" data-test="export-framework">{{ $row->sources->name }}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}" data-test="export-company">{{ $row->company->name }}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}" data-test="export-data">{{ $row->from }} > {{ $row->to }}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}">
                    <div class="flex w-full items-center justify-end space-x-4">
                        @php $buttonsData = json_encode(["data" => $row->id]); @endphp
                        <x-buttons.a-icon href="{{ route('tenant.exports.show', ['id' => $row->id, 'action' => 'view']) }}" data-test="view-report" class="flex place-content-end">
                            @include('icons.eye')
                        </x-buttons.a-icon>
                        <x-buttons.a-icon href="{{ route('tenant.exports.show', ['id' => $row->id, 'action' => 'edit']) }}" data-test="edit-report" class="flex place-content-end">
                            @include('icons.edit')
                        </x-buttons.a-icon>
                    </div>
                </x-tables.td>
            </x-tables.tr>
        @endforeach
    </x-tables.table>
    @endif
</div>
