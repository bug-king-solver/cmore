<?php

namespace App\Http\Livewire\Tasks\Modals;

use App\Models\Tenant\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Task | int $task;

    public function mount(Task $task)
    {
        $this->task = $task;
        $this->authorize("tasks.delete.{$this->task->id}");
    }

    public function render()
    {
        return view('livewire.tenant.tasks.delete');
    }

    public function delete()
    {
        $this->task->delete();

        $this->emit('tagsSaved');
        $this->emit('taskSaved');

        if (url()->previous() == route('tenant.users.tasks.show', $this->task->id)) {
            return redirect()->route('tenant.users.tasks.index');
        }

        $this->closeModal();
    }
}
