<a
    {{ $attributes->merge(['class' => 'cursor-pointer inline px-2 py-1 text-esg28 uppercase font-inter font-bold text-xs']) }} title="{!! __('Edit') !!}">
        @include('icons/tables/edit',  isset($param) ? json_decode($param, true) : [])
</a>
