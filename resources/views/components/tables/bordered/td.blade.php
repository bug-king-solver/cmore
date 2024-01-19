@php
$class = 'text-sm px-6 py-4 whitespace-nowrap';
@endphp
<td {{ $attributes->merge(['class' => $class])->except('loop') }}>{{ $slot }}</td>
