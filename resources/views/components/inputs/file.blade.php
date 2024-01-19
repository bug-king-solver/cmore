<div class="flex gap-5 items-center">
    @if (isset($extra['preview']))
        <div>
            <img alt="{{ __('Preview') }}" class="h-10 w-10 rounded-full" src="{{ $extra['preview'] }}">
        </div>
    @endif

    <div class="grow">
        <input type="file" wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" data-test="{{ $dataTest }}" {{ $attributes->except('extra')->merge(['class' => 'form-input text-esg29 border-esg6 block w-full min-w-0 flex-1 rounded-md text-lg transition duration-150 ease-in-out']) }}" data-test="{{ $dataTest ?? null }}">
    </div>
</div>
