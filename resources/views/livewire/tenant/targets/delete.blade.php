<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete target') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the target ":target"?', ['target' => $target->title]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
