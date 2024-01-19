<div>
    <x-modals.white title="{{ $result->exists ? __('Edit') : __('Add') }}">
        
        <div class="mb-5 flex items-center">
            <div class="grow">
                <x-inputs.tomselect
                    :wire_ignore="false"
                    wire:model.defer="attach"
                    remote="{{ route('tenant.attachments') }}"
                    preload="focus"
                    plugins="['remove_button']"
                    placeholder="{{ __('Choose an attachment already uploaded. Search by file name.') }}"
                    :limit="1" />
            </div>
        </div>

        <div class="my-5 text-center text-esg29 text-xl font-bold">{{ __('OR') }}</div>
        
        <x-modals.white-form-row input="file" id="file" label="{{ __('Upload File') }}" />
        <x-modals.white-form-row input="select-small" id="file_type" label="{{ __('File type') }}" :extra="['options' => $mediaTypesList]" placeholder="{{ __('Select file type') }}"/>
    </x-modals.white>
</div>
