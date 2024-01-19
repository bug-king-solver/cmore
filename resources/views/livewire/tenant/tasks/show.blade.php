@push('head')
    <x-comments::styles />
@endpush

<div>
    <x-slot name="header">
        <x-header title="{{ $task->name }}" data-test="task-header" click="{{ route('tenant.users.tasks.index') }}"
            textcolor="text-esg6">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @php $data = json_encode(["task" => $task->id]); @endphp

                <div class="flex flex-row items-center gap-3">
                    @can('tasks.delete')
                        <x-buttons.trash-btn modal="tasks.modals.delete" :data="$data" class="px-2 py-1"
                            class="cursor-pointer" :param="json_encode(['stroke' => color(16)])" />
                    @endcan

                    <x-buttons.a-icon href="{{ route('tenant.users.tasks.form', ['task' => $task->id]) }}"
                        data-test="add-data-btn"
                        class="float-right border-2 border-esg5/40 rounded-md bg-esg5 text-esg4">
                        <div class="flex gap-1 items-center bg-esg54 py-1 px-3 cursor-pointer rounded-md text-esg8">
                            @include('icons.edit', ['color' => color(4)])
                            <label class="text-esg4 cursor-pointer">{{ __('Edit') }}</label>
                        </div>
                    </x-buttons.a-icon>
                </div>

            </x-slot>
        </x-header>
    </x-slot>

    <div class="px-4 md:px-0 md:pt-4">

        <div class="mt-4 md:flex gap-4 text-lg text-esg8 justify-between bg-[#F5F6F9] p-2 rounded">
            <div class="flex flex-row gap-4">
                <div class="flex flex-row gap-2 items-center border border-esg6 bg-esg27 p-1 rounded ">
                    <x-buttons.btn-modal modal="tasks.modals.complete" :data="$data">
                        <x-slot name="buttonicon">
                            @include('icons.check', [
                                'color' => $task->completed ? '#0D9401' : color(8),
                                'width' => '16',
                                'height' => '16',
                            ])
                        </x-slot>
                        <x-slot name="slot">
                            {{ $task->completed ? __('Done') : __('Mark as done') }}
                        </x-slot>
                    </x-buttons.btn-modal>
                </div>

                <div class="flex items-center mt-4 md:mt-0">
                    <div class="pr-2">
                        @include('icons.calender', ['color' => color(8), 'height' => 12, 'width' => 15])
                    </div>
                    <div class="text-esg8 text-xs font-medium">
                        {{ $task->due_date->format('Y-m-d') }}
                    </div>
                </div>


                <div class="flex flex-row gap-1 items-center">
                    <div>
                        <span data-tooltip-target="tooltip-user-role" data-tooltip-target="hover">
                            @include('icons.weight', [
                                'color' => color(8),
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
                    <div>
                        <span data-tooltip-target="tooltip-task-alert" data-tooltip-target="hover">
                            @if ($task->alert_on_complete)
                                @include('icons.bell-2', [
                                    'color' => color(8),
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

            <div>
                <div class="flex items-center m-1">
                    @if ($users && count($users) > 0)
                        <span class="flex flex-row mr-4 text-sm text-esg8">
                            {!! __('Members') !!}
                        </span>
                        <div class="flex flex-row">
                            @foreach ($task->users as $user)
                                <img class="rounded-full -ml-2 border-2 boder-esg4" src="{{ $user->avatar }}"
                                    width="24" height="24">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>



    </div>

    <div class="mt-6 text-base font-normal text-esg8">
        {!! $task->description !!}
    </div>

    @if ($checklist)
        <div class="text-esg8 text-base font-semibold mt-6 flex flex-row gap-2 pb-1 items-center">
            <span>
                {{ __('Checklist') }}
            </span>
        </div>
        <div>
            @foreach ($checklist as $item)
                <div class="text-esg8 text-lg mt-4 flex flex-row gap-2">
                    <div class="flex gap-2 items-center">
                        <div class="pt-1.5">
                            <x-inputs.checkbox
                                wireClick="toggleChecklist({{ $task->id }}, {{ $item->id }}, {{ $item->completed ? 1 : 0 }})"
                                id="checklist.{{ $item->id }}" label="{{ $item->name }}" modelmodifier='defer'
                                value='checklist . {{ $item->id }}'
                                checked="{{ $item->completed ? 'checked' : 'false' }} "
                                class="cursor-pointer checked:!bg-esg5" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($task->targets->first())
        <div class="pt-1.5 mt-6 flex flex-col gap-2">
            <label class="text-esg8 text-base font-semibold">{{ __('Target:') }}</label>
            <p class="text-esg8">{{ $task->targets->first()->title }}</p>
        </div>

        <div class="pt-1.5 mt-4 flex flex-col gap-2">
            <label class="text-esg8 text-base font-semibold">{{ __('Target description:') }}</label>
            <p class="text-esg8">{!! $task->targets->first()->description !!}</p>
        </div>
    @endif

    <div class="text-esg8 text-lg mb-10">
        <livewire:comments :model="$task" />
    </div>

</div>
</div>
