@if (isset($action))
    <button {!! $action !!}
        {{ $attributes->merge(['class' => 'py-2.5 px-6 uppercase text-base font-bold rounded-md text-esg27 bg-esg6']) }}
        data-test="save-btn">
        {{ !empty($text) ? $text : __('Save') }}
    </button>
@else
    <button wire:click="{!! $saveMethod ?? 'save' !!}"
        {{ $attributes->merge(['class' => 'py-2.5 px-6 uppercase text-base font-bold rounded-md text-esg27 bg-esg6 cur']) }}
        data-test="save-btn">
        {{ !empty($text) ? $text : __('Save') }}
    </button>
@endif
