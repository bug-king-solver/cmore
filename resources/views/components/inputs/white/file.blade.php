<div class="">
    @if (isset($extra['preview']))
        <div class="mb-2">
            <img alt="{{ __('Preview') }}" class="h-10 w-10 rounded-full" src="{{ $extra['preview'] }}">
        </div>
    @endif

    <div class="">
        <input type="file" wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}">
    </div>
</div>
