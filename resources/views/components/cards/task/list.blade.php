@if (tenant()->hasTasksFeature)
    <div class="m-2">
        @forelse ($tasks as $task)
            @php $data = json_encode(["task" => $task->id]); @endphp
            <div class="relative bg-white rounded-md border flex items-center justify-between mb-4 p-3 cursor-pointer hover:border-esg5"
                x-on:click="window.location.href = '{{ route('tenant.users.tasks.show', ['task' => $task->id]) }}'">


                <div class="flex items-center">
                    <x-buttons.btn-icon modal="tasks.modals.complete" :data="$data" class="-ml-2" x-on:click.stop>
                        <x-slot name="buttonicon">
                            @include('icons.check', [
                                'color' => $task->completed ? '#0D9401' : '#B1B1B1',
                                'width' => '16',
                                'height' => '16',
                                'class' => 'hover:scale-125 transition-all duration-300',
                            ])
                        </x-slot>
                    </x-buttons.btn-icon>

                    <span class="text-esg42 font-encodesans text-sm">
                        {{ $task->name }}
                    </span>
                </div>

                <div class="flex items-center">
                    <div class="text-esg8 text-xs font-medium mr-6">
                        {{ $task->due_date->format('Y-m-d') }}
                    </div>

                    <div class="flex items-center gap-2 m-1">
                        <div class="flex flex-row gap-1 items-center">
                            <div>
                                <span data-tooltip-target="tooltip-user-role" data-tooltip-target="hover">
                                    @include('icons.weight', [
                                        'color' => color(16),
                                        'width' => '14',
                                        'height' => '14',
                                    ])
                                </span>
                                <div id="tooltip-user-role" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    {!! __('Weight') !!}
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                            <div class="">
                                <p class="text-xs font-encodesans text-esg8 font-medium">
                                    {{ $task->weight }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-row gap-1 items-center mr-4">
                            <div class="">
                                <span data-tooltip-target="tooltip-task-alert" data-tooltip-target="hover">
                                    @if ($task->alert_on_complete)
                                        @include('icons.bell-2', [
                                            'color' => color(16),
                                            'width' => '14',
                                            'height' => '14',
                                        ])
                                    @else
                                        @include('icons.bell-2-disabled', [
                                            'color' => '#B1B1B1',
                                            'width' => '18',
                                            'height' => '18',
                                        ])
                                    @endif
                                </span>
                                <div id="tooltip-task-alert" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    {!! __('Alert') !!}
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                            <div class="text-esg8 text-xs font-encodesans font-medium">
                                {{ $task->alert_on_complete ? __('On') : __('Off') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center m-1">
                        @if ($task->users && count($task->users) > 0)
                            <div class="flex flex-row">
                                @foreach ($task->users as $user)
                                    <img class="rounded-full -ml-2 border-2 boder-esg4" src="{{ $user->avatar }}"
                                        width="24" height="24">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-row gap-2 items-end">
                        @can('tasks.delete')
                            <x-buttons.trash modal="tasks.modals.delete" icon="trash2" :data="$data" x-on:click.stop
                                :param="json_encode(['stroke' => color(16)])"
                                class="!py-0 !px-0 !w-fit !h-fit mx-1 hover:scale-125 transition-all duration-300" />
                        @endcan
                    </div>

                </div>
            </div>

        @endforeach
    </div>

    <x-buttons.btn-view-more loadMoreAction="loadMoreTodo"
        shouldDisplay="{{ $this->filter === 'todo' && $this->moreTasksAvailableTodo && count($tasks) > 0 }}"
        text="View more" />

    <x-buttons.btn-view-more loadMoreAction="loadMoreDone"
        shouldDisplay="{{ $this->filter === 'done' && $this->moreTasksAvailableDone && count($tasks) > 0 }}"
        text="View more" />

    @if (count($tasks) == 0)
        <div class="flex justify-center items-center p-6">
            <h3 class="w-fit text-lg">
                {!! $this->filter === 'todo' ? __('No to-do tasks yet.') : __('No done tasks yet.') !!}
            </h3>
        </div>
    @endif

@endif


{{-- <div class="mb-10">
    {{ $tasks->links() }}
</div> --}}
