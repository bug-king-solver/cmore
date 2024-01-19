<div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '3' }} gap-9">
    @foreach ($indicators as $indicator)
        <x-cards.list>
            <x-slot:header>
                <div class="flex flex-row gap-2 items-center min-h-[50px] pb-3">
                    <a href="{{ route('tenant.data.indicators.show', ['indicator' => $indicator->id]) }}" class="cursor-pointer">
                        <span class="text-esg8 font-encodesans text-sm font-bold line-clamp-2">
                            {{ $indicator->name }}
                        </span>
                    </a>
                </div>
            </x-slot>

            <x-slot:content>
                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="">
                        <span data-tooltip-target="tooltip-indicator_type" data-tooltip-target="hover">
                            @include('icons.type', ['width' => 16, 'height' => 12, 'color' => color(6)])
                        </span>
                        <div id="tooltip-indicator_type" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Type') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="text-esg16 text-xs font-medium font-encodesans">
                        {{ $indicator->calc == null ? __('Simple') : __('Compound') }}
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="">
                        <span data-tooltip-target="tooltip-indicator_category" data-tooltip-target="hover">
                            @php
                            $icon = null;

                            if ($indicator->category) {
                                $categoryIcon = $indicator->category->parent ?? $indicator->category;
                                $icon = match (strtolower($categoryIcon->getTranslation('name', 'en'))) {
                                    'environment' => '1',
                                    'social' => '2',
                                    'governance' => '1',
                                    default => null
                                };
                            }
                            @endphp
                            @if ($icon)
                                @include('icons.categories.' . $icon, ['width' => 16, 'height' => 14])
                            @endif
                        </span>
                        <div id="tooltip-indicator_category" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Categories') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="text-esg16 text-xs font-medium font-encodesans">
                        {{ $indicator->category ? $indicator->category->name : '-' }}
                    </div>
                </div>

                <div class="flex flex-row gap-2 mt-3 items-center">
                    <div class="">
                        <span data-tooltip-target="tooltip-indicator_unit" data-tooltip-target="hover">
                            @include('icons.unit', ['width' => 16, 'height' => 12, 'color' => color(6)])
                        </span>
                        <div id="tooltip-indicator_unit" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            {{ __('Unit') }}
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <div class="text-esg16 text-xs font-medium font-encodesans">
                        {{ $indicator->unit_default ?? '-' }}
                    </div>
                </div>
            </x-slot:content>

            <x-slot:footer>
                <div class="flex flex-row gap-2 items-between">
                    <div class="absolute left-3">
                        <span class="font-normal text-esg16 text-xs">#{{ $indicator->id }}</span>
                    </div>

                    <x-cards.data.cards-buttons
                        modalprefix="indicators"
                        routeShow="tenant.data.indicator.show"
                        :routeParams="['indicator' => $indicator->id]"
                        :data="json_encode(['indicator' => $indicator->id])"
                        type="page"
                        view="view"
                        viewhref="{{ route('tenant.data.indicators.show', ['indicator' => $indicator->id]) }}" />
                </div>
            </x-slot:footer>
        </x-cards.list>
    @endforeach
</div>

<div class="">
    {{ $indicators->links() }}
</div>
