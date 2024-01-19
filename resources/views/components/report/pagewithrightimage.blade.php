<div class="mt-10 print:mt-0 pagebreak print:!h-full" >
    <div class="flex flex-col justify-between relative" >
        <div class="py-2 flex items-center justify-between shadow-md shadow-esg7/20 bg-white px-14">
            <span class="text-xl text-esg8 uppercase">{{ $title ?? '' }}</span>
            @if (isset($customlogo))
                @include($customlogo, ['width' => 100, 'height' => 60])
            @else
                @include('icons.logos.cmore', ['width' => 100, 'height' => 60])
            @endif
        </div>

        <div class="grid grid-cols-5 print:grid-cols-5 gap-10 min-h-[560px]">
            <div class="col-span-2 px-14">
                {{ $slot ?? '' }}
            </div>
            <div class="col-span-3" @if(isset($bgimage)) style="background-image:url({{ $bgimage }}); background-position:center; background-repeat:no-repeat; background-size:cover;" @endif>
            </div>
        </div>

        <div class="border-t py-2 {{ $footerborder ?? 'border-t-esg5' }} text-esg8 flex items-center justify-between px-14 ">
            <span class="text-sm">{{ __('Sustainability Report | ESG 2022') }}</span>
            <span class="text-sm">{{ isset($footerCount) ? $footerCount : '00' }}</span>
        </div>
    </div>
</div>
