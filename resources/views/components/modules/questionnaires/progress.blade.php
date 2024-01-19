@props([
    'title' => false,
    'progress' => 0,
    'categories' => [],
])
<div class="mt-1 text-sm grid grid-cols-{{ ($categories->count() ?: 1) + ($title ? 2 : 1) }}">
    @if ($title)
        <div class="text-base font-bold">{{ __('Progress') }}</div>
    @endif

    @if (! $title || ! $categories->count())
        <div class="flex mb-2 md:mb-0 items-center">
            <div class="pr-2.5">@includeIf('icons.categories.0')</div>
            <div class="text-base font-bold">{{ round($progress) }}%</div>
        </div>
    @endif

    @if ($categories->count())
        @foreach ($categories as $id => $progress)
            <div class="flex mb-2 md:mb-0">
                <div class="pr-2.5">@includeIf('icons.categories.' . $id)</div>
                <div class="text-base font-bold">{{ round($progress) }}%</div>
            </div>
        @endforeach
    @endif
</div>
