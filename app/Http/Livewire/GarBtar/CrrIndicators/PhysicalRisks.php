<?php

namespace App\Http\Livewire\GarBtar\CrrIndicators;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\BusinessSector;
use Illuminate\View\View;
use Livewire\Component;

class PhysicalRisks extends Component
{
    use BreadcrumbsTrait;

    /**
     * Mount default values
     */
    public function mount()
    {
        $this->periodList = [
            '2021' => 2021,
            '2022' => 2022,
            '2023' => 2023,
        ];
        
        $this->addBreadcrumb(__('Regulation'));
        $this->addBreadcrumb(__('ESG/CRR Indicators'));
        $this->addBreadcrumb(__('Physical Risks'));
    }

    /**
     * Rander view
     */
    public function render(): View
    {
        return view('livewire.tenant.garbtar.crrindicators.physicalrisks');
    }
}
