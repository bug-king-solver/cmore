<button
    @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center py-2 px-4 bg-esg5 text-white uppercase font-inter font-bold text-xs border rounded-md mb-2 float-left md:float-right']) }}>@include('icons/tables/plus')
    {{ __('Add' )}}
</button>
</br>
