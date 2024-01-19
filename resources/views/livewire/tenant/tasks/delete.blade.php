<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete task') }}
        </x-slot>

        <x-slot name="question">
            {{ __('Do you want to delete the task ":task"?', ['task' => $task->name]) }}
        </x-slot>
    </x-modals.confirm-delete>
</div>
