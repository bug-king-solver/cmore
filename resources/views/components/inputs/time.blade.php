<input wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" type="time" id="{{ $id }}" name="{{ $name ?? $id }}"
{{ $attributes->except('extra')->merge(['class' => 'form-input text-esg29 border-esg6 block w-full min-w-0 flex-1 rounded-md text-lg transition duration-150 ease-in-out']) }}>
