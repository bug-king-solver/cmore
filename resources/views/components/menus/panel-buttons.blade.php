@php
    $active = false;
    $reference = trim($reference);
    if ($reference) {
        $active = isset($button['params']) && count($button['params']) > 0 && $button['reference'] && $activated == $button['route'] && $button['reference'] == $reference;
    } else {
        $active = (!isset($button['params']) || $button['params'] === ['s[]' => '']) && $activated == $button['route'] ? true : false;
    }
@endphp
<a href="{{ route($button['route'], $button['params'] ?? []) }}">
    <div
        class="flex items-center !p-2 w-auto h-10 gap-2 rounded-md cursor-pointer {{ $active ? 'bg-white  text-esg5' : 'text-neutral-500' }}">
        @if (isset($button['icon']))
            @include("icons.{$button['icon']}", [
                'width' => '21.8px',
                'height' => '20px',
                'color' => $active ? color(5) : color(8),
            ])
        @endif
        <span class="font-weight-bold">
            {{ __($button['label']) }}
        </span>
    </div>
</a>
