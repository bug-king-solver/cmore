<div>
    <div class="flex items-center gap-5">
        <p class="text-xs font-medium text-esg8">{{ __('Enable/Disable email verification') }}</p>
        <div class="mt-1"> <x-inputs.switch id="enabled" wire:click="update"/> </div>
    </div>

    @error('enabled')
        <p class="mt-4 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror

    @if($success)
        <p class="mt-4 text-sm text-green-500">
            {{ $success }}
        </p>
    @endif
</div>
