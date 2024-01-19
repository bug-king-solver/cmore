@props([
    'title' => false,
    'categories' => []
])

<div class="mt-1 text-sm grid grid-cols-{{ count($categories) + ($title ? 1 : 0) }}">
    @if ($title)
        <div class="text-base font-bold">{{ __('Result') }}</div>
    @endif

    @foreach ($categories as $id => $result)
        <div class="flex mb-2 md:mb-0">
            <div class="pr-2.5">@include('icons.categories.' . $id)</div>
            <div class="text-base font-bold">{{ round($result) }}%</div>
        </div>
    @endforeach
</div>
