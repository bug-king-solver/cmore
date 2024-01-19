@props([
	'items' => null,
    'extra' => [],
    'placeholder' => null,
])
<select {{ $attributes->except('extra')->merge(['class' => 'form-input block w-full min-w-0 flex-1 rounded-md transition duration-150 ease-in-out']) }}>
    @if($placeholder)
        <option value="">{{ $placeholder ?? '' }}</option>
    @endif
    @foreach ($extra['options'] as $key => $value)
        <option value="{{ $value['id'] }}" {{ $value['id'
        ] == $items ? 'selected' : '' }}>{{ $value['title'] }}
        </option>
    @endforeach
</select>
