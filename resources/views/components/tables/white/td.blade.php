@php
$class = 'text-esg8 py-4 px-2';
@endphp
<td {{ $attributes->merge(['class' => $class])->except('loop') }}>{{ $slot }}</td>
