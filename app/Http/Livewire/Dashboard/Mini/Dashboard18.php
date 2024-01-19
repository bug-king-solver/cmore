<?php

namespace App\Http\Livewire\Dashboard\Mini;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard18 extends Component
{
    use DashboardCalcs;

    public $questionnaire;
    public $data;
    protected $typeId = 18;

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

        return view('livewire.tenant.dashboard.mini.under_construction');
    }
}
