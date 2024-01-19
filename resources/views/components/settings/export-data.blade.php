@props([
    'name' => '',
    'description' => '',
    'click' => null,
])

<div class="flex flex-row items-center justify-between mb-6">
    <div class="flex flex-col gap-2 w-96">
        <span class="text-esg29 text-base">{!! __($name) !!}</span>
        <span
            class="text-esg8 text-xs">{{ __($description) }}</span>
    </div>
    <div class="flex-1 flex justify-center">
        <button class="flex items-center py-2 px-4 rounded-md border border-esg16" wire:click={{ $click }}>
            <span class="font-normal flex gap-2 text-xs text-esg16 items-center">
                @include('icons/tables/download', ['color' => color(16)]) {!! __('Download') !!}
            </span>
        </button>
    </div>
</div>