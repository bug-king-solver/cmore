<?php

namespace App\Http\Livewire\Questionnaires\Taxonomy;

use App\Http\Livewire\Traits\Taxonomy\TaxonomySafeguardTrait;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Safeguards extends Component
{
    use AuthorizesRequests;
    use TaxonomySafeguardTrait;

    public $questionnaire;
    public int $questionnaireId;
    public array $safeguard;
    public $safeguardQuestions;
    public $answeredQuestion;

    protected $listeners = [
        'taxonomyActivityUpdated' => '$refresh',
        'toggleVerifiedStatus' => 'toggleVerifiedStatus',
        'resetarQuestionnaire' => 'resetarQuestionnaire',
        'completeQuestionnaire' => 'completeQuestionnaire',
    ];

    /**
     * Mount the component.
     * @param  int  $questionnaire
     * @return void
     */
    public function mount($questionnaire)
    {
        $this->questionnaireId = $questionnaire;

        $this->questionnaire = Questionnaire::where('id', $questionnaire)
            ->with('company', 'taxonomy.activities')->firstOrFail();
        $this->taxonomy = $this->questionnaire->taxonomy;

        $safeguard = parseStringToArray($this->taxonomy->safeguard ?? '');

        if ($safeguard == null || count($safeguard) == 0) {
            // Set the default question to safeguards , from our .json file
            $this->safeguard = $this->taxonomy->createSafeguard();
            $this->safeguardQuestions = $this->safeguard['questions'];
        } else {
            $this->safeguard = parseStringToArray($this->questionnaire->taxonomy->safeguard ?? '');
            $this->safeguardQuestions = parseStringToArray($this->safeguard['questions'] ?? '');
        }
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $this->answeredQuestion = collect($this->safeguardQuestions)->map(function ($question) {
            if ($question['enabled'] === 1) {
                $id = $question['id'];
                $id = "$id";
                return $id;
            }
        })->toArray();

        $this->answeredQuestion = array_filter($this->answeredQuestion, function ($value) {
            return $value !== null;
        });

        $this->answeredQuestion = array_values($this->answeredQuestion);
        $this->calcQuestionnaireProgress($this->safeguardQuestions);

        return view('livewire.tenant.taxonomy.form.safeguard');
    }

    /**
     * Mark the activity as not verified.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleVerifiedStatus()
    {
        $this->resetarQuestionnaire();

        return redirect()->route('tenant.taxonomy.safeguards', [
            'questionnaire' => $this->questionnaire,
        ]);
    }

    /**
     * Complete the questionnaire.
     */
    public function completeQuestionnaire()
    {
        return redirect()->route("tenant.taxonomy.show", [
            'questionnaire' => $this->questionnaire->id,
        ]);
    }
}
