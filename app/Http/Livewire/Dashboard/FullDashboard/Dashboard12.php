<?php

namespace App\Http\Livewire\Dashboard\FullDashboard;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use App\Models\Tenant\Questionnaire;
use Illuminate\View\View;
use Livewire\Component;
use App\Models\Tenant\CompanyAddresses;

class Dashboard12 extends Component
{
    use DashboardCalcs;

    public Questionnaire $questionnaire;
    
    protected $shouldDivideBy1000;
    
    public $physicalRisks;

    public $audits = [];

    public function mount($questionnaireId)
    {
        $this->questionnaire = Questionnaire::find($questionnaireId);
    }

    /**
     * Render view
     */
    public function render(): View
    {
        $this->physicalRisks = $this->questionnaire->physicalRisks;
        // Loop into physical risks -> hazards ['data'] and get the audits , order bby created_at , and create a new array
        foreach ($this->physicalRisks as &$physicalRisk) {
            $physicalRisk['address'] = CompanyAddresses::withTrashed()->find($physicalRisk->company_address_id);
            $this->audits[$physicalRisk->id] = [];
            foreach ($physicalRisk->hazards as $hazard) {
                if (isset($hazard['audits'])) {
                    $this->audits[$physicalRisk->id] = array_merge($hazard['audits'], $this->audits[$physicalRisk->id]);
                }
            }

            $this->audits[$physicalRisk->id] = collect($this->audits[$physicalRisk->id])
                ->sortByDesc('created_at')
                ->all();
            $this->audits[$physicalRisk->id] = array_values($this->audits[$physicalRisk->id]);
        }

        return view('livewire.tenant.dashboard.full-dashboard.physicalrisks');
    }
}
