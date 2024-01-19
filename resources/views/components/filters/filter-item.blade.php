<div>
    <li class="relative p-2 hover:bg-gray-100 0 {{ array_key_exists($item['queryName'], $this->filterSelected) ? 'bg-esg10' : '' }}">
        <x-buttons.btn-text class="w-full inline-flex items-center p-0 text-esg8"
            wire:click="updateAvailableFilter('{{ $item['queryName'] }}')">
            {{ $item['title'] }}
        </x-buttons.btn-text>
    </li>
</div>
