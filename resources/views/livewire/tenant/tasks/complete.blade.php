<div>
    <x-modals.confirm click="markAsComplete">
        <x-slot name="title">
            @if ($this->task->completed)
                {{ __('Mark as Incomplete') }}
            @else
                {{ __('Mark as Done') }}
            @endif
        </x-slot>

        <x-slot name="question">
            @if (!$this->task->completed)
                {{ __('Do you want to mark this task: :task as done ?', ['task' => $task->name]) }}
            @else
                {{ __('Do you want to mark this task: :task as not done ?', ['task' => $task->name]) }}
            @endif
        </x-slot>
    </x-modals.confirm>
</div>
