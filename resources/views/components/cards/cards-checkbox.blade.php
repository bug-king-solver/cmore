@if ($value == 'yes' || $value == '1')
    @include('icons.checkbox', ['color' =>  $color])
@elseif($value == 'no' || $value == '0')
    @include('icons.no')
@else
    -
@endif
