@php
    $loop = $attributes->get('loop') ? json_decode($attributes->get('loop')) : null;
@endphp
<div id="accordion-flush-heading-{{ $loop->iteration }}">
    
    <div class="flex items-center justify-between w-full cursor-pointer text-xs font-medium	px-2 py-4 text-left text-esg8 border-b border-esg38 hover:bg-esg37" data-accordion-target="#accordion-flush-body-{{ $loop->iteration }}" aria-expanded="false" aria-controls="accordion-flush-body-{{ $loop->iteration }}">
        {{ $heading }}
        <div class="flex-none w-auto">
            {{ $slot }}    
        </div>
        <div class="">
            @include('icons/legislation/dropdown')
        </div>
    </div>
</div>