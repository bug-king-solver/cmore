<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'text-esg8 bg-esg43 font-medium rounded text-sm py-1	px-2.5 text-center']) }}>
    {{ $slot }}
</button>