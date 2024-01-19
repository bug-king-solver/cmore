<div class="rounded-md bg-esg7/20 p-4">
    <div class="text-esg29 text-center h-auto">
        <span class="text-2xl font-bold text-esg5"> {{ __('Help area') }} </span>
    </div>

    <div class="mt-2.5 pb-4 border-b-2 border-b-esg7">
        <div class="w-full h-full text-lg text-esg16 text-center">
            {{ __('This is the help area! Here you’ll be able to see information about the question you’re currently in. ') }}
        </div>
    </div>

    <div class="mt-4">
        <div class="w-full h-full text-base uppercase text-esg16">
            {{ __($subCategorieActive['name'] ?? '') }} :
        </div>

        <div class="w-full h-full text-base text-esg16 mt-6">
            {{ __($subCategorieActive['description'] ?? '') }}
        </div>
    </div>
</div>
