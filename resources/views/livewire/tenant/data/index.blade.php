<div>
    <x-slot name="header">
        <x-header title="{{ __('Data') }}" dataTest="data-header">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <x-menus.panel :buttons="[
        [
            'route' => 'tenant.data.panel',
            'label' => __('Panel'),
            'icon' => 'panel',
        ],
        [
            'route' => 'tenant.data.index',
            'label' => __('Indicators'),
            'icon' => 'building-v2',
        ],
    ]" activated='tenant.data.index' />

    <x-filters.filter-bar :filters="$availableFilters" :isSearchable="$isSearchable" />

    <x-cards.data.list :indicators="$indicators" grid="4"/>
</div>
