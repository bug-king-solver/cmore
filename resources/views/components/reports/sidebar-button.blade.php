@props([
    'type' => null,
    'icon' => null,
])
<div>
    <li class="cursor-pointer w-full h-16 flex items-center justify-center"
        x-on:click="isOpen = true, type = type == '{{ $type }}' ? 'open' : '{{ $type }}'"
        :class="type == '{{ $type }}' ? 'bg-[#FDF2ED] border-r-4 border-r-[#E86321]' : ''">
        <span x-show="type == '{{ $type }}'" style="display: none;">
            @include("icons.dashboards.$icon", ['color' => '#E86321'])
        </span>
        <span x-show="type != '{{ $type }}'">
            @include("icons.dashboards.$icon")
        </span>
    </li>
</div>
