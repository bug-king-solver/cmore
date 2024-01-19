<button {!! $action ?? 'wire:click="save"' !!}  {{ $attributes->merge(['class' => 'text-esg28 cursor-pointer']) }}>
    {{ $slot }}
</button>
