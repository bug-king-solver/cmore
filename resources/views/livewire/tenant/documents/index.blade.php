<x-slot name="header">
    <x-header title="{{ __('Library') }}" data-test="library-header">
        <x-slot name="left"></x-slot>

        <x-slot name="right">
        </x-slot>
    </x-header>
</x-slot>

<div class="px-4 lg:px-0 mt-8">

    <p class="text-esg5 font-semibold font-encodesans text-base"> {{ __('Own Resources') }} </p>
    <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mt-5">
        @foreach ($documentTypes['internal'] as $value => $label)
            <x-cards.document-type :value="$value" type="internal">{{ $label }}</x-cards.document-type>
        @endforeach
    </div>

    @if (config('app.library.framework'))
        <p class="text-esg5 font-semibold font-encodesans text-base mt-9"> {{ __('Frameworks') }} </p>
        <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mt-5">
            @foreach ($documentTypes['framework'] as $value => $label)
                <x-cards.document-type :value="$value" type="framework">{{ $label }}</x-cards.document-type>
            @endforeach
        </div>
    @endif

    @if (config('app.library.extra'))
        <p class="text-esg5 font-semibold font-encodesans text-base mt-9"> {{ __('Extra') }} </p>
        <div class="gap-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 mt-5">
            @foreach ($documentTypes['extra'] as $value => $label)
                <x-cards.document-type :value="$value" type="extra">{{ $label }}</x-cards.document-type>
            @endforeach
        </div>
    @endif
</div>
