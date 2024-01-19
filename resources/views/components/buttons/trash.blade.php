<button
    @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif
    {{ $attributes->merge(['class' => 'cursor-pointer inline py-1 px-2 text-sm']) }} title="@if(isset($title)) {{ $title }} @else {!! __('Delete') !!} @endif">
    @if(isset($icon))
        @include('icons/' . $icon, isset($param) ? json_decode($param, true) : [])
    @else
        @include('icons/trash', isset($param) ? json_decode($param, true) : [])
    @endif
    {{ $slot ?? '' }}
</button>
