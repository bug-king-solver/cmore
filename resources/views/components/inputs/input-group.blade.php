<div class="flex mt-2">
    <input wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? ($id ?? null) }}" type="{{ $type ?? 'text' }}"
        id="{{ $id ?? null }}" name="{{ $name ?? ($id ?? null) }}"
        {{ $attributes->except('extra')->merge(['class' => 'rounded-none rounded-l-md bg-esg4 border text-esg8 block flex-1 min-w-0 w-full text-sm border-esg7/60 p-2 duration-300']) }}
        placeholder=" {{ $placeholder ?? 'cmore' }}" value="{{ $value ?? null }}">

    <span
        class="inline-flex items-center px-3 text-sm text-esg8 bg-esg7/30 border border-r-0 border-esg7/60 rounded-r-md">
        {{ $unit ?? '' }}
    </span>
</div>
