<?php

namespace App\Http\Livewire\Tasks;

use App\Http\Livewire\Traits\TasksTrait;
use App\Models\Tenant\Task;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    use TasksTrait;

    public Task $task;

    public $checklist;

    public $users;

    public $taskProgress;

    public $taskProgressColor;

    protected $listeners = [
        'taskUpdated' => '$refresh',
    ];

    public function mount(Task $task)
    {
        $this->task = $task->with('tags', 'users', 'targets', 'questionnaires', 'checklist')
            ->with(['comments' => function ($query) {
                $query->with('reactions');
            }])
            ->where('id', $task->id)
            ->first();
    }

    public function render(): View
    {
        $this->checklist = $this->task->checklist;
        $this->taskProgress = $this->calcProgress($this->checklist);
        $this->users = $this->task->users;

        return view(
            'livewire.tenant.tasks.show',
            [
                'task' => $this->task,
                'checklist' => $this->checklist,
                'users' => $this->users,
                'taskProgress' => $this->taskProgress,
            ]
        );
    }

    public function toggleChecklist($taskId, $checkListId, $value)
    {
        $isCompleted = ! $value;

        $this->task = Task::find($taskId);

        $this->task->checklist()
            ->where('id', $checkListId)
            ->update([
                'completed' => $isCompleted,
                'completed_at' => $isCompleted ? now() : null,
                'completed_by_user_id' => $isCompleted ? auth()->user()->id : null,
            ]);

        $this->emit('taskUpdated');
    }
}
