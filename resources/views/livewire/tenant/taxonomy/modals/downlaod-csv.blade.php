<div class="p-4">
    <div class="flex justify-between items-center">
        <label class="text-xl !text-esg6 text-bold">
            {{ __('Download the CSV') }}
        </label>

        <div wire:click="closeModal()" class="bg-esg7/50 rounded-full w-6 h-6 flex items-center cursor-pointer">
            <div class="m-auto">X</div>
        </div>
    </div>

    <div class="mt-4">
        <p class="text-sm text-esg8">
            {{ __('Click on the button below to download the CSV file.') }}
        </p>
    </div>

    <div class="mt-4 flex gap-4 justify-end">
        <x-buttons.btn
            class="!bg-esg4 !text-esg6 !border !border-[#44724D]  !block !text-sm !font-medium !normal-case"
            wire:click="closeModal()" text="{!! __('Cancel') !!}" />

        <x-buttons.btn class="!bg-[#44724D] !text-esg4  !block !text-sm !font-medium !normal-case cursor-pointer"
            text="{{ __('Download') }}" wire:click="download" />
    </div>
</div>
