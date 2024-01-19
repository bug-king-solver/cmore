<div>
    <x-buttons.btn-icon-text id="dropdownDefault" data-dropdown-toggle="dropdown" class="rounded-md gap-2"
        data-test="add-targets-btn">
        <x-slot name="buttonicon">
            @include('icons/tables/plus')
        </x-slot>
        {{ __($text) ?? __('Add') }}
    </x-buttons.btn-icon-text>

    <div id="dropdown"
        class="hidden z-10 w-44 bg-white rounded-lg border-[0.3px] border-esg55 divide-y divide-gray-100 shadow-lg">
        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
            @foreach ($dropdowItems as $group)
                @if ($group)
                    <x-dropdown.groups-item :group="$group" />
                @endif
            @endforeach
        </ul>
    </div>
</div>
