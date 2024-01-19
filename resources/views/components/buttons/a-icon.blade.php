<a @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'cursor-pointer inline py-1 px-2 text-sm']) }}>
    {{ $slot }}
</a>
