<?php

namespace App\Http\Livewire\Companies\Modals;

use App\Models\Tenant\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Delete extends ModalComponent
{
    use AuthorizesRequests;

    public Company | int $company;

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->authorize("companies.delete.{$this->company->id}");
    }

    public function render()
    {
        return view('livewire.tenant.companies.delete');
    }

    public function delete()
    {
        /** detach all users attached to the company */
        $this->company->users()->sync([]);

        $this->company->tags()->sync([]);

        $this->company->delete();

        $this->emit('companiesChanged');

        $this->closeModal();
    }
}
