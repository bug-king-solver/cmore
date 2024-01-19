@php
$class = 'text-left py-4 text-xs font-medium';
$loop = $attributes->get('loop') ? json_decode($attributes->get('loop')) : null;
if ($loop) {
    $class .= $loop->first ? ' pl-2' : '';
    $class .= $loop->last ? ' pr-2' : '';
}
@endphp
<th {{ $attributes->merge(['class' => $class])->except('loop') }}>{{ $slot }}</th>