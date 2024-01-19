<select wire:model{{ isset($modelmodifier) ? ".{$modelmodifier}" : '' }}="{{ $prop ?? $id }}" {{ $attributes->merge(['class' => 'form-input text-esg29 border-esg6 w-32 rounded-md transition duration-150 ease-in-out'])}}>
    @foreach (getUnitsForSelect($unitqty) as $unit)
        <option value="{{ $unit['id'] }}">{{ $unit['title'] }}</option>
    @endforeach
</select>
