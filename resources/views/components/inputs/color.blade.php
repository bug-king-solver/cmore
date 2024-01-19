<div
    x-data="{ color: '{{ $colorvalue ?? $this->color }}' }"
    x-init="
        document.getElementById('{{$id}}').style.background = color
        picker = new Picker($refs.button);
        picker.onDone = rawColor => {
            color = rawColor.{{$type ?? 'rgbaString'}};
            $dispatch('input', color)
            document.getElementById('{{$id}}').style.background = color
        }
    "
    wire:model="{{ $prop ?? $id }}"
    wire:ignore
    class="z-[99999]"
>
    <input readonly x-model="color" type="text" id="{{ $id }}" name="{{ $name ?? $id }}" class='form-input block text-esg6 border border-esg6 max-w-[250px] min-w-0 flex-1 rounded-md text-lg transition duration-150 ease-in-out p-2' placeholder="#ffffff" data-test="{{ $dataTest ?? null }}">
    <button x-ref="button" >{{ __('Change') }}</button>
</div>
