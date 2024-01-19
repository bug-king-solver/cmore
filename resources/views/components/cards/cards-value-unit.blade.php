@props([
    'isCurrency' => false,
    'isNumber' => false,
    'value' => null,
    'unit' => null,
    'xclass' => null,
    'xunitClass' => null
])
@if($value != "")
    <label for="checkbox-website" class="{{$xclass ?? 'font-encodesans font-medium text-4xl text-esg8'}}">
        @if($isCurrency)
            <x-currency :value="$value" :withoutSymbol=true></x-currency>
        @elseif($isNumber)
            <x-number :value="$value"></x-number>
        @else
            {{ $value }}
        @endif
        @if(isset($unit) && $unit !="")
            <span class="{{$xunitClass ?? 'text-base text-esg8'}}">{{ $unit }}</span>
        @endif
    </label>
@else
    <label for="checkbox-website" class="{{$xclass ?? 'font-encodesans font-medium text-4xl text-esg8'}}">-</label>
@endif
