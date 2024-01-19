<?php

namespace App\Http\Livewire\Companies\Modals\Ratios;

use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Tenant\GarBtar\BankSimulation;
use App\Models\Tenant\GarBtar\BankSimulationAssets;

class Assets extends BaseAsset
{

    public BankSimulation | int $simulation;

    public BankSimulationAssets | null $asset = null;

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(BankSimulation $simulation, $asset = null)
    {
        $this->simulation = $simulation;
        $this->isSimulation = true;

        $assetType = null;
        if (isset($asset)) {
            $this->asset = $asset;
            $assetType = $this->asset[BankAssets::TYPE];
        } else {
            $this->asset = new BankSimulationAssets();
        }
        $this->loadBaseData($assetType, $this->simulation->company);
    }

    public function save()
    {
        $this->saveBase($this->asset, $this->simulation, $this->asset[BankAssets::SIMULATION][BankAssets::BANK] ?? false);
    }
}
