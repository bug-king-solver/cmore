<div>
    <x-slot name="header">
        <x-header title="{{ __('List of Tags') }}" data-test="tags-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('tags.create')
                    <x-buttons.add modal="tags.modals.form" data-test="add-tags-btn" class="uppercase" />
                @endcan
            </x-slot>
        </x-header>
    </x-slot>

    <x-filters.filter-bar :filters="$availableFilters" />

    <x-cards.tag.list :tags="$tags" />

</div>
