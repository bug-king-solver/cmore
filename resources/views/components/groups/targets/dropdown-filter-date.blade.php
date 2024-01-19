<x-slot:dropdown>
    <button id="dropdown_{{ $event }}" data-dropdown-toggle="{{ $event }}"
        class="text-esg8 focus:ring-4 font-medium rounded-lg text-sm px-4 py-2.5 text-center border border-esg7 inline-flex items-center"
        type="button">
        {{ $title }}
        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
            </path>
        </svg>
    </button>

    <div id="{{ $event }}"
        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
            @foreach ($this->filterIntervalList as $list)
                <li>
                    <a href="javascript:void(0)" wire:click="$emit('{{ $event }}', '{{ $list['value'] }}')"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        {{ $list['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</x-slot:dropdown>
