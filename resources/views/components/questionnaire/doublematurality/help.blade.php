<div class="rounded-md bg-esg7/20 p-4">
    <div class="text-esg29 text-center h-auto">
        <span class="text-2xl font-bold text-esg5"> {{ __('Help area') }} </span>
    </div>

    @if ($category['description'])
        <div class="mt-4">
            <div class="w-full h-full text-base uppercase text-esg16">
                {{ __($category['name'] ?? '') }}
            </div>

            <div class="w-full h-full text-base text-esg16 mt-6">
                {{ __($category['description'] ?? '') }}
            </div>
        </div>
    @endif
</div>
