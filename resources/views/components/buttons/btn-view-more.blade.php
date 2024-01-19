@props([
    'loadMoreAction',
    'shouldDisplay',
    'text'
])

@if ($shouldDisplay)
    <div class="flex justify-center mb-24">
        <button wire:click="{{ $loadMoreAction }}" class="px-4 py-2 text-black rounded-md focus:outline-none">
            <div class="flex items-center gap-2">
                {!! __($text) !!}
                <span class="mt-1">@include('icons.arrow-menu', ['width' => 14, 'height' => 14])</span>
            </div>
        </button>
    </div>
@endif