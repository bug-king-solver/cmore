@php
    $loop = $attributes->get('loop') ? json_decode($attributes->get('loop')) : null;
@endphp
<div id="accordion-flush-body-{{$loop->iteration}}" class="hidden" aria-labelledby="accordion-flush-heading-{{$loop->iteration}}">
    {{ $slot }}
</div>