<div
    x-data="{ color: '{{ $colorvalue ?? $this->color }}' }"
    x-init="
        document.getElementById('{{$id . '_color'}}').style.background = color
        picker = new Picker($refs.button);
        picker.onDone = rawColor => {
            color = rawColor.{{$type ?? 'rgbaString'}};
            $dispatch('input', color)
            document.getElementById('{{$id . '_color'}}').style.background = color
        }
    "
    wire:model="{{ $prop ?? $id }}"
    wire:ignore
    class="z-[99999]"
>
    <div class="flex border border-esg7 rounded-md w-full" x-ref="button">
        <div class="w-16 h-12 rounded-l-md cursor-pointer" id={{ $id . '_color' }}> </div>
        <div>
            <input readonly x-model="color" type="text"
                id="{{ $id }}"
                name="{{ $name ?? $id }}"
                class='form-input block text-esg8/70 border-0 rounded-md text-lg transition duration-150 ease-in-out p-2 mt-0.5'
                placeholder="#ffffff"
                data-test="{{ $dataTest ?? null }}" />
        </div>
    </div>
    <span class="text-xs text-esg7">({{ __('Click above to change the colors') }})</span>
</div>
