<div class="text-esg25 flex flex-row gap-3 justify-items-center font-encodesans text-5xl font-bold mb-10">
    <div>
        <p class="text-5xl font-medium text-esg8">
            {{ $total }}
        </p>
        <p class="text-lg font-normal text-[#165DFF]"> - 0%
            <span class="text-sm text-esg7 pl-2">
                {{ __('from last month') }}
            </span>
        </p>
    </div>
    <div>
        {{ $slot }}
    </div>
</div>
