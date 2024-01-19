<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Concerns\HasAttachments;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionOptions\Simple;
use App\Models\Traits\HasUsers;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Answer extends Model implements HasMedia
{
    use HasComments;
    use HasAttachments;
    use HasFactory;
    use HasUsers;
    use LogsActivity;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'questionnaire_id',
        'question_id',
        'value',
        'weight',
        'validation',
        'data',
        'sdg_id',
        'initiative_id',
        'assigned_to_user_id',
        'assigned_by_user_id',
        'assigned_at',
        'not_applicable',
        'not_reported',
    ];

    protected $casts = [
        'data' => 'array',
    ];


    public ?QuestionOption $option;

    /**
     * Get the question that owns the answer.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the question that owns the answer.
     */
    public function question()
    {
        return $this->belongsTo(Question::class)->withTrashed();
    }

    public function availableUsers()
    {
        return $this->questionnaire->users;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the parsed value.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function parsedValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $decoded = @json_decode($attributes['value'], true);

                return (json_last_error() === JSON_ERROR_NONE)
                    ? $decoded
                    : $value;
            },
        );
    }

    /**
     * Create the assigned user message
     */
    public function assignedUserMessage($assigner)
    {
        $params = [
            'questionnaire' => $this->questionnaire->id,
            'questionHighlighted' => $this->question->id,
            'category' => $this->question->category_id ?: 0,
        ];

        $questionUrl = tenant()->route('tenant.questionnaires.show', $params);

        return [
            'userName' => $assigner->name,
            'questionDescription' => $this->question->description,
            'message' => __(':userName assigned you the question: ":questionDescription".'),
            'action' => $questionUrl,
        ];
    }

    /**
     * Remove answers of the specific question ids
     */
    public static function removeAnswersByQuestionnaireIdByQuestionIds(int $questionnaireId, array $questionsIds)
    {
        return self::where('questionnaire_id', $questionnaireId)
            ->whereIn('question_id', $questionsIds)
            ->whereNotNull('value')
            ->update(['value' => null, 'weight' => 0]);
    }

    public static function weightByQuestionnaireByCategory(int $questionnaireId, int $categoryId)
    {
        return self::leftJoin('questions', 'questions.id', '=', 'answers.question_id')
            ->where('questionnaire_id', $questionnaireId)
            ->where('category_id', $categoryId)
            ->whereNotNull('value')
            ->sum('answers.weight');
    }

    /**
     * Collect all intiatives, independently of the answer
     */
    public static function questionnaireInitiatives($questionnaire)
    {
        return self::select('question_options.initiative_id')
            ->from('answers')
            ->join('questions', 'questions.id', '=', 'answers.question_id')
            ->join('question_options', function ($join) {
                $join->on('question_options.question_id', '=', 'questions.id');
            })
            ->join('question_option_simples', function ($join) {
                $join->on('question_option_simples.id', '=', 'question_options.question_option_id')
                    ->on('answers.value', '=', 'question_option_simples.value');
            })
            ->where('questionnaire_id', $questionnaire->id)
            ->where('question_options.question_option_type', Simple::class)
            ->whereNotNull('question_options.initiative_id')
            ->get()
            ->pluck('initiative_id')
            ->toArray();
    }

    public static function questionnaireSdgs($questionnaire)
    {
        return self::select('sdg_id')
            ->where('questionnaire_id', $questionnaire->id)
            ->whereNotNull('sdg_id')
            ->distinct()
            ->get()
            ->pluck('sdg_id')
            ->toArray();
    }

    public function save(array $options = [], $dontRunMe = false)
    {
        // Just to know if this answer already exists or not
        // If not exists, means we are just saving it on the database
        // (on questionnaire creation), it is not an answer from the user.
        $exists = $this->exists;

        $this->weight = $this->option->weight ?? 0;
        $this->initiative_id = $this->option->initiative_id ?? null;
        $answer = parent::save($options);

        if ($dontRunMe) {
            return;
        }

        if ($exists) {
            $categories = collect($this->questionnaire->categories)->keyBy('id');
            $questionCategory = $this->question->category;

            $questionnaireId = $this->questionnaire->id;
            $allQuestionnaireCategoryQuestions = Category::where("model_type", 'App\Models\Tenant\QuestionnaireType')
                ->where("model_id", $this->questionnaire->questionnaire_type_id)
                ->with(['questionnaireAnswer' => function ($query) use ($questionnaireId) {
                    $query->where('questionnaire_id', $questionnaireId)
                        ->whereNotNull('value');
                }])
                ->get();

            while ($questionCategory) {
                $categories[$questionCategory->id] = array_merge(
                    $categories[$questionCategory->id],
                    [
                        'questions_answered' => $allQuestionnaireCategoryQuestions->where('id', $questionCategory->id)
                            ->first()->questionnaireAnswer->count(),
                    ]
                );
                /**
                 * In double maturity questionnaire, we have some parent categories who don't have any question
                 * so added this condition to prevent 500 error.
                 */
                if ($questionCategory->parent && isset($categories[$questionCategory->parent->id])) {
                    $questionCategory = $questionCategory->parent;
                } else {
                    $questionCategory = null;
                }
            }

            $this->questionnaire->categories = $categories->toArray();
            $this->questionnaire->markAsCompleted();
            $this->questionnaire->save();
            $this->questionnaire->refresh();
            $this->questionnaire->refactor($this->question, $this->option ?? null);
        }

        return $answer;
    }

    public static function firstOrCreateByQuestionnaireIdAndQuestionId(int $questionnaireId, int $questionId)
    {
        return self::with('users')->firstOrCreate([
            'questionnaire_id' => $questionnaireId,
            'question_id' => $questionId,
        ]);
    }

    public static function whereQuestionnaireIdAndQuestionId(int $questionnaireId, int $questionId)
    {
        return self::where('questionnaire_id', $questionnaireId)->where('question_id', $questionId)->first();
    }

    public static function withCommentsWhereQuestionnaireIdAndQuestionId($questionnaireId, $questionId)
    {
        return self::with('questionnaire', 'question', 'comments')
            ->where('questionnaire_id', $questionnaireId)
            ->where('question_id', $questionId)
            ->first();
    }

    /*
    * This string will be used in notifications on what a new comment
    * was made.
    */
    public function commentableName(): string
    {
        return mb_strimwidth("{$this->question->ref} {$this->question->description}", 0, 30, '...');
    }

    /*
    * This URL will be used in notifications to let the user know
    * where the comment itself can be read.
    */
    public function commentUrl(): string
    {
        $questionnaireType = $this->questionnaire->type->slug;

        return route("tenant.questionnaires.{$questionnaireType}.show", [
            'questionnaire' => $this->questionnaire->id,
            'category' => $this->question->category,
        ]);
    }

    /**
     * Assign user to answer
     */
    public static function assignUserByQuestionIds($questionnaireId, $questionIds, $userIds, $assigner)
    {
        $answers = self::where('questionnaire_id', $questionnaireId)
            ->whereIn('question_id', $questionIds)
            ->get();

        $answers->map(function ($answer) use ($userIds, $assigner) {
            $answer->assignUsers($userIds ?? [], $assigner);
        });

        return true;
    }

    public static function getAnsweredQuestionsCountByQuestionnaireIdAndQuestionIds(
        int $questionnaireId,
        array $questionIds
    ) {
        return self::where('questionnaire_id', $questionnaireId)
            ->whereIn('question_id', $questionIds)
            ->where(function ($query) {
                $query->whereNotNull('value')
                    ->orWhere('not_applicable', true)
                    ->orWhere('not_reported', true);
            })
            ->count('id');
    }

    public static function getAnsweredQuestionsSumByQuestionnaireIdAndQuestionIds(
        int $questionnaireId,
        array $questionIds
    ) {
        return self::where('questionnaire_id', $questionnaireId)
            ->whereIn('question_id', $questionIds)
            ->count('id');
    }

    /**
     * Collect all intiatives, independently of the answer
     */
    public static function questionOptionByquestionId($questionSimpleId)
    {
        return self::from('question_option_simples')
            ->where('question_option_simples.id', $questionSimpleId)
            ->get()
            ->toArray();
    }
}
