@props([
    'isCurrency' => false,
    'isNumber' => false,
    'value' => null,
    'unit' => null,
    'xclass' => null
])

@if($value != "")
    <span class="{{$xclass ?? 'text-lg font-medium text-esg8'}}">
        @if($isCurrency)
            <x-currency :value="$value"></x-currency>
        @elseif($isNumber)
            <x-number :value="$value"></x-number>
        @else
            {{ $value }}
        @endif
        <span class="{{$xunitClass ?? 'text-xs font-normal text-esg16'}}">{{ $unit }}</span>
    </span>
@else
    <label class="{{$xclass ?? 'text-lg font-medium text-esg8'}}">-</label>
@endif