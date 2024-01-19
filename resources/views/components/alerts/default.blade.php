<div x-data="{ isVisible: true }" x-show="isVisible"
    {{ $attributes->merge(['class' => 'flex shadow-md mb-8 items-center bg-white border-l-4 ' . (isset($color) ? "border-{$color}" : '') . ' text-esg39 text-sm py-2.5 px-6 relative']) }}
    role="alert">
    @if (isset($icon))
        @include("icons.alerts.{$icon}")
    @endif

    <span class="block sm:inline">{{ $slot }}</span>
    <span @click="isVisible = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
        @include('icons/x')
        </button>
</div>
