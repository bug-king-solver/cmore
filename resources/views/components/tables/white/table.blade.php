@php
    $loop = $attributes->get('loop') ? json_decode($attributes->get('loop')) : null;
@endphp
<div class="overflow-x-auto">
    <div class="py-2 inline-block min-w-full">
        <div class="overflow-hidden">
            <table class="min-w-full text-xs" {{ $attributes->merge(['class' => 'min-w-full']) }}>
                @if (isset($thead))
                <thead class="bg-esg6 border-b">
                    <x-tables.white.tr class="rounded-t" loop="{{$loop}}">
                        {{ $thead }}
                    </x-tables.white.tr>
                </thead>
                @endif
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>
