<div {{ $attributes->merge(['class' => 'flex flex-col justify-between relative bg-white rounded-md h-auto py-4 px-6 mt-5 md:mt-0 relative shadow-md shadow-esg7/40']) }}>
    <div class="flex gap-6">
        <div class="text-esg8 font-encodesans h-min">
            {{ $data ?? '' }}
        </div>
        <div class="w-min">
            {{ $icon ?? '' }}
        </div>
    </div>
    <div class="grid grid-cols-2 gap-5 mt-2.5 ">
        <x-buttons.a href="{{ $buyAction }}" text="{{ $enabled ? __('Buy') : __('Add') }}" class="w-full !normal-case !rounded-md !text-sm !font-medium"></x-buttons.btn>
    </div>
</div>
