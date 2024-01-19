<div
    {{ $attributes->merge(['class' => 'flex justify-between gap-2 bg-white rounded-md h-auto py-4 px-8 mt-5 md:mt-0 relative print:mt-20 shadow-md shadow-esg7/20']) }}>
    <div class="">
        <div class="text-esg16 font-encodesans flex flex-col text-sm  uppercase {{ $titleclass ?? '' }}">
            <p> {{ $text ?? '' }} </p>
        </div>
        <div class="">
            {{ $data ?? '' }}
        </div>
    </div>
    <div class="flex items-center">
        {{ $icon ?? '' }}
    </div>
</div>
