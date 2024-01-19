<button {{ $attributes->merge(['class' => 'cursor-pointer inline py-1.5 px-2 text-xs']) }}>@include('icons/trash', ['stroke' => $stroke ?? config('theme.default.colors.esg4')])</button>
