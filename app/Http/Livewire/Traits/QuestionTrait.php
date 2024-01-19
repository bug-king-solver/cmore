<?php

namespace App\Http\Livewire\Traits;

use App\Enums\Questionnaire\AssignmentType;
use App\Models\Tenant\Answer;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOption;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Userable;

trait QuestionTrait
{
    public int | Questionnaire $questionnaire;

    public int | Question  $question;

    public ?string $questionRef;

    public ?int $questionHighlighted;

    public ?Answer $answer;

    public ?QuestionOption $option;

    protected $dataType = 'json';

    public $value;

    public $answered_questionsByCategory;

    public bool $showComments = false;

    public bool $commentIsRequired;

    public bool $attachmentIsRequired;

    public int $commentsCount = 0;

    public array | null $assignment_type;

    public int $attachmentsCount = 0;

    public array $validators = [];

    public array $assigners = [];

    public array $emissionsFactors = [];
    public array $orignalEmissionsFactors = [];

    public int $isAssigned;

    public $glossary;

    public bool $not_applicable = false;

    public bool $not_reported = false;

    public $customData = [];
    public $customDataUnits = [];
    public $optionsConfig = [];

    /**
     * Mount the component
     */
    public function mount(Questionnaire $questionnaire, Question $question)
    {
        $this->beforeMount();

        $this->questionnaire = $questionnaire;
        $this->question = $question;

        $this->questionRef = $question->ref2;
        $this->answer = Answer::firstOrCreateByQuestionnaireIdAndQuestionId(
            $this->questionnaire->id,
            $this->question->id
        );

        $this->commentIsRequired = $this->question->questionOptions
            ->pluck('comment_required')
            ->filter(fn ($option) => $option)
            ->count();

        $this->attachmentIsRequired = $this->question->questionOptions
            ->pluck('attachment_required')
            ->filter(fn ($option) => $option)
            ->count();

        $this->not_applicable = $this->answer->not_applicable ?? false;
        $this->not_reported = $this->answer->not_reported ?? false;

        $this->prefill();
        $this->afterMount();

        $this->question->questionOptions->map(function ($questionOption) {
            $answer = $this->question->answer;
            $answerData = parseStringToArray($answer->data);
            // if the indicator has emission factor, enter in this if
            if ($emission = $questionOption['indicator']['emissions']['default_factor'] ?? null) {
                $emissionValue = $answerData['emissionFactors'][$questionOption->option->id] ?? $emission;
                $this->emissionsFactors[$questionOption->option->id] = $emissionValue;
                $this->orignalEmissionsFactors[$questionOption->option->id] = $emission; // Keep the original value
            }
        });
    }

    /**
     * Trait render method
     */
    public function render()
    {
        $this->question->questionOptions = $this->question->questionOptions->filter(function ($option) {
            return ($option->deleted_at == null || $option->deleted_at >= $this->questionnaire->created_at)
                && $option->created_at < $this->questionnaire->created_at;
        });

        if ($this->questionnaire->type->id == 15) {
            //TODO:: once we change in Seeders or csv will remvoe this condition and loop.
            if ($this->question->answer_type == 'checkbox-obs-decimal') {
                $this->question->answer_type = 'checkbox-obs-decimal-more';
            }
        }

        return view('livewire.tenant.questionnaires.question');
    }

    /**
     * Method to be executed before the mount()
     */
    public function beforeMount()
    {
        // nothing to do
    }

    public function saveComment()
    {
        // Without events to prevent fire Answer::save() method
        $this->answer->comment = $this->answer->comment ?: null;
        $this->answer->save([], true);
    }

    /**
     * Get the listeners for this component
     */
    protected function getListeners()
    {
        $listener = [
            "attachmentsChanged{$this->answer->id}" => 'attachmentsChanged',
            "usersChanged{$this->answer->id}" => 'usersChanged',
            'comment' => 'commentCreated',
            'delete' => 'commentDeleted',
        ];

        if ($this->question->parent_id) {
            $listener["assignChanged{$this->question->parent_id}"] = 'assignChanged';
        }

        return $listener;
    }

    /**
     * Depending on the user's assignment_type , change the user to assigners or validators
     * @param int $number
     * @param string $assignmentType
     */
    public function usersChanged($number, $assignmentType): void
    {
        $users = Userable::where('userable_id', $this->answer->id)->where('userable_type', Answer::class)->get();
        $this->assigners = [];
        $this->validators = [];
        foreach ($users as $user) {
            if ($user->assignment_type == AssignmentType::CAN_ANSWER->value) {
                $this->assigners[] = $user->user_id;
            }
            if ($user->assignment_type == AssignmentType::CAN_VALIDATE->value) {
                $this->validators[] = $user->user_id;
            }
        }
    }

