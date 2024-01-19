<div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '3' }} gap-9">
    @foreach ($companies as $company)
        <x-cards.list>
            <x-slot:header>
                <div class="flex flex-row gap-2 items-center min-h-[50px] pb-3">
                    <a href="{{ $url ?? '#' }}" class="cursor-pointer">
                        <span class="text-esg29 font-encodesans text-sm font-bold line-clamp-2">
                            {{ $company->commercial_name ? $company->commercial_name : $company->name }}
                        </span>
                    </a>
                </div>
            </x-slot>

            <x-slot:content>
                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="">
                        <span data-tooltip-target="tooltip-company-type" data-tooltip-target="hover">
                            @include('icons.type', ['width' => 20, 'height' => 16, 'color' => color(6)])
                        </span>
                        <div id="tooltip-company-type" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Type') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="text-esg16 text-sm font-encodesans font-medium">
                        {{ $company->type->label() }} @if ($company->is_external && $company->relations->count()) ({{ $company->relations->map(fn ($relation) => $relation->label())->join(', ') }}) @endif
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-center ">
                    <div class="">
                        <span data-tooltip-target="tooltip-compnay-sector" data-tooltip-target="hover">
                            @include('icons.briefcase', ['color' => color(6)])
                        </span>
                        <div id="tooltip-compnay-sector" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Sector') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="">
                        <p class="line-clamp-2 text-sm font-encodesans text-esg16 font-medium">
                            {{ $company->business_sector->name ?? 'n/a' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-center ">
                    <div class="">
                        <span data-tooltip-target="tooltip-compnay-nif" data-tooltip-target="hover">
                            @include('icons.building-v1', ['color' => color(6)])
                        </span>
                        <div id="tooltip-compnay-nif" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Nif') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="">
                        <p class="line-clamp-2 text-sm font-encodesans text-esg16 font-medium">
                            {{ $company->vat_number ?? 'n/a' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-baseline ">
                    @if ($company->country)
                        <div class="w-4">
                            @include('vendor/flag-icons/flags/4x3/' . strtolower($countries[$company->country]['cca2']))
                        </div>
                        <div class="text-sm font-encodesans text-esg16 font-medium">
                            {{ $countries[$company->country]['name'] }}
                        </div>
                    @endif
                </div>
            </x-slot>

            <x-slot:footer>
                <div class="flex flex-row gap-2 items-end">
                    <x-cards.cards-buttons
                        modalprefix="companies"
                        routeShow="tenant.companies.show"
                        :routeParams="['company' => $company->id]"
                        :data="json_encode(['company' => $company->id])"
                        href="{{ route('tenant.companies.form', ['company' => $company->id]) }}"
                        type="page"
                        view="view"
                        viewhref="{{ route('tenant.companies.show', ['company' => $company->id]) }}" />
                </div>
            </x-slot>
        </x-cards.list>
    @endforeach
</div>

<div class="mb-10">
    {{ $companies->links() }}
</div>
