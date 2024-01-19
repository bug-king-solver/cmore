<div class="" x-data="{ circumference: {{$percent < 100 ? 48 : 50}} * 2 * Math.PI, percent: {{$percent}} }">
    <div class="flex items-center justify-center overflow-hidden bg-white rounded-full">
        <svg class="w-32 h-32 transform translate-x-1 translate-y-1" x-cloak aria-hidden="true">
            <circle
                class="text-gray-300"
                stroke-width="8"
                stroke="currentColor"
                fill="transparent"
                r="50"
                cx="60"
                cy="60"
                />
            <circle
                class="{{$color}}"
                stroke-width="8"
                :stroke-dasharray="circumference"
                :stroke-dashoffset="circumference - percent / 100 * circumference"
                stroke-linecap="round"
                stroke="currentColor"
                fill="transparent"
                r="50"
                cx="60"
                cy="60"
            />
        </svg>
        <span class="absolute text-xl" x-text="`${percent}%`"></span>
    </div>
    <p class="text-base text-esg8 mb-4">{{$text}}</p>
</div>