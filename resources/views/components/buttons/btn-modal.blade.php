<button {{ $attributes->merge(['class' => 'cursor-pointer inline-flex items-center justify-center py-1 px-2 text-sm']) }}>
    <div class="flex flex-row items-center" @if (isset($modal)) x-on:click="Livewire.emit('openModal', '{{ $modal }}', {{ $data ?? null }})" @endif>
        {{ $buttonicon ?? '' }}
        <span class="ml-2">{{ $slot }}</span>
    </div>
</button>
