<div {{ $attributes->merge(['class' => 'border p-4 rounded flex w-full']) }}>
    <div class="right-content grow items-center">
        <h2 class="text-esg16 text-sm">{{ $title }}</h2>
        @if (isset($subtitle))
            {{ $subtitle }}
        @endif
    </div>
    @if (isset($left))
        <div class="left-content flex items-center">
            {{ $left }}
        </div>
    @endif
</div>
