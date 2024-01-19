@props([
    'isLeft' => false,
    'classContainer' => 'relative w-full',
    'classIcon' => 'absolute inset-y-0 flex items-center pointer-events-none',
    'classInput' => 'w-full py-1 rounded-lg bg-white border-none focus:outline-none text-[#444444]',
    'placeholder' => __('Search'),
    'disabled' => false,
    'model' => null,
])
@php
    if ($isLeft) {
        $classIcon .= 'left-0';
        $classInput .= ' pr-4 pl-6';
    } else {
        $classIcon .= ' right-0 pr-3';
        $classInput .= ' pl-4 pr-10';
    }
@endphp
<div class="flex items-center">
    <div {{ $attributes->merge(['class' => $classContainer]) }}>
        <div class="{{ $classIcon }}">
            @include('icons/search', ['width' => '16', 'heigth' => '16', 'color' => '#757575'])
        </div>
        <input type="text" @if(isset($model)) id="{{ $model }}" wire:model.debounce.500ms="{{ $model }}" @endif placeholder="{{ $placeholder }}" class="{{ $classInput }}" @if($disabled) disabled @endif>
    </div>
</div>