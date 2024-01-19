@props([
    'input' => null,
    'prop' => null,
    'id' => null,
    'name' => null,
    'extra' => [],
    'label' => null,
    'optgroups' => null,
    'options' => null,
    'plugins' => null,
    'items' => null,
    'limit' => null,
    'placeholder' => null,
    'allowcreate' => null,
])

<div>
    <div class="flex items-center">
        <label for="{{ $id }}"
            {{ $attributes->merge(['class' => 'block mb-1 text-sm font-normal text-esg8']) }}>{{ $label }}</label>
    </div>

    @if ($input === 'tomselect')
        <x-inputs.tomselect id="{{ $id }}" :label="$label" wire:model.defer="{{ $prop ?? $id }}"
            :optgroups="$optgroups" :options="$options" :plugins="$plugins" :items="$items" :limit="$limit" :allowcreate="$allowcreate"
            :placeholder="$placeholder" />
    @else
        <x-dynamic-component component="inputs.white.{{ $input }}" modelmodifier=".defer" :prop="$prop"
            :id="$id" :name="$name" :extra="$extra" :placeholder="$placeholder ?? $label" />
    @endif
    @error($prop ?? $id)
        <p class="mt-2 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror
</div>
