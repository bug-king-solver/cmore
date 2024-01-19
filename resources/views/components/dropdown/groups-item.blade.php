<div>
    <li class="relative p-2 hover:bg-gray-100">
        @if (isset($group['type']))
            <x-buttons.a-icon class="text-gray-700 w-full h-full inline-flex items-center !border-0 !bg-none !p-0"
                href="{{ $group['customClickEvent'] }}">
                <div class="inline-flex items-center gap-2">
                    @include("{$group['icon']}", ['class' => 'mr-2'])
                    {{ $group['name'] }}
                </div>
            </x-buttons.a-icon>
        @elseif (isset($group['customClickEvent']))
            <x-buttons.btn-text class="text-gray-700 w-full h-full inline-flex items-center"
                wire:click="$emitTo({{ $group['customClickEvent'] }}, '{{ implode('\',\'', $group['customClickParams']) }}');">
                @include("{$group['icon']}", ['class' => 'mr-2'])
                {{ $group['name'] }}
            </x-buttons.btn-text>
        @else
            <x-buttons.btn-text modal="{{ $group['modal'] }}" class="text-gray-700 w-full inline-flex items-center">
                @include("{$group['icon']}", ['class' => 'mr-2'])
                {{ $group['name'] }}
            </x-buttons.btn-text>
        @endif
    </li>
</div>
