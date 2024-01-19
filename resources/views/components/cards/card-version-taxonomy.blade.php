<div class="relative bg-esg4 rounded-md border border-esg7/50 p-2">
    <div class="text-esg29 font-encodesans flex h-auto text-lg font-bold">
        <span class="px-2 flex flex-row justify-between w-full items-center">

            <div class="pb-3 pt-1 flex flex-row items-center w-full border-b border-esg7/30">
                <span class="text-esg29 font-encodesans font-xs line-clamp-2">
                    {{ $resource['name'] }}
                </span>
            </div>

            @if (isset($resource->status))
                <span class="pr-2">
                    <x-groups.status-circle :status="$resource->status" />
                </span>
            @endif
        </span>
    </div>

    <div class="text-esg29 font-encodesans w-full h-auto">
        <div class="p-2 mb-7 mt-2 w-full h-full hidden">
            <div class="flex flex-row gap-1 mt-4 items-center">
                <div>@include('icons.briefcase', ['width' => 25, 'height' => 18])</div>
                <div class="">
                    {{ $resource->our_reference ?? 'n/a' }}
                </div>
            </div>

            <div class="flex flex-row gap-1 mt-5 items-center">
                <div>@include('icons.building-v1', ['width' => 25, 'height' => 18, 'color' => 'green'])</div>
                <div class="">
                    {{ $resource->our_reference ?? 'n/a' }}
                </div>
            </div>

            <div class="flex flex-row gap-1 mt-5 items-center">
                <div>@include('icons.activity', ['width' => 25, 'height' => 18, 'color' => '#44724D'])</div>
                <div class="">
                    {{ $resource->our_reference ?? 'n/a' }}
                </div>
            </div>
        </div>

        <div class="p-2 mb-7 mt-2 w-full h-full">
            <div class="flex flex-row gap-1 mt-4 items-center">
                <div>@include('icons.activity', ['width' => 25, 'height' => 18, 'color' => '#44724D'])</div>
                <div class="">
                    2022
                </div>
            </div>
        </div>
    </div>

    <div class="px-2 w-full flex flex-col justify-center bottom-0">
        <div class="flex flex-row gap-2 justify-between items-center w-full pt-3 pb-1 border-t border-esg7/30">
            <div class="flex flex-row justify-center items-center gap-1 bg-esg7/30 py-1 px-3 rounded-md">
                @include('icons.calander', ['width' => 20, 'height' => 20])
                <span class="text-esg8 font-xs">
                    {{-- {{ carbon()->parse($resource['year'] ?? $resource['created_at'])->format('Y') }} --}}
                    2022
                </span>
            </div>

            <div class="flex flex-row gap-1 items-end">
                <x-groups.cards-buttons modalprefix="taxonomy" routeShow="tenant.taxonomy.show" :routeParams="['taxonomy' => $resource['id']]" :data="json_encode(['taxonomy' => $resource['id']])" />
            </div>
        </div>
    </div>
</div>
