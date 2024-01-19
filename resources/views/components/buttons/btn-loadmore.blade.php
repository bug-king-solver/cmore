<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'inline-flex items-center my-4 py-2 px-4 bg-transparent text-esg7 font-bold text-xs']) }}>
    {{ $buttonicon }} {{ $slot }}
</button>
