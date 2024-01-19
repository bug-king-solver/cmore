<div>
    <x-slot name="header">
        <x-header title="{{ __('List of Users') }}" data-test="users-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('users.create')
                    <div class="float-right">
                        <x-buttons.a-icon href="{{ route('tenant.users.form') }}" data-test="add-data-btn" class="cursor-pointer flex place-content-end">
                            <div class="flex gap-2 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                                @include('icons.add', ['width' => 12, 'height' => 12, 'color' => color(4)])
                                <label class="uppercase font-medium text-sm cursor-pointer">{{ __('Add') }}</label>
                            </div>
                        </x-buttons.a-icon>
                    </div>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>

    <div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal">

        <x-menus.panel :buttons="[
            [
                'route' => 'tenant.users.index',
                'label' => __('Users'),
                'icon' => 'user',
            ],
            [
                'route' => 'tenant.roles.index',
                'label' => __('Teams'),
                'icon' => 'user',
            ],
        ]" activated='tenant.users.index' />

        <x-filters.filter-bar-v2 :filters="$availableFilters" :isSearchable="$isSearchable" />

        <x-cards.user.list :users="$this->users" />
    </div>
</div>
