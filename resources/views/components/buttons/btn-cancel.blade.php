<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'text-esg6 bg-transparent font-medium border border-esg6 rounded-lg text-sm text-center']) }}>
    {{ $slot }}
</button>