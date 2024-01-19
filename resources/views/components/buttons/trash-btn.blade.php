<div @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif
{{ $attributes->merge(['class' => 'py-2 px-3 border border-esg8 rounded-md text-esg8 bg-esg27 cursor-pointer']) }}>

<div class="flex flex-row gap-1 items-center cursor-pointer">
@include('icons/trash', ['height' => '16', 'width' => '16'])
@if(isset($title)) {{ $title }} @else {!! __('Delete') !!} @endif
</div>

</div>
