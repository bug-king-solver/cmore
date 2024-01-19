<div class="p-4">
    <div class="flex justify-between items-center">
        <label class="text-xl !text-[#03791A] text-bold">
            {{ __('Import activities') }}
        </label>

        <div wire:click="closeModal()" class="bg-esg7/50 rounded-full w-6 h-6 flex items-center cursor-pointer">
            <div class="m-auto">X</div>
        </div>
    </div>

    <div class="mt-4 mb-2">
        <p class="text-sm text-esg8">
            {{ __('Are you sure you want to import the CSV file?') }}
        </p>
    </div>

    <div class="relative flex flex-col mt-10">
        <x-modals.white-form-row input="file" id="fileUpload" label="{{ __('Upload File') }}" />
    </div>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p class="mt-1 text-xs text-red-500">
                {{ $error }}
            </p>
        @endforeach
    @endif

    <div class="mt-4 flex gap-4 justify-end">
        <x-buttons.btn
            class="!bg-esg4 !text-esg6 !border !border-[#44724D]  !block !text-sm !font-medium !normal-case"
            wire:click="closeModal()" text="{!! __('Cancel') !!}" />

        <x-buttons.btn class="!bg-[#44724D] !text-esg4  !block !text-sm !font-medium !normal-case cursor-pointer"
            text="{{ __('Import') }}" wire:click="import" />
    </div>
</div>
