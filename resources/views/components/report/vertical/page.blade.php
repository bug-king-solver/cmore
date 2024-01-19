<div class="mt-10 relative print:mt-0 {{ $lastpage ?? 'pagebreak' }} print:!h-screen" @if(isset($bgimage)) style="background-image:url({{ $bgimage }}); background-position:center; background-repeat:no-repeat; background-size:cover;" @endif>
    <div class="flex flex-col justify-between">
        @if(!isset($noheader))
            <div class="py-2 flex items-center justify-between px-4">
                <span class="text-xl text-esg8 uppercase">{{ $title ?? '' }}</span>
                @if (isset($customlogo))
                    @include($customlogo, ['width' => 100, 'height' => 60])
                @else
                    @include('icons.logos.cmore', ['width' => 100, 'height' => 60])
                @endif
            </div>
        @endif

        <div class="{{ $lastpage ?? 'px-4' }}">
            {{ $slot ?? '' }}
        </div>

        @if(!isset($nofooter))
            <div class="py-2  text-esg16 flex items-center justify-between px-4 absolute bottom-0">
                <span class="text-sm">{{ __('Sustainability Report | ESG 2022') }}</span>
            </div>
        @endif
    </div>
</div>
