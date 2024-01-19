@props([
    'title' => '',
    'cancel' => '',
    'class' => '',
    'buttonPosition' => 'justify-end',
    'showButtons' => true,
    'saveButtontext' => '',
    'btnClass' => '',
    'discard' => '',
])

<div class="bg-esg4 pb-5">
    <div class="mt-3 sm:mt-0 sm:text-left">
        <h3 class="text-esg29 relative text-2xl font-extrabold {{ $class ?? '' }}" id="modal-headline">
            {{ $title ?? '' }}
        </h3>
        <div class="mt-6">
            {{ $slot }}
        </div>
    </div>
</div>

@if ($showButtons == true)
    <div class="mt-4 flex {{ $buttonPosition ?? 'justify-end' }} pb-5 gap-5">
        @if (isset($discard) && $discard)
            <x-buttons.btn-discard class="!px-5 !rounded-md" discard="{{ $discard ?? '' }}"></x-buttons.btn-discard>
        @else
            <x-buttons.cancel-href href="{{ $cancel ?? '' }}" class="!px-5 !rounded-md"></x-buttons.cancel-href>
        @endif
        <x-buttons.save class="{{ $btnClass ?? '' }}" type="submit" text="{{ $saveButtontext }}"></x-save>
    </div>
@endif
