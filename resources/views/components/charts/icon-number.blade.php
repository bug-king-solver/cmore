<div class="flex">
    <div class="">
        {{ $slot }}
    </div>
    <div class="pl-4 pt-2">
        <p class="text-esg8 font-medium text-5xl">
            <x-number :value="($value ?? null)" />
            <span class="text-esg8 font-normal text-base">
                {{ $unit ?? '' }}
            </span>
        </p>
    </div>
</div>
