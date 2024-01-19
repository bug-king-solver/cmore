<?php

namespace App\Http\Livewire\Tasks\Modals;

use App\Models\Tenant\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Restore extends ModalComponent
{
    use AuthorizesRequests;

    public Task | int $task;

    public function mount($task)
    {
        $this->task = Task::withTrashed()->find($task);
        $this->authorize("tags.restore.{$this->tag->id}");
    }

    public function render()
    {
        return view('livewire.tenant.tasks.restore');
    }

    public function restore()
    {
        $this->task->restore();
        $this->emit('tagsSaved');
        $this->closeModal();
    }
}
