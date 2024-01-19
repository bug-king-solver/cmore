@props([
    'checked' => false,
    'id' => null,
    'name' => null,
    'label' => null,
    'modelmodifier' => null,
    'prop' => null,
    'wireClick' => null,
    'key' => null,
    'icon' => null,
    'labelclass' => '',
])
<div class="flex items-center text-sm">
    <input wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" type="checkbox" id="{{ $id }}"
        name="{{ $name ?? $id }}" {{ $attributes->except('extra')->merge(['class' => 'checked:bg-esg6 mr-2']) }}
        {{ $checked }} wire:click="{{ $wireClick ?? null }}">
    <label for="{{ $id }}" class="{{ $labelclass }}"> {{ $label ?? '' }}</label>
    @if (isset($icon))
        <div class="ml-2"> @include('icons.' . $icon) </div>
    @endif

</div>
