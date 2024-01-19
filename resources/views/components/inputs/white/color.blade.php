<div
    x-data="{ color: '{{ $colorvalue ?? $this->color }}' }"
    x-init="
        picker = new Picker($refs.button);
        picker.onDone = rawColor => {
            color = rawColor.{{$type ?? 'rgbaString'}};
            $dispatch('input', color)
        }
    "
    wire:model="{{ $prop ?? $id }}"
    wire:ignore
    style="z-index:99999"
>
    <input readonly x-model="color" type="text" id="{{ $id }}" name="{{ $name ?? $id }}" :style="`background: ${color}`" class='form-input block text-esg6 border border-esg6 max-w-[250px] min-w-0 flex-1 rounded-md text-lg transition duration-150 ease-in-out p-2' placeholder="#ffffff">
    <button x-ref="button" >{{ __('Change') }}</button>
</div>
