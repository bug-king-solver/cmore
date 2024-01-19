<div {{ $attributes->merge(['class' => 'w-full p-4 bg-white rounded shadow justify-between items-start inline-flex cursor-pointer']) }} >
    <div class="flex-col justify-start items-start gap-px inline-flex">
        <div class="text-neutral-500 text-lg font-normal font-['Lato'] uppercase">{{ $title ?? '' }}</div>
        <div class="justify-start items-baseline gap-1 inline-flex">
            <div class="text-green-500 text-4xl font-extrabold font-['Lato']">{{ $percentage ?? '00' }}</div>
            <div class="text-neutral-700 text-base font-normal font-['Lato']">%</div>
        </div>
    </div>

    <div class="flex flex-col gap-5">
        {{ $icon ?? '' }}

        <div class="w-5 h-5 ml-2 grid place-content-center bg-white rounded border border-slate-200" >
           {{ $button ?? '' }}
        </div>
    </div>
</div>
