<input
    {{ $attributes->except('extra')->merge(['class' => 'form-input text-sm py-2	px-3 bg-esg59 border-esg60 text-esg8 block w-full min-w-0 flex-1 rounded transition duration-150 ease-in-out']) }}
    wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" type="text" id="{{ $id }}"
    name="{{ $name ?? $id }}">
