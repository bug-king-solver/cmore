<div {{ $attributes->merge(['class' => 'flex gap-6 items-center bg-white rounded-md h-auto py-4 px-6 mt-5 md:mt-0 relative shadow-md shadow-esg7/40']) }}>
    <div class="w-min">
        {{ $icon ?? '' }}
    </div>
    <div class="text-esg8 font-encodesans h-min">
        {{ $data ?? '' }}
    </div>
</div>