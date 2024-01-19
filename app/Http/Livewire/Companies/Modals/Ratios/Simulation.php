<?php

namespace App\Http\Livewire\Companies\Modals\Ratios;

use App\Models\Enums\Regulamentation\AssetTypeEnum;
use App\Models\Tenant\Company;
use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Tenant\GarBtar\BankSimulation;
use App\Models\Tenant\GarBtar\BankSimulationAssets;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class Simulation extends ModalComponent
{
    use AuthorizesRequests;

    public Company | int $company;

    public string $name;

    public function rules()
    {
        return [
            'name' => 'required|min:0|max:255',
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(Company $company)
    {
        $this->company = $company;
    }

    public function render()
    {
        return view('livewire.tenant.companies.modals.ratios.simulation');
    }

    public function save()
    {
        $data = $this->validate();
        $simulation = new BankSimulation();
        $simulation->name = $data['name'];
        $simulation->company_id = $this->company->id;
        $simulation->save();
        foreach ($this->company->bankAssets->toArray() as $asset) {
            $bankAssets = new BankSimulationAssets();
            $bankAssets->bank_simulation_id = $simulation->id;
            unset($asset['created_at']);
            unset($asset['updated_at']);
            unset($asset['id']);
            unset($asset['original']);
            unset($asset['company_id']);
            $asset[BankAssets::SIMULATION][BankAssets::REAL] = true;
            $asset[BankAssets::SIMULATION][BankAssets::BANK] = true;
            $bankAssets->forceFill($asset);
            $bankAssets->save();
        }
        $this->emit('addedSimulation');

        $this->closeModal();
    }
}
