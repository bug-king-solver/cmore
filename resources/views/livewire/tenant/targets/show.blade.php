@push('head')
    <x-comments::styles />
@endpush

<div>
    <x-slot name="header">
        <x-header title="{{ __('Target') }}" data-test="targets-header" click="{{ route('tenant.targets.index') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('targets.update')
                <x-buttons.a-icon href="{{ route('tenant.targets.form', ['target' => $target->id]) }}"
                    data-test="add-data-btn" class="flex place-content-end">
                    <div
                        class="flex gap-1 items-center bg-esg4 cursor-pointer rounded-md text-esg16 py-1 px-2 border border-esg16/70 rounded-md">
                        @include('icons.edit', ['color' => color(16)])
                        <label>{{ __('Edit') }}</label>
                    </div>
                </x-buttons.a-icon>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>

    <div class="px-4 md:px-0 md:pt-4">
        <div class="w-full text-esg8 text-2xl font-bold">
            {{ $target->title }}
        </div>

        <div class="md:flex gap-3 pt-6 items-center">
            @if (count($users) > 0)
                <div class="flex gap-3 items-center mr-3">
                    <div class="text-sm">
                        {{ __('Members') }}
                    </div>

                    <div class="flex gap-2">
                        @foreach ($users->unique() as $user)
                            <x-members.round url="{{ $user->avatar }}" alt="{{ $user->name }}" />
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="w-44 mt-4 md:mt-0">
                <x-inputs.select id="status" :extra="['options' => $statusList]" />
            </div>

            <div class="w-36 mt-4 md:mt-0">
                <div class="text-esg8 text-lg h-11 rounded-md bg-esg8/10 p-2 flex items-center">
                    <div class="px-2">@include('icons.calender')</div>
                    <div>{{ $target->due_date ? $target->due_date->format('Y-m-d') : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="pt-7 flex gap-4 items-center">
            <x-generic.progress-bar :progress="$target->calcProgress($target->getRelation('tasks'))" />
        </div>

        <div class="pt-7 md:flex gap-4 text-lg text-esg8">
            <div class="flex items-center">
                <div class="pr-2">@include('icons.hash')</div>
                <div class="">
                    {{ $target->our_reference ?? 'n/a' }}
                </div>
            </div>
            <div class="flex items-center mt-4 md:mt-0">
                <div class="pr-2">@include('icons.performance')</div>
                <div class="">
                    {{ $target->indicator->name }}
                </div>
            </div>

            <div class="flex items-center mt-4 md:mt-0">
                <div class="pr-2">@include('icons.goal')</div>
                <div class="">
                    {{ $target->goal }}
                </div>
            </div>

            <div class="flex items-center mt-4 md:mt-0">
                <div class="pr-2">@include('icons.calender', ['color' => color(5), 'height' => 17, 'width' => 20])</div>
                <div>
                    {{ $target->start_date ? $target->start_date->format('Y-m-d') : '-' }} {{ __('to') }}
                    {{ $target->due_date ? $target->due_date->format('Y-m-d') : '-' }}
                </div>
            </div>
        </div>

        <div class="text-esg8 text-lg pt-4 flex gap-2">
            <div class="pt-1.5">
                @include('icons.info', ['color' => color(5), 'height' => 18, 'width' => 18])
            </div>
            <div>
                {!! $target->description !!}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-7">
            <div class="border border-esg8/30 rounded-md h-80 p-4">
                <div class="absolute">
                    <div class="text-esg8 font-encodesans flex-col text-base font-bold">
                        <p class="inline">{{ __('Year Prospection') }}</p>
                        <span class="bg-red-500 text-esg27 px-2 py-1 rounded-md inline ml-2 text-xs">
                            {{ __('Off target') }}
                        </span>
                    </div>
                    <div class="flex gap-5">
                        <div class="flex">
                            <div class="text-esg5 text-xl">
                                <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-esg3"></span>
                            </div>
                            <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Result') }}</div>
                        </div>

                        <div class="flex">
                            <div class="text-esg5 text-xl">
                                <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-[#153A5B]"></span>
                            </div>
                            <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Prospection') }}</div>
                        </div>

                        <div class="flex">
                            <div class="text-esg5 text-xl">
                                <span class="w-2 h-2 relative -top-2 rounded-full inline-block bg-[#19A0FD]"></span>
                            </div>
                            <div class="pl-2 inline-block text-xs text-esg8/70">{{ __('Action Plan') }}</div>
                        </div>
                    </div>
                </div>
                <div
                    class="font-encodesans text-base font-normal text-esg8 text-center h-full grid place-content-center">
                    {{ __('No data available') }}
                </div>
            </div>
            <div class="border border-esg8/30 rounded-md h-80 p-4 relative ">
                <div class="absolute">
                    <div class="text-esg8 font-encodesans flex-col text-base font-bold">
                        <p class="inline line-clamp-1">
                            {{ $target->indicator->name }}
                        </p>
                    </div>
                </div>
                <div
                    class="font-encodesans text-base font-normal text-esg8 text-center h-full grid place-content-center">
                    {{ __('No data available') }}
                </div>
            </div>
        </div>

        <div class="mt-6 mb-5">
            <x-tasks.table :tasks="$target->tasks()->paginate(10)" entity='targets' entityId="{{ $target->id }}" />
        </div>

        <div class="mt-6">
            @php $data = json_encode(['modelId' => $target->id, 'modelType' => 'target']);@endphp
            <h2 class="text-esg8 mb-5 text-xl font-semibold inline-flex">{{ __('Evidences') }} @can('targets.update')
                    <x-buttons.attachment-alt :data="$data" :counter="$attachmentsCount" text="{{ __('Add') }}"
                        class="" />
                @endcan
            </h2>
        </div>
        <div class="text-esg8 pb-7 text-lg">
            @if ($attachments->count())
                <div class="mb-4">
                    <p class="mt-6">{{ __('Added documents') }}</p>
                    @foreach ($attachments as $attachment)
                        <div class="mt-5 flex w-full items-center">
                            <div class="mr-4">
                                <img class="comments-avatar" src="{{ $attachment->user()->avatar }}" alt="avatar"
                                    title="{{ __('Added at :date by :name', ['date' => $attachment->created_at, 'name' => $attachment->name]) }}">
                            </div>
                            <div class="mr-4">
                                @include('icons/document')
                            </div>

                            <div class="text-esg8 text-sm font-medium">
                                <p class="text-esg8 pb-1 text-sm font-medium leading-3">
                                    <a target="_blank" rel="noopener"
                                        href="{{ $attachment->getUrl() }}">
                                        {{ $attachment->name }}
                                    </a>
                                </p>
                                <p class="text-esg8 text-xs font-semibold">
                                    {{ getSizeForHumans($attachment->size) }}
                                </p>
                            </div>

                            <div class="flex-1 text-right">
                                @can('targets.update')
                                    <button type="button" wire:click="destroy('{{ $attachment->id }}')">
                                        @include('icons/trash', [
                                            'stroke' => config('theme.default.colors.esg4'),
                                        ])
                                    </button>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center">{{ __('You don\'t have any evidence uploaded.') }}</p>
            @endif

            <p class="mt-6 text-center">
            </p>
        </div>
        <div class="text-esg8 text-lg mb-10">
            <livewire:comments :model="$target" />
        </div>
    </div>
</div>
