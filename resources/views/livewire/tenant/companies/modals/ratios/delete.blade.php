<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Remove asset') }}
        </x-slot>

        <x-slot name="question">
            {{ __('You’re about to remove an asset from the list. Keep in mind that this is just for the simulation scenario.') }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
