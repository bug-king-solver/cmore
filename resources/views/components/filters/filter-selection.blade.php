<div class="h-[40px] w-fit">
    <x-buttons.btn-alt id="dropdownDefault" data-dropdown-toggle="dropdown"
        class="rounded-md border-esg0 h-full dropdown-toggle hover:bg-[#cccccc84]">
        <x-slot name="buttonicon">
            <span class="text-esg16 flex justify-center items-center normal-case font-normal text-sm">
                @include('icons/plus', ['width' => '14', 'height' => '14', 'color' => '#757575', 'class' => 'mr-1'])
                {{ __('Add a filter') }}
            </span>
        </x-slot>
    </x-buttons.btn-alt>


    <div id="dropdown"
        class="hidden z-10 w-44 bg-white rounded-md shadow-bxesg1 l-[5rem] !ml-8">
        <ul class="text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
            @foreach ($dropdowFilters as $key => $item)
                <x-filters.filter-item :item="$item" />
            @endforeach
        </ul>
    </div>
</div>
