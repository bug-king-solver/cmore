<button
    @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif
    {{ $attributes->merge(['class' => 'inline py-1 px-2 rounded-lg border-2 border-esg5 bg-esg4 text-esg28 uppercase font-inter font-bold text-xs']) }}>
    {{ $buttonicon ?? ($text ?? __('Add')) }}
</button>
