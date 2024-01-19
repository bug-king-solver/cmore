<x-cards.card class="flex-col !p-3">
    @if(isset($title) && $title != null)
        <h2 class="text-lg text-esg8 font-medium align-self-start">
            {{ htmlspecialchars_decode($title) }}
            @isset($description)
                <x-information id="{{ Str::slug($description) }}">{{ __($description) }}</x-information>
            @endisset
        </h2>
    @endif

    @if (isset($legend) && $legend != null)
        <div class="flex gap-5">
            @foreach (json_decode(htmlspecialchars_decode($legend), true) as $value)
                <div class="flex items-center">
                    @if (isset($value['color']))
                        <div class="">
                            <span
                                class="w-3 h-3 relative rounded-full inline-block bg-[{{ $value['color'] ?? '#FFFFFF' }}]"
                            ></span>
                        </div>
                    @endif
                    <div class="{{ isset($value['color']) ? 'p-2' : '' }} inline-block text-sm text-esg8/70">
                        {{ $value['text'] ?? '' }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    {{ $slot }}
</x-cards.card>
