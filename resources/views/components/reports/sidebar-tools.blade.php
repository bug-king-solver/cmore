<div class="px-4 md:px-0 h-full">
    <div class="flex max-h-[65vh]">
        <aside class="w-16"
            aria-label="Sidebar" x-bind:class="{ 'border rounded': !isOpen }">
            <div class=" shadow-md overflow-y-auto py-4 bg-esg4">
                <ul class="space-y-2">
                    <li class="cursor-pointer w-full h-16 flex items-center justify-center">
                        <span x-on:click="isOpen = false, type = (type ? type : 'open')" x-show="isOpen">
                            @include('icons.close')
                        </span>
                        <span x-on:click="isOpen = true" x-show="!isOpen" style="display: none;">
                            @include('icons.dashboards.side-menu')
                        </span>
                    </li>
                    <x-reports.sidebar-button type="text" icon="text" />
                    <x-reports.sidebar-button type="chart" icon="chart-1" />
                    <x-reports.sidebar-button type="table" icon="table" />
                </ul>
            </div>
        </aside>

        <div class="py-4 bg-[#F0F0F0] w-72 p-4 overflow-y-auto" x-show="isOpen" x-transition>
            <div class="mb-8">
                <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                <div class="relative">
                    <input type="search" id="search" wire:model="search"
                        class="block w-full p-2 text-sm text-gray-900 border-0 rounded bg-esg4 " placeholder="Search" required>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        @include('icons.search', [
                            'color' => color(7),
                            'width' => 18,
                            'height' => 18,
                        ])
                    </div>
                </div>
            </div>

            <div :class="type == 'text' || type == 'open' ? '' : 'hidden'">
                <p class="text-xs font-bold text-center uppercase"> {{ __('Text') }} </p>

                <ul drag-root-text class="cursor-pointer">
                    @foreach ($text as $item)
                        <li drag-item-text draggable="true" id={{ $item['id'] }} name='{{ $item['name'] }}'
                            slug='{{ $item['slug'] }}' struct='{{ json_encode($item['structure']) }}'
                            class="bg-esg4 w-full px-4 py-2 mt-2">
                            <div class="flex justify-between items-center">
                                <div class="grow">
                                    <span {!! substr_replace($item['structure']['attributes'], ' !font-normal', -1, 0) !!}> {{ __($item['name']) }} </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div :class="type == 'chart' || type == 'open' ? '' : 'hidden'">

                <p class="text-xs font-bold text-center mt-6 uppercase">
                    {!! __('Charts and graphs') !!}
                </p>

                <ul drag-root-graphs class="cursor-pointer">
                    @foreach ($graphs as $item)
                        <li drag-item-graph draggable="true" id={{ $item['id'] }} name='{{ $item['name'] }}'
                            slug='{{ $item['slug'] }}' placeholder='{{ global_asset($item['placeholder']) }}'
                            class="bg-esg4 w-full px-4 py-2 mt-2">
                            <div class="flex justify-between items-center">
                                <div class="flex-none w-8">
                                    @include('icons.dashboards.archivement')
                                </div>

                                <div class="grow">
                                    <h1 class="text-xs text-esg8 font-normal"> {{ __($item['name']) }}
                                    </h1>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="w-full mt-2 text-right text-[9px] text-esg11">
                    {{ __('Load More') }} @include('icons.scroll-down', [
                        'class' => 'inline-block ml-1',
                        'color' => color(7),
                        'width' => 8,
                        'height' => 8,
                    ])
                </div>
            </div>

        </div>
    </div>
</div>
