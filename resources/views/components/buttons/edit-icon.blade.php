<button @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif {{ $attributes->merge(['class' => 'cursor-pointer']) }}>
    <div class="flex items-center {{ $inideClass ?? '' }}">
        <div>
            @include('icons.edit', isset($param) ? json_decode($param, true) : [])
        </div>
        <div class="pl-2">
            {{ $slot }}
        </div>
    </div>
</button>
