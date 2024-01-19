<x-cards.card class="flex-col justify-between !p-3">
    <div class="text-esg29 font-encodesans flex h-auto text-lg font-bold border-b border-b-esg7/25">
        <span class="flex flex-row gap-3 justify-between w-full items-center">
            {{ $header ?? '' }}
        </span>
    </div>

    <div class="text-esg29 font-encodesans w-full h-auto">
        <div class="mb-3 mt-2 w-full h-full">
            {{ $content ?? '' }}
        </div>
    </div>

    <div class="border-t border-esg7/25 w-full flex flex-col justify-center bottom-0">
        <div class="flex flex-row gap-2 pt-3 justify-end w-full">
            {{ $footer ?? '' }}
        </div>
    </div>
</x-cards.card>
