@props(['group', 'groupName' => $group[$name ?? 'name'], 'groupId' => $group['id'], 'chartData' => $group['chartData'] ?? []])

<x-cards.card-version-target-panel text="{{ $groupName }}">
    <x-slot:icon>
        {{-- @include('icons.categories.1', ['width' => 25, 'height' => 25]) --}}
    </x-slot:icon>

    <x-slot:iconarrow>
        <x-buttons.a-icon-simple href="{{ route('tenant.targets.groups', ['groupId' => $groupId]) }}"
            class="px-0 py-0 p-0">
            @include('icons.arrow-right', ['class' => 25, 'height' => 25])
        </x-buttons.a-icon-simple>
    </x-slot:iconarrow>

    <x-slot:canvas>
        <div class="relative">
            <x-charts.donut id="on_target_{{ $groupId }}" class="!h-[200px] !w-[200px]" x-init="$nextTick(() => {
                targetPieCharts(['{{ __('Ongoing') }}', '{{ __('Almost overdue') }}', '{{ __('Overdue') }}'], [
                    {{ $chartData['ongoing'] }},
                    {{ $chartData['almostOverdue'] }},
                    {{ $chartData['overdue'] }}
                    {{-- {{ $chartData['notStarted'] }} --}}
                ], 'on_target_{{ $groupId }}');
            })"
                wire:ignore />
            <div class="absolute flex flex-col justify-center -mt-[5.8rem] -ml-1 w-full text-center">
                <span class="text-lg font-medium text-esg8  ml-2">{{ $chartData['progressOngoing'] }}%</span>
                <span class="text-xs font-normal text-esg8/70 -Pt-10 ml-2">{{ __('Ongoing') }}</span>
            </div>
        </div>
        <div class="relative">
            <x-charts.donut id="over_due_{{ $groupId }}" class="!h-[200px] !w-[200px]" x-init="$nextTick(() => {
                targetPieCharts(['{{ __('Completed') }}', '{{ __('Not Completed') }}'], [
                    {{ $chartData['completed'] }},
                    {{ $chartData['notCompleted'] }}
                ], 'over_due_{{ $groupId }}', ['#99CA3C', '#A2A9B0']);
            })"
                wire:ignore />
            <div class="absolute flex flex-col justify-center -mt-[5.8rem] -ml-1 w-full text-center">
                <span class="text-lg font-medium text-esg8 ml-2">{{ $chartData['progressCompleted'] }}%</span>
                <span class="text-xs font-normal text-esg8/70 -Pt-10 ml-2">{{ __('Completed') }}</span>
            </div>
        </div>
    </x-slot:canvas>
</x-cards.card-version-target-panel>
