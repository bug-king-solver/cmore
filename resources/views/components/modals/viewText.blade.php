<div class="fixed inset-0 z-10 overflow-y-auto overflow-x-clip" wire:click="closeModal()">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="transition-all transform bg-white rounded-lg shadow-xl lg:w-1/3 md:w-1/5 w-1/6 p-4">
            <div class="modal-header flex flex-row">
                <div class="text-esg6 text-xl font-bold flex-grow">
                    {{ $title ?? '' }}
                </div>
                <div wire:click="closeModal()" class="bg-esg7/50 rounded-full w-6 h-6 flex items-center cursor-pointer">
                    <div class="m-auto">X</div>
                </div>
            </div>
            <div class="mt-4 lg:max-h-[500px] max-h-[350px] h-auto relative overflow-y-scroll w-auto">
                <p class="text-sm text-esg8 w-auto">
                    {{ $text ?? ($placeholder ?? '') }}
                </p>
            </div>
            <div class="mt-4 flex gap-4 justify-end">
                <x-buttons.btn
                    class="!bg-esg4 !text-esg16 !border !border-esg16 !block !text-sm !font-medium !normal-case"
                    wire:click="closeModal()" text="{!! __('Close') !!}" />
            </div>
        </div>
    </div>
</div>
