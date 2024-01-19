<div>
    <label class="inline-flex relative items-center cursor-pointer">
        <input @if(!isset($checked)) wire:model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}" @endif type="checkbox" id="{{ $id }}" name="{{ $name ?? $id }}" {{ $checked ?? '' }} {{ $disabled ?? '' }} class="sr-only peer" value="{{ $value ?? '' }}" :model{{ $modelmodifier ?? '' }}="{{ $prop ?? $id }}">
        <div {{ $attributes->except('extra')->merge(['class' => "w-9 h-5 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-esg6"]) }}></div>
        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $label ?? ''}}</span>
    </label>
</div>
