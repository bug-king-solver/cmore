@if (isset($legendes) && $legendes)
    <div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '2' }} gap-10 print:gap-2">
        <div class="grid content-center justify-center">
            <div class="!h-[180px] !w-[180px] print:!h-[120px] print:!w-[120px]">
                <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }}></canvas>
            </div>
        </div>
        <div class="grid content-center h-full {{ $legendClass ?? '' }}" id="{{ $id . '-legend' }}"></div>
    </div>
@else
    <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }}></canvas>
@endif
