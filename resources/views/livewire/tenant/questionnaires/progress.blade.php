<div class="mt-1 text-sm grid grid-cols-{{ ($menu['main_categories']->count() ?: 1) + ($title ? 1 : 0) }}">
    @if ($title)
        <div class="text-base font-bold">{{ __('Progress') }}</div>
    @endif

    @if (! $title || ! $menu['main_categories']->count())
        <div class="flex mb-2 md:mb-0 items-center justify-items-end">
            <div class="pr-2.5">@includeIf('icons.categories.0')</div>
            <div class="text-base font-bold">{{ round($questionnaire->progress) }}%</div>
        </div>
    @endif

    @if ($menu['main_categories']->count())
        @foreach ($menu['main_categories'] as $category)
            <div class="grid mb-2 md:mb-0 items-center justify-items-end">
                <div class="flex">
                    <div class="pr-2.5">@includeIf('icons.categories.' . $category->id)</div>
                    <div class="text-base font-bold">{{ round($category->progress) }}%</div>
                </div>
            </div>
        @endforeach
    @endif
</div>
