<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy;

use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\Taxonomy\TaxonomyActivitiesQuestTrait;
use App\Models\Tenant\Questionnaire;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class NpsQuestionnaire extends Component
{
    use BreadcrumbsTrait;
    use AuthorizesRequests;
    use TaxonomyActivitiesQuestTrait;

    public $questionnaire;
    public $questionnaireId;

    public $activity;
    public $activityCode;

    public $objective;

    public $objectives;

    public $questions;

    public $answeredQuestion;

    public string $questionnaireColumn = 'dnsh';
    public string $redirectRoute = 'dnsh';

    public $eligibleText;
    public $notEligibleText;

    protected $listeners = [
        'taxonomyActivityUpdated' => '$refresh',
        'toggleVerifiedStatus' => 'toggleVerifiedStatus',
        'resetarQuestionnaire' => 'resetarQuestionnaire',
        'completeQuestionnaire' => 'completeQuestionnaire',
    ];

    /**
     * Mount the component.
     * @param  int  $questionnaire
     * @param  int  $activity
     * @param  int|null  $questionid
     * @return void
     */
    public function mount($questionnaire, $code, $objective)
    {
        $this->questionnaireId = $questionnaire;
        $this->objective = $objective;

        $this->questionnaire = Questionnaire::where('id', $this->questionnaireId)
            ->with('taxonomy.activities')->firstOrFail();

        $id = $code;
        $this->activity = $this->questionnaire->taxonomy->activities()->where("id", $id)->firstOrFail();

        $column = $this->questionnaireColumn;
        $this->objectives = parseStringToArray($this->activity->$column);

        $this->objectives = collect(parseStringToArray($this->objectives['data'] ?? ''));

        $this->objectives = $this->objectives->filter(function ($obj) {
            return $obj['arrayPosition'] == $this->objective;
        });

        $this->objectives = $this->objectives->first();

        $this->eligibleText = __('Verified no significant harm');
        $this->notEligibleText = __('Not verified no significant harm');

        $this->addBreadcrumb(__('Questionnaires'), route('tenant.questionnaires.index'));
        $this->addBreadcrumb(__('Taxonomy'), null);
        $this->addBreadcrumb(__('NPS'), null);
        $this->addBreadcrumb(translateJson($this->objectives['name']), null);
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $this->questions = $this->objectives['questions'] ?? [];

        $this->answeredQuestion = collect($this->questions)->map(function ($question) {
            if ($question['enabled'] === 1) {
                return (string)$question['id'];
            }
        })->toArray();

        $this->answeredQuestion = array_filter($this->answeredQuestion, function ($value) {
            return $value !== null;
        });

        $this->answeredQuestion = array_values($this->answeredQuestion);
        $this->calcQuestionnaireProgress($this->questions);

        return view('livewire.tenant.taxonomy.form.questions', ['backRoute' => 'tenant.taxonomy.dnsh']);
    }

    /**
     * Mark the activity as not verified.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleVerifiedStatus()
    {
        return redirect()->route('tenant.taxonomy.dnsh', [
            'questionnaire' => $this->questionnaire,
            'code' => $this->activity->id
        ]);
    }

    /**
     * Complete the questionnaire.
     */
    public function completeQuestionnaire($route)
    {
        return redirect()->route("tenant.taxonomy.{$route}", [
            'questionnaire' => $this->questionnaire,
            'code' => $this->activity->id
        ]);
    }
}
