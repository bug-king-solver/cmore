@php
    $loop = $attributes->get('loop') ? json_decode($attributes->get('loop')) : null;
@endphp
<div id="accordion-flush-body-{{$loop->iteration}}" class="hidden" aria-labelledby="accordion-flush-heading-{{$loop->iteration}}">
    <div class="py-3 px-4 pl-12 text-sm text-esg8 bg-esg37 dark:border-esg38">
        {{ $slot }}
    </div>
</div>