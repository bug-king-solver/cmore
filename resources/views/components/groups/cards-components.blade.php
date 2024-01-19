<x-cards.card {{ $attributes->merge(['class' => 'flex-col !p-3']) }}>
    <div class="text-esg29 font-encodesans flex h-auto text-lg font-bold border-b border-b-esg7/25">
        <span class="flex flex-row gap-3 justify-between w-full items-center">
            <div class="flex flex-row items-center min-h-[50px] pb-3">
                <a href="{{ route('tenant.targets.show', ['target' => $resource->id]) }}" class="cursor-pointer">
                    <span class="text-esg29 font-encodesans text-sm font-bold line-clamp-2">
                        {{ $resource[$name ?? 'title'] }}
                    </span>
                </a>
            </div>

            @if (isset($resource->status))
                <span class="pb-3">
                    <x-groups.status-circle :status="$resource->status" />
                </span>
            @endif
        </span>
    </div>

    <div class="text-esg29 font-encodesans w-full h-auto">
        <div class="mb-3 mt-2 w-full h-full">
            <div class="flex flex-row gap-2 mt-3 items-baseline">
                <div class="">
                    <span data-tooltip-target="tooltip-target-reference" data-tooltip-trigger="hover">
                        @include('icons.hash', ['width' => 20, 'height' => 16, 'color' => color(6)])
                    </span>
                    <div id="tooltip-target-reference" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        {{ __('Reference') }}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
                <div class="text-esg8 text-sm font-encodesans overflow-hidden">
                    {{ $resource->our_reference ?? 'n/a' }}
                </div>
            </div>

            <div class="flex flex-row gap-2 mt-3 items-baseline margin-t-esg10">
                <div class="">
                    <span data-tooltip-target="tooltip-target-indicator" data-tooltip-trigger="hover">
                        @include('icons.performance', ['color' => color(6)])
                    </span>
                    <div id="tooltip-target-indicator" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        {{ __('Indicator') }}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
                <div class="">
                    <p class="line-clamp-2 min-h-[42px] text-sm font-encodesans text-esg8 font-medium">
                        {{ $resource->indicator->name ?? 'n/a' }}
                    </p>
                </div>
            </div>

            @if ($resource->users)
                <div class="flex flex-row gap-2 mt-3 items-center">
                    @foreach ($resource->users->unique() as $user)
                        <x-members.round url="{{ $user->avatar }}" alt="{{ $user->name }}" h="h-7" w="w-7" />
                    @endforeach
                </div>
            @endif

            <div class="flex gap-4 items-center mt-3">
                <x-generic.progress-bar :progress="$resource->calcProgress($resource->getRelation('tasks'))" />
            </div>
        </div>
    </div>

    <div class="border-t border-esg7/25 w-full flex flex-col justify-center bottom-0">
        <div class="flex flex-row gap-2 pt-3 justify-between w-full">
            <span data-tooltip-target="tooltip-target-duedate" data-tooltip-trigger="hover">
                <div class="flex flex-row justify-center items-center gap-1">
                    @include('icons.calendar', ['width' => 20, 'height' => 20, 'color' => color(8)])
                    <span class="text-esg8 font-xs">
                        {{ carbon()->parse($resource['due_date'] ?? $resource['created_at'])->format('Y-m-d') }}
                    </span>
                </div>
            </span>
            <div id="tooltip-target-duedate" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                {{ __('Due Date') }}
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            @php
                $href = isset($type) && $type == 'page' ? route('tenant.targets.form', ['target' => $resource->id]) : '';
            @endphp

            <div class="flex flex-row gap-2 items-end">
                <x-groups.cards-buttons modalprefix="targets" routeShow="tenant.targets.show" :routeParams="['target' => $resource->id]"
                    :data="json_encode(['target' => $resource->id])" href="{{ $href ?? '' }}" type="{{ $type }}" />
            </div>
        </div>
    </div>
</x-cards.card>
