<?php

namespace App\Http\Livewire\GarBtar;

use App\Models\Tenant\BusinessSector;
use Illuminate\View\View;
use Livewire\Component;

class EsgCrrIndicator extends Component
{
    public $periodList;

    public $period = 2022;

    public $chart;

    public $kpi = 'gar';

    public $business = 'volum';

    public $stockflow = 'stock';

    public $ratio = null;

    public $graphOption = null;

    public $data;

    public $itemsInMainSectorsGraph = '5';

    public $itensInMainSectorsTable = '20';

    protected $listeners = ['refreshComponent' => 'refreshGraph'];

    /**
     * Mount default values
     */
    public function mount()
    {
        $this->periodList = [
            '2022' => 2022,
            '2023' => 2023,
        ];
    }

    /**
     * Rander view
     */
    public function render(): View
    {
        return view('livewire.tenant.garbtar.esg_crr-indicators');
    }

    /**
     * Update kpi
     */
    public function changeKpi($value)
    {
        $this->kpi = $value;
        $this->emit('refreshComponent');
    }

    /**
     * Update Business
     */
    public function changeBusiness($value)
    {
        $this->business = $value;
        $this->emit('refreshComponent');
    }

    /**
     * Update stock/flow
     */
    public function changeStockFlow($value)
    {
        $this->stockflow = $value;
        $this->emit('refreshComponent');
    }

    /**
     * Upate ration
     */
    public function updateRation($value = null)
    {
        $this->ratio = $value;
        $this->graphOption = null;
    }

    /**
     * Update graph section
     */
    public function updateGraphselection($value = null)
    {
        $this->ratio = null;
        if ($this->graphOption == $value) { // If we click 2nd time on same button will reset value to default null
            $this->graphOption = null;
        } else {
            $this->graphOption = $value;
        }
    }

    /**
     * Dispatch event to refresh the charts
     */
    public function refreshGraph()
    {
        $this->dispatchBrowserEvent('chartUpdated');
    }
}
