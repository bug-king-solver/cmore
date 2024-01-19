<?php

namespace App\Http\Livewire\Dashboard\Mini;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use App\Models\Tenant\Questionnaire;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard12 extends Component
{
    use DashboardCalcs;

    public array $data;
    public $questionnaire;
    public $physicalRisks;
    protected $typeId = 12;


    /**
     * Mount the component
     * @param $questionnaires
     */
    public function mount($questionnaires)
    {
        $this->questionnaireIdLists = $questionnaires;
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        $this->questionnaire = $this->questionnaireList->where('id', $this->questionnaireId)->first();
        $this->physicalRisks = $this->questionnaire->physicalRisks;

        return view('livewire.tenant.dashboard.mini.physicalrisks');
    }
}
