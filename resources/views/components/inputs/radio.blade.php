@props([
    'checked' => false,
    'disabled' => false,
    'id' => null,
    'name' => null,
    'label' => null,
    'modelmodifier' => null,
    'prop' => null,
    'wireClick' => null,
    'key' => null,
])
<label for="{{$id}}">
<input type="radio" id="{{ $id }}"
    name="{{ $name ?? $id }}" {{ $attributes->except('extra')->merge(['class' => 'mb-1 checked:bg-esg6']) }}
    {{ $checked }} {{ $disabled }}>{{ $label ?? '' }}</label>
