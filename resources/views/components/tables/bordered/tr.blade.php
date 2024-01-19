@php
$class = 'font-medium bg-white border-b';
@endphp
<tr {{ $attributes->merge(['class' => $class])->except('loop') }}>{{ $slot }}</tr>
