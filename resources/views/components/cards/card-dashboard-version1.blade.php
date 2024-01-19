<div
    {{ $attributes->merge(['class' => 'bg-esg4 border border-esg8/20 rounded h-[440px] p-4 mt-5 md:mt-0 relative print:mt-20']) }}>
    <div class="absolute w-full pl-4 pr-4 -ml-4">
        @if (isset($text) && $text != null)
            <div class="text-esg5 font-encodesans flex flex-col text-base font-bold {{ $titleclass ?? '' }}">
                @foreach (json_decode(htmlspecialchars_decode($text), true) as $value)
                    <p> {{ $value }} </p>
                @endforeach
            </div>
        @endif
        @if (isset($subpoint) && $subpoint != null)
            <div class="flex gap-5">
                @foreach (json_decode(htmlspecialchars_decode($subpoint), true) as $value)
                    <div class="flex">
                        @if (isset($value['color']))
                            <div class="text-xl">
                                <span
                                    class="w-2 h-2 relative -top-2 rounded-full inline-block {{ $value['color'] ?? '#FFFFFF' }}"></span>
                            </div>
                        @endif
                        <div class="pl-2 inline-block text-xs text-esg8/70">{{ $value['text'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div
        class="text-esg25 font-encodesans text-5xl font-bold h-full {{ $type ?? 'grid' }} {{ $contentplacement ?? 'place-content-center' }}">
        {{ $slot }}
    </div>
</div>
