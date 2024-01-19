@if (isset($legendes) && $legendes)
    <div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '2' }} gap-10">
        <div class="grid content-center">
            <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }}
                width="{{ $width ?? '' }}"></canvas>
        </div>
        <div class="grid content-center h-full" id="{{ $id . '-legend' }}"></div>
    </div>
@else
    <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }} width="{{ $width ?? '' }}"
        height="{{ $height ?? '' }}"></canvas>
@endif
