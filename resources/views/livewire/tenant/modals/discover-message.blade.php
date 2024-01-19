<div class="w-full">
    <div class="flex flex-col content-center text-center">
        <h2 class="mb-8 mt-4 text-esg6 text-2xl font-extrabold">{{ __('Thanks for your interest') }}</h2>
        <div class="mb-8 text-lg"> {{ __('One of our experts will contact you soon') }} </div>
        <div class="mb-4 pl-4">
            <x-buttons.btn class="bg-esg6 color-esg4 pr-4 pl-4 text-base" text="{{ __('OK') }}" wire:click="$emit('closeModal')"/>
        </div>
    </div>
</div>