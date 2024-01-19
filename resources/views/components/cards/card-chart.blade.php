<div>
    <x-cards.card class="flex-col">
        <div class="flex justify-between">
            <p class="text-lg text-esg16">{{ $title }}</p>
        </div>
        <div class="flex flex-col justify-center items-center self-stretch pt-6 ">
            <div class="flex justify-center">
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </x-cards.card>
</div>
