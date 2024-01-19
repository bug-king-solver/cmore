<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'text-esg28 cursor-pointer']) }}>
    {{ $slot }}
</button>
