<button {!! $action ?? 'wire:click="delete"' !!} {{ $attributes->merge(['class' => 'py-2.5 px-6 uppercase text-base font-bold rounded-md text-esg27 bg-esg6']) }}>
    {{ __('Delete') }}
</button>
