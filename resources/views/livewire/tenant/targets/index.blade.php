<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Targets') }}" data-test="targets-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                <x-dropdown.groups-button text="{{ __('Add') }}" :dropdowItems="makeMultiArray(
                    [
                        'name' => __('New target'),
                        'icon' => 'icons/add',
                        'customClickEvent' => route('tenant.targets.form') . ($this->parentGroupId != null ? '?group=' . $this->parentGroupId : ''),
                        'customClickParams' => [$this->parentGroupId ?? null],
                        'type' => 'page'
                    ],
                    $groupsButtonDropdown,
                )" />
            </x-slot>
        </x-header>
    </x-slot>

    <div class="flex lg:flex-row md:flex-row sm:flex-col-reverse xs:flex-col-reverse max-w-7xl leading-normal w-full">
        @if ($groupId)
            <div class="w-full flex flex-row gap-2 items-center flex-wrap">
                <div>
                    <x-breadcrums.container>
                        <x-breadcrums.top_li url="{{ route('tenant.targets.index') }}">
                            {{ __('Targets') }}
                        </x-breadcrums.top_li>
                        @foreach ($breadcrumbs as $key => $item)
                            @if (!$loop->last)
                                <x-breadcrums.middle_li url="{{ $item['href'] }}">
                                    {{ $item['text'] }}
                                </x-breadcrums.middle_li>
                            @else
                                <x-breadcrums.last_li>
                                    {{ $item['text'] }}
                                </x-breadcrums.last_li>
                            @endif
                        @endforeach
                    </x-breadcrums.container>
                </div>
            </div>
        @endif
    </div>


    @if ($groupId)
        <div class="flex flex-row mx-auto max-w-7xl leading-normal mb-10 mt-10 w-full">
            <div class="w-full flex flex-row gap-2 items-center">
                <span
                    class="text-esg8 text-2xl font-bold lg:max-w-[80%] md:max-w-[80%] lg:w-auto md:w-auto sm:w-full xs:w-full">
                    {{ $parentGroup->name }}
                </span>
                <div class="flex flex-row gap-1 items-end">
                    <x-buttons.edit modal="groups.modals.form" :data="json_encode(['group' => $parentGroup->id])" />
                    <x-buttons.trash modal="groups.modals.delete" :data="json_encode(['group' => $parentGroup->id, 'redirect' => true])" class="px-1 py-1" />
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-9">

        <x-cards.card-version-target-panel-v2 text="{{ __('Ongoing') }}" wirekey="">
            <x-groups.targets.dropdown-filter-date :title="$this->ongoingInterval['title']" event="changeOngoingInterval" />
            <x-slot:data>
                <x-groups.targets.card-report :total="$this->ongoingTasks['total']">

                    <x-charts.multiline id="ongoing" class="!h-[100px] !w-[200px]" wire:ignore
                        wire:key="ongoing_{{ $this->ongoingInterval['interval'] }}" x-init="$nextTick(() => { multilineCharts('ongoing', '{{ json_encode($this->ongoingTasks['labels'], true) }}', '{{ json_encode($this->ongoingTasks['totalColumns'], true) }}', '{{ json_encode($this->ongoingTasks['totaisCreated'], true) }}'); });" />

                </x-groups.targets.card-report>
            </x-slot:data>
        </x-cards.card-version-target-panel-v2>

        <x-cards.card-version-target-panel-v2 text="{{ __('Completed') }}" wirekey="">
            <x-groups.targets.dropdown-filter-date :title="$this->completedInterval['title']" event="changeCompletedInterval" />
            <x-slot:data>
                <x-groups.targets.card-report :total="$this->completedTasks['total']">

                    <x-charts.multiline id="completed" class="!h-[100px] !w-[200px]" wire:ignore
                        wire:key="completed_{{ $this->completedInterval['interval'] }}" x-init="$nextTick(() => { multilineCharts('completed', '{{ json_encode($this->completedTasks['labels'], true) }}', '{{ json_encode($this->completedTasks['totalColumns'], true) }}', '{{ json_encode($this->completedTasks['totaisCreated'], true) }}'); });" />

                </x-groups.targets.card-report>
            </x-slot:data>
        </x-cards.card-version-target-panel-v2>

        <x-cards.card-version-target-panel-v2 text="{{ __('Overdue') }}" wirekey="">
            <x-groups.targets.dropdown-filter-date :title="$this->overdueInterval['title']" event="changeOverdueInterval" />
            <x-slot:data>
                <x-groups.targets.card-report :total="$this->overdueTasks['total']">
                    <x-charts.multiline id="overdue" class="!h-[100px] !w-[200px]" wire:ignore
                        wire:key="overdue_{{ $this->overdueInterval['interval'] }}" x-init="$nextTick(() => { multilineCharts('overdue', '{{ json_encode($this->overdueTasks['labels'], true) }}', '{{ json_encode($this->overdueTasks['totalColumns'], true) }}', '{{ json_encode($this->overdueTasks['totaisCreated'], true) }}'); });" />
                </x-groups.targets.card-report>
            </x-slot:data>
        </x-cards.card-version-target-panel-v2>
    </div>

    <x-groups.groups :groups="$targetGroups" :parentGroup="$parentGroup" :groupId="$groupId" :groupLevelDescription="$groupLevelDescription" type="page" />

    <x-tasks.table :tasks="$this->targetTasks" entity='targets' />
</div>
