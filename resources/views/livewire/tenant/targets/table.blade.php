<x-tables.table>


    <x-slot name="thead">
        <x-tables.th>{{ __('Company') }}</x-tables.th>
        <x-tables.th>{{ __('Title') }}</x-tables.th>
        <x-tables.th>{{ __('Owner') }}</x-tables.th>
        <x-tables.th>{{ __('Status') }}</x-tables.th>
        <x-tables.th>{{ __('Due Date') }}</x-tables.th>
        <x-tables.th>&nbsp;</x-tables.th>
    </x-slot>

    @foreach ($targets as $target)
        @php
            $loopEncoded = json_encode($loop);
            $data = json_encode(['target' => $target->id]);
        @endphp
        <x-tables.tr>
            <x-tables.td loop="{!! $loopEncoded !!}">
                {{ $target->company->name }}
            </x-tables.td>
            <x-tables.td loop="{!! $loopEncoded !!}">
                {{ $target->title }}
            </x-tables.td>
            <x-tables.td loop="{!! $loopEncoded !!}">
                {{ $target->user->name ?? '-' }}
            </x-tables.td>
            <x-tables.td loop="{!! $loopEncoded !!}">
                {{ __($target->status_label) }}
            </x-tables.td>
            <x-tables.td loop="{!! $loopEncoded !!}">
                {{ $target->due_date->format('Y-m-d') }}
            </x-tables.td>
            <x-tables.td loop="{!! $loopEncoded !!}">
                <div class="flex w-full items-center justify-end space-x-4">
                    @can('targets.view')
                        <x-buttons.a-icon href="{{ route('tenant.targets.show', ['target' => $target->id]) }}">
                            @include('icons/eye')
                        </x-buttons.a-icon>
                    @endcan
                    @can('targets.update')
                        <x-buttons.edit modal="targets.modals.form" :data="$data" />
                    @endcan
                    @can('targets.delete')
                        <x-buttons.trash modal="targets.modals.delete" :data="$data" />
                    @endcan
                </div>
            </x-tables.td>
        </x-tables.tr>
    @endforeach
    </x-tables-table>

    <div class="">
        {{ $targets->links() }}
    </div>
