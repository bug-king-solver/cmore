<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy;

use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Report extends Component
{
    use AuthorizesRequests;

    public $negocios;

    public $capx;

    public $opex;

    public $activities;

    public $totalBusinessVolume;

    public $totalCapex;

    public $totalOpex;

    public int|Questionnaire $questionnaire;

    public $taxonomy;

    public $description;

    public $substancialContribute;

    public $npsObjectives;

    public $results;

    public $safeguards;


    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $this->taxonomy = Taxonomy::where('questionnaire_id', $this->questionnaire->id)->get()->first();
        $this->activities = TaxonomyActivities::where('taxonomy_id', $this->taxonomy->id)->get();

        $this->totalBusinessVolume = $this->taxonomy->summary['total']['volume'];
        $this->totalCapex = $this->taxonomy->summary['total']['capex'];
        $this->totalOpex = $this->taxonomy->summary['total']['opex'];
        $this->safeguards = parseStringToArray($this->taxonomy->safeguard ?? '');

        foreach ($this->activities as $activity) {
            $this->substancialContribute[$activity->id] = $activity->filterContributeObjectivesData();
            $this->npsObjectives[$activity->id] = $activity->filterNpsObjectivesData();
        }

        return view('livewire.tenant.taxonomy.report.report');
    }
}
