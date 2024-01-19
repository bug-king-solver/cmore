@props([
    'title' => '',
    'titleclass' => '',
    'icon' => '',
    'defaulthide' => false,
    'placement' => 'justify-center',
    'rounded' => 'rounded-md',
    'background' => 'bg-esg6/20',
    'helper' => '',
    'reference' => '',
])

<div class="accordion-item w-full border border-esg6/10 {{ $rounded }} delay-100 duration-500 overflow-hidden"
    style="max-height: none !important;">

    <div class="accordion-header flex items-center {{ $background }} p-3">

        <div class="flex items-center grow text-center {{ $placement }} gap-5">
            <div class="text-sm font-bold text-[#2B2D3B] uppercase {{ $titleclass ?? '' }}">{{ $title ?? '' }}</div>
        </div>

        @if ($helper && $reference)
            <x-information id="{{ $reference }}" model="true">
                {{ $helper }}
            </x-information>
        @endif

        <div class="accordion-button cursor-pointer py-2 ml-10 {{ $rounded }}">
            <span>@include('icons.according.hide')</span>
            <span>@include('icons.according.show')</span>
        </div>
    </div>

    <div {{ $attributes->merge(['class' => 'accordion-content transition duration-500']) }}>
        {{ $slot ?? '' }}
    </div>
</div>
