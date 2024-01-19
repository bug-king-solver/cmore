<x-cards.card {{ $attributes->merge(['class' => 'flex-col !p-3']) }}>
    <div class="text-esg8 font-encodesans flex justify-between items-center pb-5 text-lg font-normal">
        <div class="w-6/12">{{ $text }}</div>
        <div class="">
            {{ $dropdown ?? null }}
        </div>
    </div>

    <div class="mt-6">
        {{ $data }}
    </div>
</x-cards.card>
