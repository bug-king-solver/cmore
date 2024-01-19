<?php

namespace App\Http\Livewire\Targets\Modals;

use App\Models\Tenant\Target;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Target | int $target;

    public function mount(Target $target)
    {
        $this->target = $target;
        $this->authorize("targets.delete.{$this->target->id}");
    }

    public function render()
    {
        return view('livewire.tenant.targets.delete');
    }

    public function delete()
    {
        $this->target->users()->sync([]);

        $this->target->tags()->sync([]);

        $this->target->dropResourceFromGroup($this->target);

        $this->target->delete();

        $this->emit('targetsChanged');

        $this->closeModal();
    }
}
