<?php

namespace App\Http\Livewire\Companies\Modals\Ratios;

use App\Models\Tenant\GarBtar\BankAssets;

class Real extends BaseAsset
{

    public BankAssets $asset;

    public function mount(BankAssets $asset)
    {
        $this->asset = $asset;
        $this->isSimulation = false;
        $this->loadBaseData($this->asset[BankAssets::TYPE], $this->asset->company);
        $this->purpose = (isset($this->asset[BankAssets::SPECIFIC_PURPOSE]))
                ? $this->asset[BankAssets::SPECIFIC_PURPOSE] === "S"
                : null;
    }

    public function save()
    {
        $this->saveBase($this->asset, null, true);
    }
}
