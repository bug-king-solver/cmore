<?php

namespace App\Http\Livewire\Dashboard\Mini;

use App\Http\Livewire\Traits\Dashboards\DashboardCalcs;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard20 extends Component
{
    use DashboardCalcs;

    public $questionnaire;
    protected $typeId = 20;

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
