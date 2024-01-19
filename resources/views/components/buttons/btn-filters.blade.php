@props([
    'click' => '',
    'active' => false,
    'count' => '',
    'text' => '',
])

<button wire:click="{{ $click }}"
    class="{{ $active ? 'rounded-md bg-white text-esg6' : 'text-esg16' }} px-4 py-2 rounded-md focus:outline-none drop-shadow px-10 place-items-center cursor-pointer hover:scale-90 transition-all duration-300">
    <div class="flex items-center gap-2">
        {!! __($text) !!}
        <span
            class="{{ $active ? 'bg-esg6' : 'bg-esg73' }} flex items-center w-fit h-5 text-white px-2 py-1 rounded text-xs">{{ $count }}</span>
    </div>
</button>
