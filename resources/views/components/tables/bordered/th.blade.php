@php
    $class = 'text-xs font-semibold text-esg6 px-6 py-4 text-left';
@endphp
<th {{ $attributes->merge(['class' => $class])->except('loop') }}>{{ $slot }}</th>