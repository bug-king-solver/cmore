<div id="alert-additional-content-2"
    {{ $attributes->merge(['class' => 'p-4 border bg-' . $color . ' border-' . $color . ' rounded-lg  mt-4 mb-4 w-full transition-animation']) }}
    role="alert">

    <div class="flex items-center">
        @if (isset($icon))
            @include("icons.alerts.{$icon}")
        @else
            @include('icons.info-fill', [
                'color' => 'red',
            ])
        @endif
        <h3 class="text-lg font-medium">
            {{ $title ?? __('Alert') }}
        </h3>
    </div>

    <div class="flex items-center place-content-between">
        <div class=" text-sm">
            {{ $slot ?? '' }}
        </div>
    </div>
</div>

@push('head')
    <style nonce="{{ csp_nonce() }}" scoped>
        .transition-animation {
            animation: translateYAnimation .5s ease-in-out;
        }

        @keyframes translateYAnimation {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }

            80% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endpush
