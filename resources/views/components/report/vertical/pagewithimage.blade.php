<div class="mt-10 print:-mt-10 pagebreak print:!h-full print:!w-full relative" >
    <div class=" bg-white w-full h-[40%] z-99999">
        @if (isset($header))
            <div class="flex p-14 h-full flex-col justify-between m-auto border-b-[20px]  {{ $border ?? 'border-b-esg5' }} w-full bg-white">
                <div class="flex justify-center">
                    @if (isset($customlogo))
                        @include($customlogo, ['width' => 100, 'height' => 60])
                    @else
                        @include('icons.logos.cmore')
                    @endif
                </div>

                {{ $slot ?? '' }}
            </div>
        @endif
    </div>
    <div class="h-3/5 -z-20">
        <img src="{{ $url ?? '' }}" class="object-fill w-full h-[700px]">
    </div>
</div>
