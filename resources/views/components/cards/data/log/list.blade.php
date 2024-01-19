<div class="mt-6">
    @if ($log->isEmpty())
        <div class="flex justify-center items-center p-6">
            <h3 class="w-fit text-md">{{ __('Select a company to view the information') }}</h3>
        </div>
    @else
        <x-tables.table>
            <x-slot name="thead">
                <x-tables.th
                    class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Value') }}</x-tables.th>
                <x-tables.th
                    class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Date and time') }}</x-tables.th>
                <x-tables.th
                    class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Reported by') }}</x-tables.th>
                <x-tables.th
                    class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">{{ __('Status') }}</x-tables.th>
                <x-tables.th class="!border-y-esg7 text-esg6 text-sm pb-2 font-normal text-left">&nbsp;</x-tables.th>
            </x-slot>
            @foreach ($log->sortByDesc('id') as $row)
                @php $loopEncoded = json_encode($loop); @endphp
                <x-tables.tr>
                    <x-tables.td loop="{!! $loopEncoded !!}" data-test="data-value"
                        class="text-sm">{{ $row->value }}</x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}" data-test="data-datetime" class="text-sm">
                        <div class="flex items-center gap-1 bg-esg7/20 rounded p-1 w-48">
                            <span class="flex items-center">@include('icons.calander', ['color' => color(8)])
                                {{ \Carbon\Carbon::parse($row->reported_at)->format('Y-m-d') }}</span>
                            <span class="flex items-center">@include('icons.clock', ['color' => color(8)])
                                {{ \Carbon\Carbon::parse($row->reported_at)->format('H:i A') }}</span>
                        </div>
                    </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}" data-test="data-reported-by" class="text-sm">
                        {{ $row->user->name ?? '-' }}
                    </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}" data-test="data-status" class="text-sm">

                        @php
                            $auditorStatus = \App\Models\Tenant\Auditor::where('company_id', $row->company_id)->first();
                            $auditorstat = $auditorStatus ? $auditorStatus->status : null;
                        @endphp

                        @if ($row->validator_status || $row->auditor_status || ($row->validator_status && !$auditorstat))
                            <div class="flex items-center gap-1 bg-esg7/20 rounded w-20 p-1">
                                @include('icons.checkbox', ['width' => 14, 'height' => 14])
                                <span class="text-xs text-esg8"> {{ __('Updated') }} </span>
                            </div>
                        @else
                            <div class="flex items-center gap-1 bg-esg7/20 rounded w-32 p-1">
                                @include('icons.loading')
                                <span class="text-xs text-esg8"> {{ __('Pending validation') }} </span>
                            </div>
                        @endif
                    </x-tables.td>
                    <x-tables.td loop="{!! $loopEncoded !!}">
                        <div class="flex w-full items-center justify-end space-x-1">
                            @php $buttonsData = json_encode(["data" => $row->id]); @endphp

                            @can('data.view')
                                <x-buttons.a-icon href="{{ route('tenant.data.show', ['data' => $row->id]) }}"
                                    data-test="add-data-btn" class="flex place-content-end">
                                    @include('icons.eye-line2', ['width' => 16, 'height' => 16])
                                </x-buttons.a-icon>
                            @endcan

                            @can('data.update')
                                <x-buttons.a-icon
                                    href="{{ route('tenant.data.form', ['company' => $row->company, 'indicator' => $indicator->id, 'data' => $row->id]) }}"
                                    data-test="add-data-btn" class="flex place-content-end">
                                    @include('icons.edit', [
                                        'width' => 16,
                                        'height' => 16,
                                        'color' => color(8),
                                    ])
                                </x-buttons.a-icon>
                            @endcan

                            @can('data.delete')
                                <x-buttons.trash modal="data.modals.delete" :data="$buttonsData" data-test="delete-data-btn" />
                            @endcan
                        </div>
                    </x-tables.td>
                </x-tables.tr>
            @endforeach
            </x-tables-table>

            <div class="mb-10">
                {{ $log->links() }}
            </div>
    @endif
</div>