    /**
     * Event to be executed when a new attachement is uploaded to the answer
     * @param int $number
     */
    public function attachmentsChanged($number): void
    {
        $this->attachmentsCount += $number;
    }

    /**
     * Event to be executed when a comment is deleted
     */
    public function commentCreated(): void
    {
        $this->commentsCount++;
    }

    /**
     * Event to be executed when a comment is deleted
     */
    public function commentDeleted(): void
    {
        $this->commentsCount--;
    }

    /**
     * Method to be executed after the mount()
     */
    public function afterMount()
    {
        // nothing to do
    }

    /**
     * Trait mount method
     */
    public function mountQuestionTrait(): void
    {
        foreach ($this->question->questionOptions as $i => $questionOption) {
            if ($questionOption->option->is_co2_equivalent) {
                continue;
            }
            $unitType = $questionOption->indicator->unit_qty ?? null;
            $unitDefault = $questionOption->indicator->unit_default ?? null;
            if($unitDefault !== null){
                $unitDefault = strtolower($unitDefault);
                if (!isset($this->customDataUnits[$questionOption->option->id])) {
                    $this->customDataUnits[$questionOption->option->id] = $unitType;
                    $this->customDataUnits[$questionOption->option->id] .= ".{$unitDefault}";
                }
            }
        }
    }

    /**
     *
     */
    public function prefill(): void
    {
        $method = 'prefill' . ucfirst($this->dataType);

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     *
     */
    public function prefillJson(): void
    {
        $this->value = isset($this->answer->value)
            ? json_decode($this->answer->value, true)
            : [];
    }

    /// Add custom dataestava a
    public function prefillCheckbox(): void
    {
        $values = isset($this->answer->value)
            ? json_decode($this->answer->value, true)
            : [];
        $customData = [];

        array_walk(
            $values,
            function (&$value, $key) use (&$customData) {
                if (is_bool($value)) {
                    $customData[$key] = '';
                } else {
                    $customData[$key] = $value;
                    $value = $value
                        ? true
                        : false;
                }
            }
        );

        $this->value = $values;
        $this->customData = $customData;
    }

    /**
     * Validate if the questionnaire is not submitted yet and assign values to the answer
     */
    public function beforeSave(): void
    {
        abort_if(
            $this->questionnaire->isSubmitted(),
            403,
            'You can\'t change a submitted questionnaire'
        );

        if (method_exists($this, 'defaultUnit')) {
            $this->defaultUnit();
        }

        $this->answer->option = $this->option ?? null;
    }

    /**
     * Method to be executed after saving the answer
     */
    public function afterSave(): void
    {
        $this->question->answer->value = $this->answer->value;
        $this->emitTo('questionnaires.menu', '$refresh');
        $this->emitTo('questionnaires.progress', '$refresh');
        $this->emitTo('questionnaires.ready-to-submit', '$refresh');
        $this->emitTo('companies.flow.next-button', '$refresh');
        $this->emit('questionnaire-filter-applied');

        if ($this->question->category_id && $this->questionnaire->id) {
            $this->emit('show::answered_count_changed', $this->question->category_id, $this->questionnaire->id);
        }
    }

    /**
     * After Validating the questionnaire submission and deleting
     * the associated Userable records based on the answer's ID and type,
     * Create or retrieve the Answer model based on the questionnaire
     *  and question IDs
     */
    public function clearAnswer(): void
    {
        $this->beforeSave();
        $this->assigners = [];
        $this->validators = [];
        Userable::where('userable_id', $this->answer->id)->where('userable_type', Answer::class)->delete();
        $this->answer->delete();
        $this->answer = Answer::firstOrCreateByQuestionnaireIdAndQuestionId(
            $this->questionnaire->id,
            $this->question->id
        );
        $this->afterSave();
    }

    /**
     * Toggle the validation status of the answer Save the updated answer
     */
    public function toggleAnswerValidation(): void
    {
        $this->beforeSave();
        $this->answer->validation = !$this->answer->validation;
        $this->answer->save();
        $this->question->answer->validation = $this->answer->validation;
        $this->afterSave();
    }

    /**
     * Update the "not_applicable" checkbox status of the answer
     *
     */
    public function updatedNotApplicable($value)
    {
        return $this->answer->update(['not_applicable' => $value]);
    }

    /**
     * Update the "not_reported" checkbox status of the answer
     *
     */
    public function updatedNotReported($value)
    {
        return $this->answer->update(['not_reported' => $value]);
    }
}
