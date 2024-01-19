@props([
    'bgcolor' => 'bg-esg2',
    'width' => 'w-2',
    'height' => 'h-2',
    'textcolor' => 'text-esg8',
    'fontsize' => 'text-sm',
    'title' => ''
])
<div {{ $attributes->merge(['class' => 'flex items-start gap-2.5']) }}>
    <div class="w-2">
        <div class="{{ $bgcolor }} {{ $width }} {{ $height }} rounded-full mt-1.5"></div>
    </div>
    <div class="{{ $fontsize }} {{ $textcolor }} grow">
        {{ $title }}
    </div>
</div>
