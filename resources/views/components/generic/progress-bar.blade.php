@props([
    'progress' => 0,
    'current' => null,
    'total' => null,
])

@php
    switch (true) {
        case $progress <= 25:
            $color = '#c81e1e';
            break;
        case $progress <= 50:
            $color = '#f57c00';
            break;
        case $progress <= 75:
            $color = '#fdd835';
            break;
        case $progress <= 100:
            $color = '#008131';
            break;
        default:
            $color = '#008131';
    }
@endphp

<div class="w-full flex gap-2 items-center">
    <div class="grow h-3 bg-esg7/20">
        <div class="h-3 bg-[{{ $color }}] w-[{{ $progress }}%]"></div>
    </div>
    @if (isset($current) && isset($total))
        <div class="flex flex-row justify-between gap-2">
            <div class="text-sm font-extrabold">{{ $current }}</div>
            <div class="text-sm font-extrabold">/</div>
            <div class="text-sm font-extrabold">{{ $total }}</div>
        </div>
    @else
        <div class="text-sm font-extrabold">{{ $progress }}%</div>
    @endif
</div>
