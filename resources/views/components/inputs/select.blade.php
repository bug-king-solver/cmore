<select wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}"
    {{ $attributes->except('extra')->merge(['class' => 'form-input text-esg29 border-esg6 block w-full min-w-0 flex-1 rounded-md text-sm transition duration-150 ease-in-out']) }}>
    @if (!isset($extra['show_blank_opt']) || $extra['show_blank_opt'])
        <option value="" class="text-esg16">{{ $default ?? $placeholder ?? '' }}</option>
    @endif
    @foreach ($extra['options'] as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
