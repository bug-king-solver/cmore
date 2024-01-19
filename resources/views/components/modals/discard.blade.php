<div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 z-20 overflow-y-auto overflow-x-clip">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="transition-all transform bg-white rounded-lg shadow-xl lg:w-1/3 md:w-1/5 w-1/6 p-4"
                @click.away="showModal = false">
                <div class="modal-header flex flex-row">
                    <div class="text-esg6 text-xl font-bold flex-grow">
                        {{ $title ?? '' }}
                    </div>
                    <div @click="showModal = false"
                        class="bg-esg7/50 rounded-full w-6 h-6 flex items-center cursor-pointer">
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
                        class="!bg-esg4 !text-esg8 !border !border-esg8 !block !text-sm !font-medium !normal-case !rounded"
                        @click="showModal = false" text="{!! __('Cancel') !!}" />

                    <x-buttons.cancel-href href="{{ $discard ?? '' }}" text="{!! __('Discard') !!}"
                        class="bg-esg5 !text-white !border !border-esg5 !block !text-sm !font-medium !normal-case"></x-buttons.cancel-href>
                </div>
            </div>
        </div>
    </div>
</div>
