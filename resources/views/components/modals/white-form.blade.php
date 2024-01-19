<div class="bg-esg4 px-4 pt-5 pb-5 sm:p-6 sm:pb-4">
    <div class="mt-3 text-center sm:mt-0 sm:text-left">
        <h3 class="text-esg29 relative text-2xl font-extrabold" id="modal-headline">
            {{ $title }}
            <div wire:click="closeModal()" class="flex text-center border-esg6 absolute right-0 top-0 cursor-pointer rounded-full border-2 w-7 h-7 text-sm font-bold leading-3"><div class="m-auto">X</div></div>
        </h3>
        <div class="mt-3">
            {{ $slot }}
        </div>
    </div>
</div>

<div class="mt-4 flex gap-4 justify-end p-5">
    <x-buttons.cancel class="!text-esg6 !border-esg6"></x-cancel>
    <x-buttons.save></x-save>
</div>
