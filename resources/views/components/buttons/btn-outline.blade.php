<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'text-esg6 bg-transparent border border-esg6 rounded text-sm']) }}>
    {{ $slot }}
</button>