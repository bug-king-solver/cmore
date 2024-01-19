<?php

namespace App\Http\Livewire\Compliance\DocumentAnalysis\Modals;

use App\Models\Tenant\Compliance\DocumentAnalysis\Result;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Result | int $result;

    public function mount(Result $result)
    {
        $this->result = $result;
        $this->authorize("result.delete.{$this->result->id}");
    }

    public function render()
    {
        return view('livewire.tenant.compliance.document_analysis.modal.delete');
    }

    public function delete()
    {
        $this->result->delete();

        $this->emit('resultAltered');

        $this->closeModal();
    }
}
