<div class="p-4">
    <div class="flex justify-between items-center">
        <label class="!text-esg6 text-xl font-bold">
            {{ __('Submit taxonomy') }}
        </label>

        <div wire:click="closeModal()" class="bg-esg7/50 rounded-full w-6 h-6 flex items-center cursor-pointer">
            <div class="m-auto">X</div>
        </div>
    </div>

    <div class="mt-4">
        <p class="text-sm text-esg8">
            {{ __('Are you sure you want to submit the taxonomy?') }}
        </p>
    </div>

    <div class="mt-4 flex gap-4 justify-end">
        <x-buttons.btn
            class="!bg-esg4 !text-esg6 !border !border-esg5  !block !text-sm !font-medium !normal-case  duration-300 hover:!bg-esg5/20 hover:text-white"
            wire:click="closeModal()" text="{!! __('Cancel') !!}" />

        <x-buttons.btn
            class="!bg-esg5 !text-esg4  !block !text-sm !font-medium !normal-case cursor-pointer duration-300 hover:!bg-esg5/70"
            text="{{ __('Yes, finish') }}" wire:click="save" />
    </div>
</div>
