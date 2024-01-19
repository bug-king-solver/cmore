<select wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" {{ $attributes->except('extra')->merge(['class' => 'form-input block w-full min-w-0 flex-1 rounded-md text-xs transition duration-150 ease-in-out']) }}>
    <option value="">{{$placeholder ?? ''}}</option>
    @foreach ($extra['options'] as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
