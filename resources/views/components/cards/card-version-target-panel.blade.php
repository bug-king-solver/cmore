<x-cards.card {{ $attributes->merge(['class' => 'flex-col !p-3']) }}>
    <div class="text-esg8 font-encodesans flex items-center justify-between pb-5 text-lg font-normal">
        <span>
            {{ $icon }}
        </span>
        <span class="pl-2 w-10/12">{{ $text }}</span>
        <span class="grow pl-10">
            {{ $iconarrow }}
        </span>
    </div>

    <div class="">
        {{ $progress ?? null }}
    </div>

    <div class="text-esg25 grid grid-cols-2 justify-items-center font-encodesans text-5xl font-bold mb-10">
        {{ $canvas }}
    </div>
</x-cards.card>
