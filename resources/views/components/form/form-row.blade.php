@props([
    'wire_ignore' => true,
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
    'key' => null,
    'dataDescription' => null,
    'dataTest' => null,
    'modelmodifier' => '.defer',
    'keyDownEnter' => null,
])

<div class="flex relative">
    @if ($input === 'checkbox-inline')
        <div class="mt-4 flex items-baseline">
            <x-dynamic-component component="inputs.checkbox" modelmodifier="{{ $modelmodifier }}" :prop="$prop"
                :id="$id" :name="$name" :extra="$extra" :data-test="$dataTest" />
            <label for="{{ $id }}" {{ $attributes->merge(['class' => 'text-esg29 block text-lg font-bold']) }}>
                {{ $label }}
            </label>
        </div>
    @else
        <div class="mt-4 flex w-1/3 items-center">
            <label for="{{ $id }}" {{ $attributes->merge(['class' => 'text-esg29 block text-lg font-bold']) }}>
                {{ __($label) }}
            </label>

            <x-information id="{{ $id }}">{{ $dataDescription ?? '' }}</x-information>
        </div>

        @if ($errors->has($prop ?? $id))
            <span class="absolute top-0 bottom-3 right-0 flex items-center pr-9 z-10">
                @include('icons/alert-circle')
            </span>
        @endif

        <div class="mt-4 w-2/3">

            @if ($input === 'tomselect')
                <x-inputs.tomselect :id="$id" modelmodifier="{{ $modelmodifier }}" :optgroups="$optgroups"
                    :options="$options" :plugins="$plugins" :items="$items" :limit="$limit" :placeholder="$placeholder"
                    :data-test="$dataTest" :wire_ignore="$wire_ignore" class="{{ $fieldClass ?? '' }}"
                    {{ $attributes->merge([]) }} />
            @else
                <x-dynamic-component component="inputs.{{ $input }}" :id="$id"
                    name="{{ $name ?? $id }}" modelmodifier="{{ $modelmodifier }}" :prop="$prop"
                    :extra="$extra" wire:keydown.enter='{{ $keyDownEnter }}' :placeholder="$placeholder" :data-test="$dataTest"
                    :wire_ignore="$wire_ignore" class="{{ $fieldClass ?? '' }}" {{ $attributes }} />
            @endif

            @error($prop ?? $id)
                <p class="mt-2 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>
    @endif
</div>
