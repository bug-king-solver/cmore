<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete activity') }}
        </x-slot>

        <h3 class="text-esg8 pt-14 text-center font-medium">
        </h3>


        <x-slot name="question">
            {{ __('Do you want to delete the activity ":activity"?', ['activity' => $activity->name]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
