@props([
    'content' => null,
])
<div
    {{ $attributes->merge(['class' => 'flex flex-col w-full border border-1 border-[#E1E6EF] rounded-md p-[16px] gap-[15px] max-w-[287px] justify-between']) }}>
    @if ($content)
        {{ $content }}
    @endif

    {{ $slot }}
</div>
