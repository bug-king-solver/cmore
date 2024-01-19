@props([
    'checked' => false,
    'id' => null,
    'name' => null,
    'label' => null,
    'modelmodifier' => null,
    'prop' => null,
    'wireClick' => null,
    'key' => null,
])

<input wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" type="checkbox" id="{{ $id }}"
    name="{{ $name ?? $id }}" {{ $attributes->except('extra')->merge(['class' => 'checked:bg-esg6 mr-3.5']) }}
    {{ $checked }} wire:click="{{ $wireClick ?? null }}">
{{ $label ?? '' }}
