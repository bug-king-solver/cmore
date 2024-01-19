<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'bg-esg5 p-2 font-encodesans font-semibold text-2xl text-esg27 rounded-lg']) }}>
    {{ $text }}
</button>