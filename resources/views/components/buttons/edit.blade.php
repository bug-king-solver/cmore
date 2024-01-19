<button
    @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif
    {{ $attributes->merge(['class' => 'cursor-pointer inline px-2 py-1 text-esg28 uppercase font-inter font-bold text-xs']) }} title="{!! __('Edit') !!}">
        @include('icons/tables/edit',  isset($param) ? json_decode($param, true) : [])
</button>
