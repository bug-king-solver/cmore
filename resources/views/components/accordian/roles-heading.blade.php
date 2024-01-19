@php
    $loop = $attributes->get('loop') ? json_decode($attributes->get('loop')) : null;
@endphp
<div id="accordion-flush-heading-{{ $loop->iteration }}">
    <div class="flex items-center w-full cursor-pointer text-sm font-medium	px-2 py-4 text-left text-esg8 hover:bg-esg44" data-accordion-target="#accordion-flush-body-{{ $loop->iteration }}" aria-expanded="false" aria-controls="accordion-flush-body-{{ $loop->iteration }}">
        <div class="flex-none w-auto mr-4">
        @include('icons/updown')
        </div>
        
        {{ $heading }}
        <div class="flex-none w-auto">
            {{ $slot }}    
        </div>
    </div>
</div>