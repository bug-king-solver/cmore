<div class="px-4 md:px-0">

    <x-slot name="header">
        <x-header title="{{ __('Companies') }}" data-test="companies-header">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('companies.create')
                    <x-buttons.a-icon href="{{ route('tenant.companies.form') }}" data-test="add-data-btn"
                        class="flex place-content-end uppercase">
                        <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                            @include('icons.add', ['color' => color(4)])
                            <label>{{ __('Add') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>


    @php
        $buttons = [
            [
                'route' => 'tenant.companies.index',
                'label' => __('Panel'),
                'icon' => 'panel',
            ],
            [
                'route' => 'tenant.companies.list',
                'label' => __('All'),
                'icon' => 'building-v2',
                'params' => ['s[]' => ''],
                'reference' => null,
            ]
        ];

        if (tenant()->companiesTypeAvailableInMenu) {
            $typeInternal = \App\Models\Enums\Companies\Type::INTERNAL;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $typeInternal->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_type_filter][0]' => $typeInternal->value,
                ],
                'reference' => $typeInternal->value,
            ];

            $typeExternal = \App\Models\Enums\Companies\Type::EXTERNAL;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $typeExternal->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_type_filter][0]' => $typeExternal->value,
                ],
                'reference' => $typeExternal->value,
            ];
        }

        if (tenant()->companiesRelationAvailableInMenu) {
            $relationClient = \App\Models\Enums\Companies\Relation::CLIENT;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $relationClient->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_relation_filter][0]' => $relationClient->value,
                ],
                'reference' => $relationClient->value,
            ];

            $relationSuppplier = \App\Models\Enums\Companies\Relation::SUPPLIER;
            $buttons[] = [
                'route' => 'tenant.companies.list',
                'label' => $relationSuppplier->labelPlural(),
                'icon' => 'building-v2',
                'params' => [
                    's[company_relation_filter][0]' => $relationSuppplier->value,
                ],
                'reference' => $relationSuppplier->value,
            ];
        }

    @endphp

    @php
        $firstArrayKey = array_key_first($this->activeFilters);
        $reference = $this->activeFilters[$firstArrayKey][0] ?? null;
    @endphp

    <x-menus.panel :buttons="$buttons" activated='tenant.companies.list' :reference="$reference" />

    <x-filters.filter-bar-v2 :filters="$availableFilters" :isSearchable="$isSearchable" />

    <x-cards.company.list :companies="$companies" :countries="$countries" grid="4" />

    @if ($companies->isEmpty() && $search)
        <div class="flex justify-center items-center p-6">
            <h3 class="w-fit text-md">
                {{ __('We couldn\'t find any matches for "' . $search . '". Check your search for any typos, try a different search term or use the filters!') }}
            </h3>
        </div>
    @endif

    @if ($companies->isEmpty() && $filterSelected && !$search)
        <div class="flex justify-center items-center p-6">
            <h3 class="w-fit text-md">
                {{ __('We couldn\'t find any matches for the selected filters. Try to change or remove them.') }}</h3>
        </div>
    @endif
</div>
