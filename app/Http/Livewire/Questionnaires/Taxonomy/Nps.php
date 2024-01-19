<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Nps extends Component
{
    use AuthorizesRequests;
    use BreadcrumbsTrait;

    public $questionnaire;

    public $activity;

    public $objectives;

    protected $listeners = [
        'taxonomyActivityUpdated' => '$refresh',
    ];

    /**
     * Mount the component.
     * @param  int  $questionnaire
     * @param  int  $activity
     * @param  int|null  $questionid
     * @return void
     */
    public function mount($questionnaire, $code)
    {
        $this->questionnaire = Questionnaire::where('id', $questionnaire)
            ->with('company')
            ->with('taxonomy.activities.sector')->firstOrFail();

        $id = $code;
        $this->activity = $this->questionnaire->taxonomy->activities()->where("id", $id)->firstOrFail();
        $this->objectives = $this->activity->filterNpsObjectivesData();

        $this->addBreadcrumb(__('Questionnaires'), route('tenant.questionnaires.index'));
        $this->addBreadcrumb(__('Taxonomy'), null);
        $this->addBreadcrumb(__('NPS'), null);
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('livewire.tenant.taxonomy.dnsh.index');
    }

    /**
     * Check if all objectives are completed and if the activity is elegible.
     */
    public function completeActivity()
    {
        $this->activity->completeNps();
        $this->activity->refresh();

        return redirect()->route("tenant.taxonomy.show", [
            'questionnaire' => $this->activity->taxonomy->questionnaire_id,
        ]);
    }
}
