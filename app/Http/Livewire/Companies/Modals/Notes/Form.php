<?php

namespace App\Http\Livewire\Companies\Modals\Notes;

use App\Models\Tenant\Company;
use App\Models\Tenant\InternalNotes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;

    public Company | int $company;

    public InternalNotes | int $note;

    public string $title;

    public string $description;

    public function rules()
    {
        return [
            'title' => 'string|required',
            'description' => 'string|required',
        ];
    }

    public function mount(Company $company, ?InternalNotes $note)
    {
        $this->company = $company;
        tenant()->see_only_own_data || $this->authorize(
            !$this->company->exists
                ? 'companies.create'
                : "companies.update.{$this->company->id}"
        );
        if ($note->exists) {
            $this->note = $note;
            $this->title = $this->note->title;
            $this->description = $this->note->description;
        } else {
            $this->note = new InternalNotes();
        }
    }

    public function render()
    {
        return view('livewire.tenant.companies.modals.notes.form');
    }

    public function save()
    {
        $data = $this->validate();
        $this->note->title = $data['title'];
        $this->note->description = $data['description'];
        $this->note->company_id = $this->company->id;
        $this->note->save();
        $this->closeModal();
        $this->emit('companyRefresh');
    }
}
