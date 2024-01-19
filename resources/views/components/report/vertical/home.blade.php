<div class="mt-10 print:-mt-10 grid grid-cols-1 pagebreak print:!h-full print:!w-full text-center" >
    @if (isset($header) && isset($footer))
        <div class="flex flex-col justify-between">
            <div class="py-2 grid justify-end shadow-md shadow-esg7/20 bg-white px-14">
                @if (isset($customlogo))
                    @include($customlogo, ['width' => 100, 'height' => 60])
                @else
                    @include('icons.logos.cmore', ['width' => 100, 'height' => 60])
                @endif
            </div>

            <div class="px-14">
                {{ $slot ?? '' }}
            </div>

            <div class="border-t py-2  {{ $footerborder ?? 'border-t-esg5' }} grid justify-end px-14">
                {{ $footerCount ?? '00' }}
            </div>
        </div>
    @else
        {{ $slot ?? '' }}
    @endif

    <div class="">
        <img src="{{ $url ?? '' }}" class="w-full">
    </div>
</div>
