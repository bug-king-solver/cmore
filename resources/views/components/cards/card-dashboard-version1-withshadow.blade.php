<div
    {{ $attributes->merge(['class' => 'bg-white rounded-md h-[440px] py-4 px-8 mt-5 md:mt-0 relative print:mt-20 shadow-md shadow-esg7/20']) }}>
    <div class="w-full mb-5">
        @if (isset($filtersWithTitle))
            {{ $filtersWithTitle ?? '' }}
        @elseif(isset($text) && $text != null)
            <div class="flex items-center justify-between">
                <div class="text-esg16 font-encodesans flex flex-col text-base  uppercase {{ $titleclass ?? '' }}">
                    @php $text = parseStringToArray(htmlspecialchars_decode($text)) @endphp
                    @foreach ($text as $value)
                        <p> {{ $value }} </p>
                    @endforeach
                </div>
                @if (isset($pagination) && $pagination)
                    <div class="flex items-center">
                        <button id="prevPage" disabled>
                            @include('icons.left')
                        </button>
                        <div class="text-gray-600">
                            <span id="currentPage" class="text-sm text-esg16">01</span> <span
                                class="text-sm text-esg16">{{ __('of') }}</span> <span id="totalPages"
                                class="text-sm text-esg16">02</span>
                        </div>
                        <button id="nextPage">
                            @include('icons.right')
                        </button>
                    </div>
                @endif
            </div>

        @endif

        @if (isset($subpoint) && $subpoint != null)
            <div class="flex gap-5">
                @foreach (json_decode(htmlspecialchars_decode($subpoint), true) as $value)
                    <div class="flex items-center">
                        @if (isset($value['color']))
                            <div class="">
                                <span
                                    class="w-3 h-3 relative rounded-full inline-block {{ $value['color'] ?? '#FFFFFF' }}"></span>
                            </div>
                        @endif
                        <div class="{{ isset($value['color']) ? 'p-2' : '' }} inline-block text-sm text-esg8/70">
                            @php $data = explode(":", $value['text']) @endphp
                            @if (isset($data[1]))
                                {{ $data[0] }}: <b>{{ $data[1] }}</b>
                            @else
                                {{ $value['text'] ?? '' }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div
        class="text-esg8 font-encodesans h-min {{ $type ?? 'grid' }} {{ $contentplacement ?? 'place-content-center' }}">
        {{ $slot }}
    </div>
</div>
