<div class="flex justify-center">
    <div class="absolute bottom-0 flex flex-row pb-1">
        {!! tenantView('partials/footer', ['class' => 'border-none']) !!}
    </div>
    <div class="absolute bottom-0 right-6 pb-[24px] flex flex-row gap-[2px]" >
        @include('icons.mail', ['width' => '20px', 'height' => '20px', 'color' => color(11)])
        <span class="font-encodesans leading-5 pl-1 text-sm font-normal text-esg11"></span>
    </div>
</div>
