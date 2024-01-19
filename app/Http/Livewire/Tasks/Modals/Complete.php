<?php

namespace App\Http\Livewire\Tasks\Modals;

use App\Http\Livewire\Traits\TasksTrait;
use App\Models\Tenant\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Complete extends ModalComponent
{
    use AuthorizesRequests;
    use TasksTrait;

    public Task | int $task;

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->authorize("tasks.complete.{$this->task->id}");
    }

    public function render()
    {
        return view('livewire.tenant.tasks.complete');
    }

    public function markAsComplete()
    {
        $this->task->update([
            'completed' => ! $this->task->completed,
        ]);

        $this->closeModal();
        $this->emit('taskSaved');
        $this->emitTo('targets.show', 'targetsChanged');
        $this->emit('taskUpdated');
    }
}
