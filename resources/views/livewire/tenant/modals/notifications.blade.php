@props([
    'title' => null,
    'message' => null,
    'token' => null,
    'cancel' => false,
    'confirm' => 'closeModal',
])


<div class="w-full">
    <h2 class="mb-8 mt-10 text-esg6 text-2xl font-extrabold text-center">
        {{ $title ?? __('Notification') }}
    </h2>
    <div class="mb-6 mt-4  text-esg6 text-center">
        {{ $message ?? __('Notification message') }}
    </div>

    @isset($token)
        <div class="mb-6 mt-4" text-esg6>
            {{ $token }}
        </div>
    @endisset

    <div class="mt-10 flex justify-center space-x-4 pb-5">
        @if ($cancel)
            <x-buttons.cancel />
        @endif

        <button wire:click="$emit('{{$confirm}}')"
            class="cursor-pointer text-esg27 bg-esg5 ml-10 p-1.5 rounded leading-3 text-uppercase font-inter font-bold text-xs">
            {{ __('Confirm') }}
        </button>

    </div>

</div>
