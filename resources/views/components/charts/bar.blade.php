@if (isset($unit) && $unit != null)
    @if (isset($legend) && $legend != null)
        <div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '2' }} gap-10">
            <div>
                <div class="flex justify-end">
                    <span class="text-esg16 text-xs bg-esg7/10 px-2 py-1 rounded">{{ __('unit: ') }} <span class="font-extrabold">{{ $unit }}</span></span>
                </div>

                <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }}></canvas>
            </div>
            <div class="grid content-center h-full" id="{{ $id . '-legend' }}"></div>
        </div>
    @else
        @if(isset($filters))
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="">
                    {{ $filters ?? '' }}
                </div>
                <div class="flex justify-end">
                    <span class="text-esg16 text-xs bg-esg7/10 px-2 py-1 rounded">{{ __('unit: ') }} <span class="font-extrabold">{{ $unit }}</span></span>
                </div>
            </div>
        @else
            <div class="flex justify-end">
                <span class="text-esg16 text-xs bg-esg7/10 px-2 py-1 rounded">{{ __('unit: ') }} <span class="font-extrabold">{{ $unit }}</span></span>
            </div>
        @endif
        <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }}></canvas>
    @endif
@else
    @if (isset($legend) && $legend != null)
        <div class="grid grid-cols-1 md:grid-cols-{{ $grid ?? '2' }} gap-10">
            <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }}></canvas>
            <div class="grid content-center h-full" id="{{ $id . '-legend' }}"></div>
        </div>
    @else
        <canvas id="{{ $id }}" {{ $attributes->merge(['class' => '']) }}></canvas>
    @endif
@endif

@if (isset($subinfo) && $subinfo != null)
    <div class="flex items-center justify-center gap-5 mt-5">
        <div class="text-sm text-esg16"> {{ __('Total:') }} </div>
        @foreach(json_decode(htmlspecialchars_decode($subinfo), true) as $value)
            <div class="text-2xl font-medium {{ $value['color'] ?? 'text-esg8' }}">
                <x-number :value="$value['value']" />
                <span class="text-sm font-normal">{{ $value['unit'] }}</span>
            </div>
        @endforeach
    </div>
@endif
