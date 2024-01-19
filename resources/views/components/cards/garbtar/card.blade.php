<div {{ $attributes->merge(['class' => 'bg-white rounded-md h-[440px] p-8 mt-5 md:mt-0 relative print:mt-20 shadow-md shadow-esg7/20']) }}>
    <div class="w-full mb-5">
        @if(isset($text) && $text != null)
            <div class="flex items-center">
                <div class="flex flex-col text-lg font-bold text-esg6 uppercase{{ $titleclass ?? '' }}">
                    @foreach(json_decode(htmlspecialchars_decode($text), true) as $value)
                        <p> {{ $value }} </p>
                    @endforeach
                </div>
                @if (!isset($nolist))
                    <div class="ml-2">
                        @include('icons.list')
                    </div>
                @endif
            </div>
        @endif

        @if(isset($subpoint) && $subpoint != null)
            <div class="flex gap-5">
                @foreach(json_decode(htmlspecialchars_decode($subpoint), true) as $value)
                    <div class="flex items-center">
                        @if (isset($value['color']))
                            <div class="">
                                <span class="w-3 h-3 relative rounded-full inline-block {{ $value['color'] ?? '#FFFFFF' }}"></span>
                            </div>
                        @endif
                        <div class="{{ (isset($value['color'])) ? 'p-2' : '' }} inline-block text-base text-esg8">
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

    <div class="text-esg8 h-min {{ $type ?? 'grid' }} {{ $contentplacement ?? 'place-content-center' }}">
        {{ $slot }}
    </div>
</div>
