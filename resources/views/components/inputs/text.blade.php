<input {{ $disabled ?? false ? 'disabled' : '' }}
    {{ $attributes->except('extra')->merge(['class' => 'form-input text-esg29 block w-full min-w-0 flex-1 rounded-md text-lg placeholder:text-esg7 duration-300 border-esg67 focus:border-esg6 focus:ring-0']) }}
    wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" type="text" id="{{ $id }}"
    name="{{ $name ?? $id }}" {{ ($disabled ?? false) ? 'disabled' : '' }}>
