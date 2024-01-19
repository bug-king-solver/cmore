<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Models\Enums\Taxonomy\ShortNameForObjectives;
use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    use BreadcrumbsTrait;

    use AuthorizesRequests;

    public int|Questionnaire $questionnaire;

    public null|Taxonomy $taxonomy;

    public array $safeguards;

    public array $businessResume;

    public array $activitiesResume;

    public $substancialContribute;

    public $npsObjectives;

    public $activitiesNameLabels = [];

    public $canSubmit = true;

    protected $listeners = [
        'taxonomyUpdated' => '$refresh',
        'resetContribute' => 'resetContribute',
        'resetNps' => 'resetNps',
        'resetSafeguards' => 'resetSafeguards',
    ];

    /**
     * Mount the component.
     *
     * @param  \App\Models\Tenant\Questionnaire  $questionnaire
     * @return void
     */
    public function mount(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;

        $this->authorize("questionnaires.view.{$this->questionnaire->id}");

        if (! $this->questionnaire->is_ready) {
            return false;
        } elseif (! $this->questionnaire->canBeAccessedBy(auth()->user())) {
            abort(403);
        }

        if ($this->questionnaire->questionnaire_type_id != 10) {
            session()->flash('messageText', __('The questionnaire is not a taxonomy questionnaire'));
            return redirect()->route('tenant.questionnaires.panel');
        }

        $taxonomy = $this->questionnaire->taxonomy;

        if (!$taxonomy) {
            $this->questionnaire->taxonomy()->create();
        }

        $this->addBreadcrumb(__('Questionnaires'), route('tenant.questionnaires.index'));
        $this->addBreadcrumb(__('Taxonomy'), null);
        $this->addBreadcrumb($this->questionnaire->id);
    }


    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $this->questionnaire = Questionnaire::with('taxonomy.activities.sector.parent')
            ->whereId($this->questionnaire->id)->first();

        $this->taxonomy = $this->questionnaire->taxonomy;

        if (!$this->taxonomy) {
            return view('livewire.tenant.questionnaires.not-ready');
        }

        if ($this->taxonomy->started_at == null) {
            return view('livewire.tenant.taxonomy.welcome');
        }

        $this->businessResume = parseStringToArray($this->taxonomy->summary ?? '');
        $this->safeguards = parseStringToArray($this->taxonomy->safeguard ?? '');

        $this->canSubmit = true;


        if ($this->taxonomy->activities->count() == 0) {
            $this->canSubmit = false;
        }

        foreach ($this->taxonomy->activities as $activity) {
            $contribute = parseStringToArray($activity->contribute ?? '');
            $dnsh = parseStringToArray($activity->dnsh ?? '');
            $resume = parseStringToArray($activity->summary);

            if (!$activity->NpsIsAnswered) {
                $this->canSubmit = false;
            }

            $this->substancialContribute[$activity->id] = $contribute;
            $this->npsObjectives[$activity->id] = $dnsh;

            $this->substancialContribute[$activity->id]['data'] = $activity->filterContributeObjectivesData();
            $this->npsObjectives[$activity->id]['data'] = $activity->filterNpsObjectivesData();

            $this->activitiesResume[$activity->id] = [
                'volume' => $resume['volume']['value'] ?? 0,
                'capex' => $resume['capex']['value'] ?? 0,
                'opex' => $resume['opex']['value'] ?? 0,
            ];

            $this->activitiesNameLabels[$activity->id]['text'] = __("Atividade projetada");
            $this->activitiesNameLabels[$activity->id]['color'] = 'bg-esg7/10';

            if ($resume['volume'] > 0 || $resume['capex'] > 0 || $resume['opex'] > 0) {
                $this->activitiesNameLabels[$activity->id]['text'] = __('Atividade real');
                $this->activitiesNameLabels[$activity->id]['color'] = 'bg-esg6/10';
            }
        }

        return view('livewire.tenant.taxonomy.index');
    }

    /**
     * Change the resume volume.
     *
     * @param  mixed  $value
     * @return void
     */
    public function updatedBusinessResume($value, $nested)
    {
        if ($value == "") {
            $value = 0;
        }

        $nested = explode('.', $nested)[1];
        $taxonomy = $this->taxonomy;
        $total = 0;
        foreach ($taxonomy->activities as $activity) {
            $resume = parseStringToArray($activity->summary);
            $total += $resume[$nested]['value'] ?? 0;
        }

        if ($total > $value) {
            $message = [
                'title' => __('Warning'),
                'message' => __('The business resume must be equal or greater than the sum of the activities.'),
            ];
            $this->emit('openModal', 'modals.notification', ['data' => $message]);
            return;
        }
        $data = $taxonomy->summary;
        $data['total'][$nested]['value'] = $value;
        $data['total'][$nested]['percentage'] = $value ? 100 : 0;
        $taxonomy->update([
            'summary' => $data,
        ]);
        $taxonomy->activities->each(function ($activity) use ($nested, $value) {
            $resume = parseStringToArray($activity->summary);
            $resume[$nested]['percentage'] = roundValues(($resume[$nested]['value'] * 100) / $value);
            $activity->update([
                "summary" => $resume,
            ]);
        });
    }

    /**
     * Change the activity resume volume.
     *
     * @param  mixed  $value
     * @return void
     */
    public function updatedActivitiesResume($value, $nested)
    {
        if ($value == "") {
            $value = 0;
        }

        $arr = explode('.', $nested);

        if ($value < 0) {
            $message = [
                'title' => __('Warning'),
                'message' => __("The {$arr[1]} value must be positive."),
            ];
            $this->emit('openModal', 'modals.notification', ['data' => $message]);
            return;
        }

        $activityId = $arr[0];
        $nested = $arr[1];
        $activity = TaxonomyActivities::find($activityId);
        $data = parseStringToArray($activity->summary);

        $taxonomyTotal = $activity->taxonomy->summary['total'][$nested]['value'];

        $activities = $activity->taxonomy->activities->filter(function ($a) use ($activityId) {
            return (int)$a->id != (int)$activityId;
        });


        $total = 0;
        foreach ($activities as $a) {
            $total += (float)$a->summary[$nested]['value'];
        }

        $total += (float)$value;
        if ($total > $taxonomyTotal) {
            $this->activitiesResume[$activity->id][$nested] = $data[$nested];
            $message = [
                'title' => __('Warning'),
                'message' => __('The sum of the activities is greater than the total of the business resume'),
            ];
            $this->emit('openModal', 'modals.notification', ['data' => $message]);
            return;
        }

        $data[$nested]['value'] = $value;
        $data[$nested]['percentage'] = calculatePercentage($value, $taxonomyTotal, 2);
        $activity->update([
            'summary' => $data,
        ]);
    }

    /***
     * update start time to start the questionnaire
     */
    public function start()
    {
        $this->taxonomy->started_at = date('Y-m-d H:i:s');
        $this->taxonomy->save();
        return redirect(request()->header('Referer')); // Refresh the page display conntent correctly.
    }

    /**
     * Reset the contribute.
     */
    public function resetContribute(TaxonomyActivities $activity): void
    {
        $activity->resetContributeSubstantial();
        $activity->resetNps();
        $this->questionnaire->refresh();
        $this->taxonomy->refresh();
        $this->emit('taxonomyUpdated');
    }

    /**
     * Reset the dnsh.
     */
    public function resetNps(TaxonomyActivities $activity): void
    {
        $activity->resetNps();
        $this->questionnaire->refresh();
        $this->taxonomy->refresh();
        $this->emit('taxonomyUpdated');
    }

    /**
     * Reset the safeGurds.
     */
    public function resetSafeguards(): void
    {
        $this->taxonomy->createSafeguard();
        $this->questionnaire->refresh();
        $this->emit('taxonomyUpdated');
    }
}
