@foreach ($categories as $category)
    <div class="mt-6">
        <p class="text-lg font-bold text-esg6 uppercase">{{ $category->name }}</p>
        <p class="text-lg text-esg16 mt-6">{{ $category->description }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">
        @foreach ($category->products as $product)
            <x-cards.catalog.product class="transition ease-in-out duration-300 hover:z-10 hover:bg-gray-100"
                infoAction="{{ $product->button_info_href ?? '#' }}" buyAction="{{ $product->button_buy_href }}"
                enabled="{{ $product->enabled }}">
                <x-slot:icon>
                    @include('icons.product.questionnaire.custom')
                </x-slot:icon>
                <x-slot:data>
                    <div class="">
                        <p class="text-lg font-bold text-esg6"> {{ $product->title }} </p>
                        <div class="mt-2">
                            <p class="text-base text-esg16">{{ $product->description }}</p>
                        </div>
                    </div>
                </x-slot:data>
            </x-cards.catalog.product>
        @endforeach
    </div>
@endforeach
