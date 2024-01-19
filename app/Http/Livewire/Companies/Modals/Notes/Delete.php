<?php

namespace App\Http\Livewire\Companies\Modals\Notes;

use App\Models\Tenant\InternalNotes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public InternalNotes | int $note;

    public function mount(InternalNotes $note)
    {
        $this->note = $note;
        tenant()->see_only_own_data || $this->authorize("companies.update.{$this->note->company}");
    }

    public function render()
    {
        return view('livewire.tenant.companies.modals.notes.delete');
    }

    public function delete()
    {
        $this->note->delete();
        $this->emit('companyRefresh');
        $this->closeModal();
    }
}
