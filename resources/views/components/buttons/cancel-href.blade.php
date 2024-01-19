<a type="button"
    {{ $attributes->merge(['class' => 'cursor-pointer inline px-2 py-1 rounded bg-esg4 border-[1px] border-esg8 text-esg8 uppercase font-inter font-bold text-xs flex items-center']) }}>
    {!! $text ?? __('Cancel') !!}
</a>
