@props([
	'name' => null,
	'id' => null,
    'value' => null
])

<div class="flex items-center ml-1" wire:key="{{ $id }}">

<x-buttons.a-icon x-on:click="{{ $id }}.open()" title="{{ __('Open calendar') }}">
    @include('icons.calender', [
        'color' => '#757575',
    ])
</x-buttons.a-icon>

<input type="text" id="{{ $id }}" name="{{ $id }}"
    x-ref="{{ $id }}" x-init="{{ $id }} = flatpickr($refs.{{ $id }}, {
        dateFormat: 'Y-m-d',
        mode: 'range'
    });" {{ $attributes->except('extra')->merge(['class' => 'datepicker']) }}>
</div>
