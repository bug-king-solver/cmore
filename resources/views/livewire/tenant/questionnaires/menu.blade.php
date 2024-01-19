@push('head')
<style nonce="{{ csp_nonce() }}">
    .disabled-cursor {
        cursor: not-allowed;
        opacity: 0.5;
        pointer-events: none;
        border-color: #b1b1b1;
        color: #b1b1b1;
        padding: 4px;
        border-radius: 4px
    }
    .enabled-cursor {
    cursor: pointer;
    opacity: 0.5;
    border-color: #444;
    color: #444;
    padding: 4px;
    border-radius: 4px;
}
</style>

@endpush
<div class="px-4 lg:px-0">
    <div class="p-4 flex items-center">
        <div class="relative w-full">
            <input type="text" id="search-bar" placeholder="Search" class="w-full pl-4 pr-10 py-2 rounded-lg bg-white border-none focus:outline-none">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
        </div>
    </div>
    <div id="accordion-collapse" data-accordion="collapse" data-active-classes="" data-inactive-classes="">
        @php $categoriesProgess = 0; @endphp
        @foreach ($menu['main_categories'] as $category)
            @php
            $ariaExpanded = $categorySelected && ($categorySelected->id === $category->id || $category->children->contains($categorySelected)) ? 'true' : 'false';
            @endphp

            @if (! $menu['children_categories']->where('parent_id', $category->id)->count())
                <h2 class="">
                    <a href="{{ route('tenant.questionnaires.show', ['questionnaire' => $questionnaire, 'category' => $category]) }}" class="flex w-full items-center justify-between p-5 text-left ">
                        <div class="flex items-center">
                            <div class="font-bold">{{ $category->name }}</div>
                            <div class="grow-0">
                                <div class="w-14  grid items-center px-1.5 text-center font-bold">{{ round($category->progress) }}%</div>
                            </div>
                        </div>
                    </a>
                </h2>
            @else
                <h2 data-aria="{{ $ariaExpanded }}" id="accordion-collapse-heading-{{ $loop->index }}" class="category">
                    <button type="button" class="flex w-full items-center justify-between p-5 text-left" data-accordion-target="#accordion-collapse-body-{{ $loop->index }}" aria-expanded="{{ $ariaExpanded }}" aria-controls="accordion-collapse-body-{{ $loop->index }}">
                        <div class="flex items-center">
                            <div class="font-bold">{{ $category->name }} </div>
                        </div>
                        <div class="flex items-center">
                            @php $categoriesProgess+= round($category->progress); @endphp
                            <div class="font-bold ">{{ round($category->progress) }}%</div>
                            <svg data-accordion-icon class=" w-6 h-6 shrink-0 @if ($ariaExpanded === 'true') rotate-180 @endif" fill="currentColor" viewBox="0 0 20 20" xmlns="https://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>

                </h2>
                <div id="accordion-collapse-body-{{ $loop->index }}" class="p-3  @if ($ariaExpanded === 'false') hidden @endif" aria-labelledby="accordion-collapse-heading-{{ $loop->index }}">
                    <div class="p-2">
                        <ul>
                            @foreach ($menu['children_categories']->where('parent_id', $category->id) as $children)
                                <li class="py-2 category">
                                    <div class="flex items-center space-x-2">
                                        <div class="grow">
                                            <a href="{{ route('tenant.questionnaires.show', ['questionnaire' => $questionnaire, 'category' => $children]) }}" class="py-1  {{ $categorySelected && $categorySelected->id === $children->id ? 'font-bold text-esg5' : 'text-gray-500' }} ">@if ($categoriesAllowed && ! in_array($children->id, $categoriesAllowed)) <img class="inline-block" src="{{ global_asset('images/icons/locker.png') }}"  width="12"> @endif{{ $children->name }}</a>
                                        </div>
                                        <div class="grow-0">
                                            <div class="w-14  grid items-center px-1.5 text-center {{ $categorySelected && $categorySelected->id === $children->id ? 'font-bold text-esg5' : 'text-gray-500' }} ">{{ round($children->progress) }}%</div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="">
        @if ($isCompleted && !$isSubmitted)
            <button
                class="p-4 text-center border-2 rounded-md  bg-esg5 w-full hover:shadow transition ease-in duration-200 text-esg4 uppercase"
                x-on:click="Livewire.emit('openModal', 'questionnaires.modals.submit', {{ json_encode(['questionnaire' => $questionnaire->id]) }})"
                >
                    {{ __('Submit') }}
                </button>
        @else
            <button
                class="p-4 text-center border-2 rounded-md  w-full text-esg7 uppercase cursor-not-allowed"
                >
                    {{ __('Submit') }}
            </button>
        @endif
    </div>

</div>

@push('child-scripts')
    <script nonce="{{ csp_nonce() }}">
        const searchInput1 = document.querySelector('#search-bar');
        searchInput1.addEventListener('input', (event) => {
            const searchText = event.target.value.toLowerCase();

            const categories = document.querySelectorAll('.category');
            categories.forEach((category) => {
                const categoryText = category.textContent.toLowerCase();

                if (categoryText.includes(searchText)) {
                    category.classList.remove('hidden');
                } else {
                    category.classList.add('hidden');
                }
            });
        });
    </script>
@endpush
