<button {!! $action ?? 'wire:click="save"' !!} {{ $attributes->merge(['class' => 'text-white bg-esg6 font-medium border border-esg6 rounded-lg text-sm py-3.5 px-2.5 text-center']) }}>
    {{ $slot }}
</button>