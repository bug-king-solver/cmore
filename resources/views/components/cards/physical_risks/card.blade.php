<div class="relative bg-esg4 rounded-md border border-esg6/10 print:!pt-0 pagebreak-unset">
    <div class="text-esg29 font-encodesans flex h-auto text-lg font-bold border-b border-b-esg7/25">
        <span class="flex flex-row gap-3 justify-between w-full items-center p-3">
            {{ $header ?? '' }}
        </span>
    </div>

    <div class="px-10 py-3">
        <div class="text-esg29 font-encodesans w-full h-auto">
            <div class="mb-3 mt-2 w-full h-full">
                {{ $content ?? '' }}
            </div>
        </div>
    </div>
</div>

<div class="mt-3 border-b border-b-esg7/50 pb-10">
    {{ $log ?? '' }}
</div>