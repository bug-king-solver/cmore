<div class="relative flex overflow-hidden gap-1">
    <img class="rounded-full {{ $w ?? 'w-10' }} {{ $h ?? 'h-10' }}" src="{{ htmlspecialchars_decode($url) ?? '' }}"
        alt="{{ $alt ?? '' }}">
</div>
