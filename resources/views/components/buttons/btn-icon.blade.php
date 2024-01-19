<button
    @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif
    {{ $attributes->merge(['class' => 'cursor-pointer inline py-1.5 px-2 text-sm']) }}>
    {{ $buttonicon ?? $slot }}
</button>
