<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Substantial extends Component
{
    use AuthorizesRequests;

    use BreadcrumbsTrait;

    public $questionnaire;

    public $activity;

    public $objectives;

    public bool $hasNPS;

    public bool $editView;

    public $showModal;

    public int $percentageTotal = 0;

    public int $percentageToFullfill = 0;

    protected $listeners = [
        'taxonomyActivityUpdated' => '$refresh',
        'completeActivity' => 'completeActivity',
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
        $this->activity = $this->questionnaire->taxonomy->activities()->where("id", $id)->first();

        $this->objectives = parseStringToArray($this->activity->contribute);

        $this->hasNPS = $this->objectives['verified'] == 1
            ? $this->activity->hasNps
            : false;

        $this->objectives = collect(parseStringToArray($this->objectives['data'] ?? ''));
        $this->showModal = false;
        $this->editView = false;

        $this->objectives = $this->objectives->filter(function ($objective) {
            return translateJson($objective['name']) != '';
        });

        $this->addBreadcrumb(__('Questionnaires'), route('tenant.questionnaires.index'));
        $this->addBreadcrumb(__('Taxonomy'), null);
        $this->addBreadcrumb(__('Substantial Contribute'), null);
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $this->percentageTotal = array_sum(array_column($this->objectives->toArray(), 'percentage'));

        $this->percentageToFullfill = (100 - $this->percentageTotal);
        $this->percentageToFullfill = $this->percentageToFullfill < 0
            ? 0
            : $this->percentageToFullfill;

        return view('livewire.tenant.taxonomy.cs.index');
    }

    /**
     * Change the activity resume volume.
     *
     * @param  mixed  $value
     * @return void
     */
    public function updatedObjectives($value, $nested)
    {
        $arr = explode('.', $nested);
        $objectiveId = $arr[0];
        $nested = $arr[1];

        $activity = $this->activity;
        $substantialContribute = parseStringToArray($activity->contribute);
        $data = parseStringToArray($substantialContribute['data'] ?? '');

        foreach ($data as $i => $d) {
            if ($d['arrayPosition'] == $objectiveId) {
                $data[$i][$nested] = $value;
                if ($value === 0 || $value === "") {
                    $data[$i]['verified'] = null;
                }
            }
        }

        $substantialContribute['data'] = $data;
        $activity->contribute = $substantialContribute;

        $activity->save();
        $this->emit('taxonomyActivityUpdated');
    }

    /**
     * Check if all objectives are completed and if the activity is elegible.
     */
    public function completeActivity()
    {
        if ($this->percentageTotal > 100) {
            session()->flash(
                'messageText',
                __('You cannot complete the activity because the percentage is greater than 100%')
            );
            return redirect()->route(
                'tenant.taxonomy.substantial',
                [
                    'questionnaire' => $this->questionnaire->id,
                    'code' => $this->activity->id
                ]
            );
        }

        $this->activity->completeContribute();
        $this->activity->refresh();


        return redirect()->route("tenant.taxonomy.show", [
            'questionnaire' => $this->activity->taxonomy->questionnaire_id,
        ]);
    }

    /**
     * Open the modal to edit the activity.
     */
    public function openModal()
    {
        $this->showModal = true;
    }

    /**
     * Close the modal to edit the activity.
     */
    public function closeModal()
    {
        $this->showModal = false;
    }

    /**
     * Enable the edit view.
     */
    public function enabledEdit(): void
    {
        $this->activity->resetNps();
        $this->editView = true;
        $this->closeModal();
    }
}
