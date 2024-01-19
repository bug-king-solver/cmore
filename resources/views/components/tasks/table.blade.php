@if (tenant()->hasTasksFeature)
    <x-cards.card-version-target-panel-v2 text="{{ __('Tasks') }}" class="mt-10">
        <x-slot:dropdown>
            @if (isset($entity))
                <x-buttons.a-icon href="{{ route('tenant.users.tasks.form') }}?entity={{ $entity . (isset($entityId) ? '&entityId=' . $entityId : '') }}" data-test="add-data-btn" class="flex place-content-end">
                    <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                        @include('icons.add', ['color' => color(4)])
                        <label>{{ __('Add') }}</label>
                    </div>
                </x-buttons.a-icon>
            @else
                <x-buttons.a-icon href="{{ route('tenant.users.tasks.form') }}" data-test="add-data-btn" class="flex place-content-end">
                    <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                        @include('icons.add', ['color' => color(4)])
                        <label>{{ __('Add') }}</label>
                    </div>
                </x-buttons.a-icon>
            @endif
        </x-slot:dropdown>
        <x-slot:data>
            <x-tables.table-withfilter class="md:w-full">
                <x-slot name="thead">
                    <x-tables.th no_border>#</x-tables.th>
                    <x-tables.th no_border>{{ __('Tasks') }}</x-tables.th>
                    <x-tables.th no_border>{{ __('Due date') }}</x-tables.th>
                    <x-tables.th no_border>&nbsp;</x-tables.th>
                    <x-tables.th no_border>&nbsp;</x-tables.th>
                    <x-tables.th no_border>&nbsp;</x-tables.th>
                    <x-tables.th no_border>&nbsp;</x-tables.th>
                </x-slot>

                @forelse ($tasks as $task)
                    @php $loopEncoded = json_encode($loop); @endphp
                    @php $data = json_encode(["task" => $task->id]); @endphp
                    <x-tables.tr class='border-b-1'>
                        <x-tables.td loop="{!! $loopEncoded !!}">{{ $task->id }}</x-tables.td>
                        <x-tables.td loop="{!! $loopEncoded !!}" class="w-1/2">
                            <div class="flex flex-col mr-6">
                                <span class="title">
                                    {{ $task->name }}
                                </span>
                                <span class="description text-justify">
                                    {{ $task->description }}
                                </span>
                            </div>
                        </x-tables.td>
                        <x-tables.td loop="{!! $loopEncoded !!}">{{ $task->due_date->format('Y-m-d') }}
                        </x-tables.td>
                        <x-tables.td loop="{!! $loopEncoded !!}">
                            <span class='flex gap-3'>
                                @include('icons.weight', [
                                    'color' => '#444444',
                                    'width' => '20',
                                    'height' => '20',
                                ])
                                {{ $task->weight }}
                            </span>
                        </x-tables.td>
                        <x-tables.td loop="{!! $loopEncoded !!}">
                            @if ($task->alert_on_complete)
                                @include('icons.bell-2', [
                                    'color' => '#444444',
                                    'width' => '20',
                                    'height' => '20',
                                ])
                            @else
                                @include('icons.bell-2-disabled', [
                                    'color' => '#B1B1B1',
                                    'width' => '20',
                                    'height' => '20',
                                ])
                            @endif
                        </x-tables.td>
                        <x-tables.td loop="{!! $loopEncoded !!}">
                            <x-buttons.btn-icon modal="tasks.modals.complete" :data="$data">
                                <x-slot name="buttonicon">
                                    @include('icons.check', [
                                        'color' => $task->completed ? '#0D9401' : '#B1B1B1',
                                        'width' => '20',
                                        'height' => '20',
                                    ])
                                </x-slot>
                            </x-buttons.btn-icon>
                        </x-tables.td>
                        <x-tables.td loop="{!! $loopEncoded !!}">
                            <div class="flex w-full items-center space-x-4">
                                <a href="{{ route('tenant.users.tasks.show', ['task' => $task->id]) }}">
                                    <x-buttons.btn-icon>
                                        @include('icons.eye', [
                                            'color' => '#B1B1B1',
                                            'width' => '20',
                                            'height' => '20',
                                        ])
                                    </x-buttons.btn-icon>
                                </a>
                            </div>
                        </x-tables.td>
                    </x-tables.tr>
                @empty
                    <x-tables.tr>
                        <x-tables.td colspan="7" class="border-none">
                            <div class="flex w-full items-center justify-center space-x-4 mt-3">
                                {{ __('No tasks available yet. Click the “Add” button to create a new one.') }}
                            </div>
                        </x-tables.td>
                    </x-tables.tr>
                @endforelse
            </x-tables.table-withfilter>

            <div class="">
                {{ $tasks->links() }}
            </div>
        </x-slot:data>
    </x-cards.card-version-target-panel-v2>
@endif
