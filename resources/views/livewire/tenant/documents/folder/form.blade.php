<div>
    <x-modals.white title="{{ $documentFolder->exists ? __('Edit:') : __('Add:') }}">
        <x-modals.white-form-row input="text" id="name" label="{{ __('Name') }}" />
        <x-modals.white-form-row input="select" id="visibility" label="{{ __('Visibility') }}" :extra="['options' => $visibilityOptions]" />
    </x-modals.white>
</div>
