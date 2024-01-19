<div>
    <x-slot name="header">
        <x-header title="{{ __('Tasks') }}" textcolor="text-esg6">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @if (isset($entity))
                    <x-buttons.a-icon
                        href="{{ route('tenant.users.tasks.form') }}?entity={{ $entity . (isset($entityId) ? '&entityId=' . $entityId : '') }}"
                        data-test="add-data-btn" class="flex place-content-end">
                        <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                            @include('icons.add', ['color' => color(4)])
                            <label class="cursor-pointer">{{ __('New Task') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @else
                    <x-buttons.a-icon href="{{ route('tenant.users.tasks.form') }}" data-test="add-data-btn"
                        class="flex place-content-end">
                        <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                            @include('icons.add', ['color' => color(4)])
                            <label class="cursor-pointer">{{ __('New Task') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @endif
            </x-slot>
        </x-header>
    </x-slot>

    @if (tenant()->hasTasksFeature)
        <div class="flex space-x-4 mb-6 items-center gap-5 p-2 bg-esg72 rounded-md">
            <x-buttons.btn-filters click="$set('filter', 'todo')" :active="$filter === 'todo'" :count="$todoCount" text="To-do" />

            <x-buttons.btn-filters click="$set('filter', 'done')" :active="$filter === 'done'" :count="$doneCount" text="Done" />
        </div>
    @endif

    <x-filters.filter-bar :filters="$availableFilters" :isSearchable="$isSearchable" />

    <x-cards.task.list :tasks="$tasks" />
</div>
