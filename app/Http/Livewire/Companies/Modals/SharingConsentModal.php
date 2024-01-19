<?php

namespace App\Http\Livewire\Companies\Modals;

use App\Models\Tenant\Company;
use App\Models\Tenant\SharingOption;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class SharingConsentModal extends ModalComponent
{
    use AuthorizesRequests;

    public Company | int $company;
    public SharingOption | int $sharingOption;

    public string $bankName = '';
    public string $action = '';

    /**
     * Mount the component.
     * @param Company $company
     * @return void
     */
    public function mount(Company $company, SharingOption $sharing): void
    {
        $this->company = $company;
        $this->sharingOption = $sharing;
        $this->bankName = $sharing->name;

        $this->action = findInCollection($this->company->sharingOptions, 'id', $this->sharingOption->id) ?
            'revoke' :
            'grant';

        $this->authorize("companies.delete.{$this->company->id}");
    }

    /**
     * Get the view / contents that represent the component.
     * @return View
     */
    public function render(): View
    {
        return view('livewire.tenant.companies.modals.sharing-consent');
    }

    /**
     * Method to update the sharing consent
     * @param int $sharingOptionId
     * @return void
     */
    public function save(): void
    {
        $sharingOption = $this->sharingOption;
        $sharingOption->companies()->toggle($this->company->id);
        $this->company->refresh();
        $this->emit('companyRefresh');
        $this->closeModal();
    }
}
