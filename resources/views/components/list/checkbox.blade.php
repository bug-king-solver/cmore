<div {{ $attributes->merge(['class' => 'flex items-center justify-between border-b border-b-esg7/30 w-full py-3 gap-4']) }}>
    <div class="">
        <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $label ?? '' }}</p>
    </div>
    <div class="">
        @if ($status == true)
            @include('icons.checkbox', ['color' =>  color( $color ?? 2)])
        @else
            @include('icons.checkbox-no')
        @endif
    </div>
</div>
