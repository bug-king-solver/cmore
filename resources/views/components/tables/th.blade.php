@props([
    'class' => 'px-2 pb-2 text-left text-lg text-esg29 font-encodesans font-semibold',
    'no_border' => false,
])
@php
    if (!$no_border) {
        $class = 'border-b-[1px] border-y-esg5 ' . $class;
    }
@endphp
<th {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</th>
