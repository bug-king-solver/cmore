<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'inline py-1.5 px-2 rounded-lg bg-esg5 text-esg27 uppercase font-inter font-bold text-xs']) }}>
    {{ $text }}
</button>
