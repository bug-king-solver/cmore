<div>
    <x-modals.confirm click="save">
        <x-slot name="title">
            {{ __('Duplicate questionnaire') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Are you sure you want to duplicate it?') }}
        </x-slot>
    </x-modals.confirm>
</div>
