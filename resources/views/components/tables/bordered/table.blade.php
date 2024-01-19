@php
    $loop = $attributes->get('loop') ? json_decode($attributes->get('loop')) : null;
@endphp

<table class="min-w-full text-xs" {{ $attributes->merge(['class' => 'min-w-full']) }}>
    @if (isset($thead))
    <thead class="bg-white border-b border-esg43">
        <x-tables.bordered.tr class="rounded-t" loop="{{$loop}}">
            {{ $thead }}
        </x-tables.bordered.tr>
    </thead>
    @endif
    <tbody>
        {{ $slot }}
    </tbody>
</table>
