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
    'keyDownEnter' => null,
    'key' => null,
    'form_div_size' => 'w-2/3',
    'disabled' => false,
    'dataDescription' => null,
    'dataTest' => null,
    'fieldClass' => '',
    'default' => '',
    'modelmodifier' => '.defer',
    'flex' => false,
])
<div class="{{ $flex == true ? 'flex gap-3 items-center' : '' }} relative">
    @if ($label)
        <div class="mt-4 flex {{ $form_div_size }} items-center">
            <label for="{{ $id }}"
                {{ $attributes->merge(['class' => 'text-esg29 block text-lg font-normal']) }}>
                {{ __($label) }}
            </label>

            <x-information id="{{ $id }}">{{ $dataDescription ?? '' }}</x-information>
        </div>
    @endif

    @if ($errors->has($prop ?? $id))
        <span class="absolute bottom-11 z-10 {{ $input == 'date' ? 'right-11' : 'right-4' }}">
            @include('icons/alert-circle')
        </span>
    @endif

    <div class="mt-2 {{ $form_div_size }} ">
        @if ($input === 'tomselect')
            <x-inputs.tomselect :id="$id"
                :optgroups="$optgroups"
                :options="$options"
                :plugins="$plugins"
                :items="$items" :limit="$limit"
                :placeholder="$placeholder"
                :data-test="$dataTest"
                :wire_ignore="$wire_ignore"
                class="{{ $fieldClass ?? '' }}"
                modelmodifier="{{ $modelmodifier }}"  {{ $attributes->merge([]) }} />
        @else
            <x-dynamic-component component="inputs.{{ $input }}"
                :id="$id" name="{{ $name ?? $id }}"
                modelmodifier="{{ $modelmodifier }}"
                :prop="$prop" :extra="$extra"
                wire:keydown.enter='{{ $keyDownEnter }}'
                :placeholder="$placeholder"
                :data-test="$dataTest"
                :wire_ignore="$wire_ignore"
                class="{{ $fieldClass ?? '' }}" {{ $attributes }} />
        @endif

        @error($prop ?? $id)
            <p class="mt-2 text-sm text-red-500">
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
