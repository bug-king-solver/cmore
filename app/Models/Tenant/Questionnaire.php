<?php

namespace App\Models\Tenant;

use App\Jobs\CopyAnswers;
use App\Jobs\Tenants\CreateQuestionnaireJob;
use App\Jobs\Tenants\Questionnaires\Submit;
use App\Models\Enums\QuestionnaireType as EnumQuestionnaireType;
use App\Models\Enums\Questionnaires\QuestionnaireStatusEnum;
use App\Models\Tenant\Answer;
use App\Models\Tenant\Category;
use App\Models\Tenant\Concerns\Interfaces\Userable;
use App\Models\Tenant\Filters\AvailableUsersFilter;
use App\Models\Tenant\Filters\CompanyFilter;
use App\Models\Tenant\Filters\Company\DateFilter;
use App\Models\Tenant\Filters\DateBetweenFilter;
use App\Models\Tenant\Filters\InternalTagsFilter;
use App\Models\Tenant\Filters\QuestionnaireStatus;
use App\Models\Tenant\Filters\QuestionnaireTypeFilter;
use App\Models\Tenant\Filters\TagsFilter;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Question;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Tenant\ReportingPeriod;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Traits\Catalog\ProductItem;
use App\Models\Traits\Filters\IsSortable;
use App\Models\Traits\HasInternalTags;
use App\Models\Traits\HasTags;
use App\Models\Traits\HasTasks;
use App\Models\Traits\HasUsers;
use App\Models\Traits\QueryBuilderScopes;
use App\Models\Traits\ReportingPeriodsTrait;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Questionnaire extends Model implements Userable
{
    use LogsActivity;
    use HasFactory;
    use HasUsers;
    use HasTags;
    use HasInternalTags;
    use HasTasks;
    use QueryBuilderScopes;
    use HasFilters;
    use IsSearchable;
    use HasDataColumn;
    use ProductItem;
    use SoftDeletes;
    use IsSortable;
    use ReportingPeriodsTrait;

    /* @var array<string,mixed>|Collection $weightableQuestionsForFilter */
    public $weightableQuestionsForFilter;

    /* @var array<string,mixed>|Collection $allQuestionsForMaturity */
    public $allQuestionsForMaturity;


    protected $casts = [
        'is_ready' => 'bool',
        'welcomepage_enable' => 'bool',
        'from' => 'date',
        'to' => 'date',
        'countries' => 'array',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
        'result_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'categories' => 'array',
        'questions' => 'array',
        'initiatives' => 'array',
        'sdgs' => 'array',
        'data' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'questionnaire_type_id',
        'parent_id',
        'created_by_user_id',
        'company_id',
        'from',
        'to',
        'countries',
        'categories',
        'questions',
        'time_to_complete',
        'is_ready',
        'welcomepage_enable',
        'completed_at',
        'submitted_at',
        'result_at',
        'reporting_period_id',
    ];

    protected array $sortable = [
        'id' => 'Id',
        'progress' => 'Progress',
        'created_at' => 'Created at',
    ];

    /**
     * The attributes that should be cast.
     */
    public static function getCustomColumns(): array
    {
        return array_merge((new self())->getFillable(), [
            'id',
            'sdgs',
            'maturity',
            'progress',
            'initiatives',
            'last_question_id',
            'updated_by_user_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    public function getCasts()
    {
        return array_merge(parent::getCasts(), [
            'data' => 'array',
        ]);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'questionnaire_type_id' => $this->questionnaire_type_id,
            'from' => $this->from,
            'to' => $this->to,
            'countries' => $this->countries,
            'created_at' => $this->created_at,
        ];
    }

    protected array $searchable = [
        'countries', 'from', 'to',
    ];

    protected array $filters = [
        CompanyFilter::class,
        DateFilter::class,
        DateBetweenFilter::class,
        TagsFilter::class,
        InternalTagsFilter::class,
        AvailableUsersFilter::class,
        QuestionnaireTypeFilter::class,
        QuestionnaireStatus::class,
    ];

    protected static function booted()
    {
        static::creating(function ($questionnaire) {
            $questionnaire->welcomepage_enable = 1;
        });

        static::created(function ($questionnaire) {
            if ($questionnaire->type->getProduct()->is_payable ?? false) {
                tenant()->forceWithdrawFloat($questionnaire->type->getPriceProduct(), self::getMetaProduct($questionnaire));
            }

            if (isset($questionnaire->create_async) && $questionnaire->create_async) {
                CreateQuestionnaireJob::dispatchNow($questionnaire);
            } else {
                CreateQuestionnaireJob::dispatch($questionnaire);
            }
        });

        static::deleting(function ($questionnaire) {
            $questionnaire->data()->delete();
        });
    }

    public static function getMetaProduct($questionnaire)
    {
        $meta = $questionnaire->type->getMetaProduct();
        $meta['productable_id'] = $questionnaire->id;
        $meta['description'] =  preg_replace('/#\d+/', "#{$questionnaire->id}", $meta['description']);

        return $meta;
    }

    /**
     * Get the children questions.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the children questions.
     */
    public function childrenSubmitted()
    {
        return $this->children()->whereNotNull('submitted_at');
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list($user = null)
    {
        return self::OnlyOwnData($user);
    }

    /**
     * Get the user that created the questionnaire.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the last user that changed the questionnaire.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updatedBy_user_id');
    }

    /**
     * Get the company that owns the company.
     */
    public function type()
    {
        return $this->belongsTo(QuestionnaireType::class, 'questionnaire_type_id')
            ->withTrashed()
            ->withoutGlobalScope(EnabledScope::class);
    }

    /**
     * Get the company that owns the company.
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the answers for the questionnaire.
     */
    public function defaultCategories()
    {
        return $this->hasManyThrough(Category::class, QuestionnaireType::class);
    }

    /**
     * Get the answers for the questionnaire.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function finalAnswers()
    {
        return $this->answers()->whereNotNull('value');
    }

    /**
     * Get the comments for the questionnaire.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the attachments for the questionnaire.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get the reported data for the questionnaire.
     * @return HasMany
     */
    public function data(): HasMany
    {
        return $this->hasMany(Data::class);
    }

    /**
     * Alias for data()
     * @return HasMany
     */
    public function reportedData(): HasMany
    {
        return $this->data();
    }

    public function dataList()
    {
        return $this->hasMany(Data::class)
            ->with(['indicator', 'indicator.sources']);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logExcept(['categories', 'questions'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the questionnaire's reporting period.
     */
    public function reportingPeriods()
    {
        return $this->belongsTo(ReportingPeriod::class);
    }

    /**
     * Get the last responded question.
     */
    public function lastQuestion()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get answered questions
     */
    protected function answeredQuestions()
    {
        return $this->answers->filter(function ($answer) {
            return !is_null($answer->value) || $answer->not_applicable || $answer->not_reported;
        });
    }

    /**
     * Get answered questions
     *
     * @return int
     */
    public function answeredQuestionsCount(): int
    {
        return count($this->answeredQuestions());
    }

    public static function ongoing(): Builder
    {
        return self::OnlyOwnData()
            ->where('is_ready', true)
            ->whereNull('submitted_at')
            ->with('company', 'type')
            ->orderBy('created_at', 'DESC');
    }

    public static function submitted(): Builder
    {
        return self::OnlyOwnData()
            ->whereNotNull('submitted_at')
            ->with('company', 'type')
            ->orderBy('submitted_at', 'DESC');
    }

    public static function firstSubmitted()
    {
        return self::OnlyOwnData()
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'ASC')
            ->first();
    }

    public static function lastSubmitted()
    {
        return self::OnlyOwnData()
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'DESC')
            ->first();
    }

    public function review()
    {
        $this->data()->delete();
        $this->children()->delete();
        $this->submitted_at = null;
        $this->result_at = null;
        $this->save();
    }

    public function submit()
    {
        // Questionnaires that have maturity
        if ($this->type->has_score) {
            $countScore = $this->countMaturityScore();
            $this->categories = $countScore['allCategories'];
            $this->maturity = $countScore['maturity'];
        }

        $initiatives = Answer::questionnaireInitiatives($this);
        $sdgs = Answer::questionnaireSdgs($this);

        $this->initiatives = $initiatives;
        $this->sdgs = $sdgs;

        $this->submitted_at = Carbon::now();
        $this->time_to_complete = $this->submitted_at->diffInSeconds($this->created_at);

        $this->save();

        // Dispatch all the defined action by order
        $user = auth()->user() ?? User::find($this->created_by_user_id) ?? null;

        $batch = Bus::chain([
            function () use ($user) {
                $this->copyToData($user->id ?? null);
            },
            function () {
                $job = "App\Jobs\Tenants\Questionnaires\Submit{$this->questionnaire_type_id}";
                if (class_exists($job)) {
                    if (isset($this->create_async) && $this->create_async) {
                        $job::dispatch($this)->onConnection('sync');
                    } else {
                        $job::dispatch($this);
                    }
                }
            },
            function () {
                $questionnaire = self::find($this->id);

                if (isset($questionnaire->wait_result_job) && $questionnaire->wait_result_job) {
                    unset($questionnaire->wait_result_job);
                    $questionnaire->result_at = null;
                    $questionnaire->save();
                    return;
                }

                $this->result_at = Carbon::now();
                $this->save();
            },
        ]);

        $batch->onQueue('questionnaires')->dispatch();
    }

    /**
     * Get the answered questions of the questionnaire and Iterate over each answered question.
     * If there is data available according to the conditions, insert it into the Data Model
     * @param null|int $userId
     */
    public function copyToData(null|int $userId)
    {
        $answers = $this->answeredQuestions();
        $date = $this->submitted_at->format('Y-m-d H:i:s');
        $data = [];

        foreach ($answers as $answer) {

            $questionOptions = $answer->question->questionOptions;
            $questionOptionsCount = $questionOptions->count();

            $values = is_array($answer->parsedValue)
                ? $answer->parsedValue
                : [0 => $answer->value];

            if ($answer->question->answer_type === 'binary') {
                if ($questionOptions[0]->indicator_id !== null && $questionOptions[0]->indicator_id !== '') {
                    $data[] = [
                        'company_id' => $this->company_id,
                        'indicator_id' => $questionOptions[0]->indicator_id,
                        'questionnaire_id' => $this->id,
                        'value' => is_array($answer->parsedValue) ? $values[$questionOptions[0]->question_option_id] : $values[0],
                        'reported_at' => $date,
                        'created_at' => $date,
                        'updated_at' => $date,
                        'user_id' => $userId,
                        'reporting_period_id' => $this->reporting_period_id,
                        'data' => null
                    ];
                }
            } else {
                foreach ($questionOptions as $option) {
                    // Ignore if the option has no indicator associated
                    // Or the question option does not exist in the answer
                    if (
                        !$option->indicator_id
                        || ($questionOptionsCount > 1 && !isset($values[$option->question_option_id]))
                    ) {
                        continue;
                    }

                    if ($option->indicator_id !== null && $option->indicator_id !== '') {

                        $finalValue = null;
                        if (is_array($answer->parsedValue) && isset($values[$option->question_option_id])) {
                            $finalValue = $values[$option->question_option_id];
                        } else if (is_array($values) && isset($values[0])) {
                            $finalValue = $values[0];
                        } else if (is_array($values) && isset($values['currency'])) {
                            $finalValue = $values['value'];
                        } else {
                            $finalValue = $values;
                        }

                        if(is_array($finalValue)) {
                            $finalValue = $finalValue[array_key_first($finalValue)];
                        }

                        if($finalValue === null) {
                            continue;
                        }

                        $ghostEmission = $option['indicator']['emissions']['ghost_factors'] ?? null;
                        if ($ghostEmission) {
                            $ghostEmissionValue = 0;

                            foreach ($ghostEmission as $key => $value) {
                                $ghostEmissionValue += ($value * (float)$finalValue);
                            }

                            $ghostEmission = json_encode([
                                'ghost_emission' => $ghostEmissionValue,
                                'ghost_factors' => $ghostEmission
                            ]);
                        } else {
                            $ghostEmission = json_encode([
                                'ghost_emission' => null,
                                'ghost_factors' => null
                            ]);
                        }

                        $data[] = [
                            'company_id' => $this->company_id,
                            'indicator_id' => $option->indicator_id,
                            'questionnaire_id' => $this->id,
                            'value' => $finalValue,
                            'reported_at' => $date,
                            'created_at' => $date,
                            'updated_at' => $date,
                            'user_id' => $userId,
                            'reporting_period_id' => $this->reporting_period_id,
                            'data' => $ghostEmission,
                        ];
                    }
                }
            }
        }

        if ($data) {
            Data::insert($data);
            $this->insertCalcIndicators();
        }
    }

    /**
     * Resursive function that search for all indicator from our answers
     * and insert them into the Data Model
     * @param int $iterations - Number of iterations
     * @param int $maxIterations - Max number of iterations ( to avoid infinite loop )
     */
    public function insertCalcIndicators($iterations = 0, $maxIterations = 4)
    {
        $submittedDate = $this->submitted_at->format('Y-m-d H:i:s');

        // fetch all the data of this questionnaire
        $insertedDatas = $this->data()->get();

        // search for indicators in the calc of the indicators
        $calcIndicators = $this->searchIndicators($insertedDatas->pluck('indicator_id')->toArray());

        if (empty($calcIndicators)) {
            return true;
        }

        $indicators = Indicator::whereIn('id', $calcIndicators)
            ->withoutGlobalScope(EnabledScope::class)
            ->get();

        $unusedIndicators = [];
        $newData = [];

        // Loop the indicators
        foreach ($indicators as $indicator) {

            $reportedData = [];
            // get the calc math of the indicator
            $arr = getStringsBetweenDollars($indicator->calc);

            // get the value of the indicators that are in the calc
            foreach ($arr as $indicatorId) {
                $reportedData[] = $insertedDatas->where('indicator_id', (int)$indicatorId)->first() ?? null;
            }

            // Dont report the indicator if there is no data for the indicators in the calc
            if (count(array_filter($reportedData)) == 0) {
                $unusedIndicators[] = $indicator->id;
                continue;
            }

            $result = evalmath(
                replaceIndicatorWithValueInCalc(
                    collect($reportedData)->pluck('value', 'indicator_id'),
                    $indicator->calc
                )
            );

            if ($result == null) {
                continue;
            }

            $newData[] = [
                'company_id' => $this->company_id,
                'indicator_id' => $indicator->id,
                'questionnaire_id' => $this->id,
                'value' => $result,
                'reporting_period_id' => $this->reporting_period_id,
                'reported_at' => $submittedDate,
                'created_at' => $submittedDate,
                'updated_at' => $submittedDate,
            ];
        }

        if (!empty($newData)) {
            // remove from newData all the indicators that are already in the database
            // bulk action
            $createData = array_filter(
                $newData,
                function ($newData) use ($insertedDatas) {
                    return !$insertedDatas->where('indicator_id', $newData['indicator_id'])->first();
                }
            );

            // update the indicators that are already in the database
            $updateData = array_filter(
                $newData,
                function ($newData) use ($insertedDatas) {
                    return $insertedDatas->where('indicator_id', $newData['indicator_id'])->first();
                }
            );

            if (!empty($createData)) {
                Data::insert($createData);
            }

            if (!empty($updateData)) {
                foreach ($updateData as $data) {
                    $dataToUpdate = $insertedDatas->where('indicator_id', $data['indicator_id'])->first();
                    $dataToUpdate->value = $data['value'];
                    $dataToUpdate->save();
                }
            }

            if (!empty($unusedIndicators)) {
                $iterations = $iterations + 1;
                if ($iterations >= $maxIterations) {
                    return true;
                }
                return $this->insertCalcIndicators($iterations, $maxIterations);
            }
        }

        return true;
    }

    /**
     * Will search  for indicators in the calc of the indicators
     * This is a recursive function , that will search for indicators in the calc of the indicators
     * @param array $indicatorsId - Indicator from the indicators already reported
     * @param array $result
     */
    public function searchIndicators($indicatorsId, $result = [])
    {
        $indicators = [];
        foreach ($indicatorsId as $indicator) {
            $indicators = array_merge(
                $indicators,
                Indicator::where('calc', 'like', "%$$indicator$%")
                    ->where('calc', '!=', '')
                    ->whereNotIn('id', $result)
                    ->withoutGlobalScope(EnabledScope::class)
                    ->pluck('id')
                    ->toArray()
            );
        }
        // remove duplicates
        $indicators = array_unique($indicators);

        if (!empty($indicators)) {
            $result = array_merge($result, $indicators);
            $result = array_unique($result);
            // recursive call
            return $this->searchIndicators($indicators, $result);
        }
        // return all the indicators found
        return $result;
    }

    /**
     * Check if the questionnaire has the result ready
     */
    public function hasResult()
    {
        return $this->result_at
            ? true
            : false;
    }

    /**
     * Check if the questionnaire is submitted
     */
    public function isSubmitted()
    {
        return $this->submitted_at
            ? true
            : false;
    }

    /**
     * Check if the questionnaire is completed
     */
    public function isCompleted()
    {
        return $this->completed_at
            ? true
            : false;
    }

    /**
     * Load the categories for the questionnaire
     */
    public function categories()
    {
        return Category::loadForQuestionnaire($this->categories);
    }

    public function categoriesMainList()
    {
        $mainCategories = array_filter($this->categories, fn ($category) => !($category['parent_id'] ?? false));

        return Category::loadForQuestionnaire($mainCategories);
    }

    public function categoriesList()
    {
        return Category::listForQuestionnaire($this->categories);
    }

    public static function answerRelationship($id)
    {
        return function ($query) use ($id) {
            $query->where('questionnaire_id', $id);
        };
    }

    public function questions($categoryId = null)
    {
        $questionnaireId = $this->id;
        $questQuestions = $this->questions;
        $questQuestionsIds = array_column($questQuestions, 'id');

        $refHistory = [];
        $previousLevels = [];
        $questions = Question::withTrashed()
            ->whereHas('answer', function ($q) use ($questionnaireId) {
                $q->where('questionnaire_id', $questionnaireId);
            })
            ->with([
                'parent.parent',
                'questionOptions' => function ($query) {
                    $query->where(
                        function ($query) {
                            $query
                                ->whereNull('deleted_at')
                                ->orWhere('deleted_at', '>=', $this->created_at);
                        }
                    )
                        ->where('created_at', '<', $this->created_at);
                },
                'internalTags',
                'category',
                'source',
                'usersCanValidateAnswer' => function ($query) use ($questionnaireId) {
                    $query->where('questionnaire_id', $questionnaireId);
                },
                'answer' => function ($query) use ($questionnaireId) {
                    $query->where('questionnaire_id', $questionnaireId);
                }
            ])
            ->withCount(
                [
                    'comments' => function ($query) use ($questionnaireId) {
                        $query->where('questionnaire_id', $questionnaireId);
                    },
                    'attachments' => function ($query) use ($questionnaireId) {
                        $query->where('questionnaire_id', $questionnaireId);
                    },
                    'users' => function ($query) use ($questionnaireId) {
                        $query->where('questionnaire_id', $questionnaireId);
                    },
                ]
            )
            ->whereIn('id', $questQuestionsIds);

        if ($categoryId != null && is_int($categoryId)) {
            $questions = $questions->where('category_id', $categoryId);
        } else if (is_object($this->current_category)) {
            $questions = $questions->where('category_id', $this->current_category->id);
        } elseif (is_array($this->current_category)) {
            $questions = $questions->whereIn('category_id', array_column($this->current_category, 'id'));
        } elseif (is_int($this->current_category)) {
            $questions = $questions->where('category_id', $this->current_category);
        }

        $questions = $questions->get()
            ->sortBy('ref', SORT_NATURAL)
            ->transform(
                function ($question) use ($questQuestions, $questQuestionsIds, &$refHistory, &$previousLevels) {
                    // Calc the ref starting in 1
                    $levels = explode('.', $question->ref);
                    array_pop($levels); // The last one is empty

                    $ref = [];
                    foreach ($levels as $index => $level) {
                        if (!isset($previousLevels[$index])) {
                            $ref[$index] = 1;
                        } elseif ($level == $previousLevels[$index]) {
                            $ref[$index] = $refHistory[$index];
                        } else {
                            $ref[$index] = $refHistory[$index] + 1;
                        }
                    }

                    $refHistory = array_merge($ref, $refHistory);
                    $previousLevels = $levels;
                    // End of ref calc

                    $key = array_search($question['id'], $questQuestionsIds, false);

                    $questQuestion = $questQuestions[$key];

                    $question['questionnaire_id'] = $this->id;
                    $question['enabled'] = $questQuestion['enabled'];
                    $question['ref2'] = implode('.', $ref) . '.';

                    return $question;
                }
            );

        return $questions;
    }

    /**
     * Get enabled questions
     */
    protected function questionsEnabled(): array
    {
        return array_filter($this->questions ?? [], fn ($question) => $question['enabled']);
    }

    /**
     * Get total of enabled questions
     */
    protected function questionsEnabledCount(): int
    {
        return count($this->questionsEnabled());
    }

    /**
     * Weightable question
     * We defined the rule in a meeting with Carina 14/11/2023
     * Rule: Visible questions + Visible questions where parent id is enabled and has weight
     */
    public function weightableQuestions()
    {
        if (!isset($this->allQuestionsForMaturity)) {
            $this->allQuestionsForMaturity = $this->questionsForMaturity();
        }

        $questionsid = collect($this->questions)
            ->keyBy('id')
            ->filter($this->weightableQuestionsFilter($this->allQuestionsForMaturity));

        return $this->allQuestionsForMaturity->whereIn('id', $questionsid->keys());
    }

    public function weightableQuestionsFilter($allQuestions)
    {
        return function ($question) use ($allQuestions) {
            if ($question['enabled']) {
                return (float) $question['weight'] > 0;
            } else {
                $mustCount = true;

                // It must count, except if one of the parents questions (in the full hierarchy) does not have weight. It must include the disabled questions.
                // We defined this rule in a meeting with Carina 21/12/2023
                $parent = $allQuestions->where('id', $question['parent_id'])->first();

                while ($parent) {
                    $mustCount = (bool) $parent['weight'] && $question['weight'];

                    // The question must not count, if its parent does not counts.
                    // The question must count, if its parent is enabled and counts.
                    if (!$mustCount || ($parent['enabled'] && $mustCount)) {
                        break;
                    }

                    $parent = $allQuestions->where('id', $parent['parent_id'])->first();
                }
                return $mustCount && $question['weight'];
            }
        };
    }

    /**
     * Calculate pontuation based on the weightable questions.
     * @param closure $filterCallback
     * @param null $tag
     * @return float|int
     */
    public function calculatePontuation($filterCallback): float|int
    {
        if (!isset($this->weightableQuestionsForFilter)) {
            $filteredWeightableQuestions = $this->weightableQuestions()->filter($filterCallback);
        } else {
            $filteredWeightableQuestions = $this->weightableQuestionsForFilter->filter($filterCallback);
        }

        return calculatePercentage(
            Answer::where('questionnaire_id', $this->id)
                ->whereIn('question_id', $filteredWeightableQuestions->pluck('id'))
                ->sum('weight'),
            $filteredWeightableQuestions->sum('weight'),
            2
        );
    }

    public function menu()
    {
        $ordered = $this->categories()->sortBy('order')->values();

        $mainCategories = $ordered->whereNull('parent_id')->values();
        $subCategories = $ordered->whereNotNull('parent_id')->values();

        $ids = $ordered->pluck('id')->values()->toArray();
        $currentOrderedIndex = array_search($this->current_category->id ?? null, $ids, false);
        $previous = $ordered[$currentOrderedIndex - 1] ?? null;
        $next = $ordered[$currentOrderedIndex + 1] ?? null;

        return [
            'main_categories' => $mainCategories,
            'children_categories' => $subCategories,
            'previous' => $previous,
            'next' => $next,
        ];
    }

    /**
     * Run all refactor steps
     */
    public function refactor(Question $question, ?QuestionOption $option)
    {
        $this->refactorQuestions($question, $option);
        $this->calcProgress();
        $this->markAsCompleted();
        $this->save();
    }

    /**
     * Refactor questions
     *
     * This piece of code must enable/disable questions and their children questions
     */
    protected function refactorQuestions(Question $parentQuestion, ?QuestionOption $option)
    {
        $questions = collect($this->questions);

        if ($option) {
            // When enabling, get only the first level of subquestions, because the next levels
            // will be enabled when user answer to this level of questions
            // When disabling, get all subquestions recursively, because we want to delete all children questions
            $depth = $option->children_action === 'enable'
                ? 1
                : 0;
            $subQuestionsIds = Question::childrenIdsRecursive($depth, $parentQuestion->id);

            if ($option->children_action === 'disable') {
                $this->removeAnswersByQuestionIds($subQuestionsIds);
            }
        }

        // Enable/Disable subquestions
        if ($option && isset($subQuestionsIds) && $subQuestionsIds) {
            $questions->transform(function ($question) use ($option, $subQuestionsIds) {
                if (in_array($question['id'], $subQuestionsIds, false)) {
                    $value = $option->children_action === 'enable'
                        ? true
                        : false;

                    if ($question['enabled'] !== $value) {
                        $question['enabled'] = $value;
                    }
                }

                return $question;
            });
        }

        $this->questions = array_values($questions->toArray());

        if ($this->hasCategories()) {
            $this->refactorCategory($parentQuestion->category_id);
        }
    }

    /**
     * Refactor categories
     *
     * Count questions, question weights, answered questions, etc.
     */
    protected function refactorCategory(int $categoryId)
    {
        $categories = collect($this->categories)->keyBy('id');

        // Category to change
        $category = $categories->where('id', $categoryId)->first();

        if (!$category) {
            return false;
        }

        $allQuestions = collect($this->questions())
            ->where('category_id', $categoryId)
            ->keyBy('id');

        $enabledQuestions = $allQuestions->where('enabled', true);

        // TO DO :: Check the needed changes to use the weightableQuestions method
        $weightableQuestions = $allQuestions->filter($this->weightableQuestionsFilter($allQuestions));

        $category['questions_count'] = $enabledQuestions->count();
        $category['questions_sum_weight'] = $weightableQuestions->sum('weight');
        $category['questions_answered'] = Answer::getAnsweredQuestionsCountByQuestionnaireIdAndQuestionIds(
            $this->id,
            $enabledQuestions->pluck('id')->toArray()
        );
        $category['weight'] = Answer::getAnsweredQuestionsSumByQuestionnaireIdAndQuestionIds(
            $this->id,
            $weightableQuestions->pluck('id')->toArray()
        );
        $category['progress'] = $this->calcProgressByCategory($category);
        $category['maturity'] = $this->calcMaturityByCategory($category); // Maturity

        $categories[$category['id']] = $category;

        $this->categories = array_values($categories->toArray());

        $this->refactorParentCategory($category['parent_id']);

        return $category;
    }

    protected function refactorParentCategory($categoryId)
    {
        // All categories
        $categories = collect($this->categories)->keyBy('id');

        // Category to change
        $category = $categories->where('id', $categoryId)->first();

        if (!$category) {
            return false;
        }

        $subCategories = $categories->where('parent_id', $categoryId);

        $category['questions_count'] = $subCategories->sum('questions_count');
        $category['questions_sum_weight'] = $subCategories->sum('questions_sum_weight');
        $category['questions_answered'] = $subCategories->sum('questions_answered');
        $category['weight'] = $subCategories->sum('weight');
        $category['progress'] = $this->calcProgressByCategory($category);
        $category['maturity'] = $this->calcMaturityByCategory($category); // Maturity

        $categories[$category['id']] = $category;

        $this->categories = array_values($categories->toArray());

        if ($category['parent_id']) {
            return $this->refactorParentCategory($category['parent_id']);
        }

        return $category;
    }

    /**
     * Check if the questionnaire has categories
     */
    public function hasCategories()
    {
        return (bool) $this->categories;
    }

    protected function removeAnswersByQuestionIds(array $ids)
    {
        return Answer::removeAnswersByQuestionnaireIdByQuestionIds($this->id, $ids);
    }

    /**
     * Mark a questionnaire as completed
     */
    public function markAsCompleted(): void
    {
        $incompleted = array_filter($this->categories, fn ($category) => $category['progress'] < 100);

        $this->completed_at = $incompleted || (count($this->categories) == 0 && $this->progress < 100)
            ? null
            : Carbon::now();
    }

    /**
     * Calc global progress for a questionnaire
     */
    public function calcProgress()
    {
        $progress = floor($this->answeredQuestionsCount() * 100 / $this->questionsEnabledCount());

        if ($progress < 0) {
            $progress = 0;
        } elseif ($progress > 100) {
            $progress = 100;
        }

        $this->progress = $progress;
    }

    protected function calcProgressByCategory($category)
    {
        $questionsCount = $category['questions_count'] ?? 0;
        $questionsAnswered = $category['questions_answered'] ?? 0;

        $progress = $questionsCount
            ? $questionsAnswered * 100 / $questionsCount
            : 0;

        // TODO :: Check the reason for sometimes the progress become greater than 100. May be a round issue
        return $progress < 100
            ? $progress
            : 100;
    }

    protected function calcMaturityByCategory($category)
    {
        $total = $category['questions_sum_weight'] ?? 0;
        $weight = $category['weight'] ?? 0;
        if ($total > 0) {
            return $weight * 100 / $total;
        }
        return 0;
    }

    protected function calcFinalMaturityByCategory($category)
    {
        return $category['maturity'] / 100 * $category['ponderation'];
    }

    /**
     * Create an array with the current categories ids (to the case they be changed in the future)
     */
    public static function copyCategories(int $questionnaireTypeId, int $businessSectorId)
    {
        $categories = QuestionnaireType::copyDefaultCategories($questionnaireTypeId);

        // Count total questions by category
        $categories->transform(
            function ($category) use ($questionnaireTypeId, $businessSectorId) {
                $category->questions_answered = 0;
                $category->weight = 0;
                $category->progress = 0;
                $category->ponderation = Category::ponderation($questionnaireTypeId, $category->id, $businessSectorId);
                $category->maturity = 0;
                $category->maturity_final = 0;

                return $category;
            }
        );

        // Count total questions for parent categories
        $categories->map(function ($category) use (&$categories) {
            $parent = $categories->firstWhere('id', $category->parent_id);

            while ($parent) {
                $parent->questions_count += $category->questions_count;
                $parent = $categories->firstWhere('id', $parent->parent_id);
            }
        });

        return $categories;
    }

    /**
     * Create an array with the current questions ids (to the case they be changed in the future)
     */
    public static function copyQuestions($questionnaire)
    {
        $company = $questionnaire->company;
        $businessSectorIds = $company->businessSectorsAllIdsArray();
        $size = $company->size ?? null;
        $questionnaireTypeId = $questionnaire->questionnaire_type_id;

        return Question::copy($questionnaireTypeId)->transform(
            function ($question) use ($businessSectorIds, $size) {

                if (($question->business_sector_only && !array_intersect_key($businessSectorIds, array_flip($question->business_sector_only)))
                    || ($question->business_sector_except && array_intersect_key($businessSectorIds, array_flip($question->business_sector_except)))
                    || ($question->size_only && !in_array($size, $question->size_only, false))
                    || ($question->size_except && in_array($size, $question->size_except, false))
                ) {
                    return null;
                }

                return [
                    'id' => $question->id,
                    'parent_id' => $question->parent_id,
                    'category_id' => $question->category_id,
                    'weight' => $question->weight,
                    // Only first level questions are enabled by default
                    'enabled' => $question->parent_id ? false : true,
                ];
            }
        )->filter()->values();
    }

    /**
     * Get all questionnaire by type id
     */
    public static function filterQuestionnaireList($type, $from)
    {
        return self::where('questionnaire_type_id', $type)
            ->whereNotNull('submitted_at')
            ->whereYear('from', $from)
            ->with('company')
            ->orderBy('submitted_at', 'ASC')
            ->get();
    }

    /**
     * Create the assigned user message
     */
    public function assignedUserMessage($assigner)
    {
        $params = [
            'questionnaire' => $this->id,
        ];

        $actionUrl = tenant()->route('tenant.questionnaires.show', $params);

        return [
            'userName' => $assigner->name,
            'period' => "{$this->from->format('Y-m-d')}/{$this->to->format('Y-m-d')}",
            'company' => $this->company->name,
            'message' => __(':userName assigned you a questionnaire for ":company" for the period ":period".'),
            'action' => $actionUrl,
        ];
    }

    /**
     * Create the assigned user message
     */
    public function updatedUserMessage()
    {
        $params = [
            'questionnaire' => $this->id,
        ];

        // URL for the specific questionnaire
        $actionUrl = route('tenant.questionnaires.show', $params);

        return [
            'period' => "{$this->from->format('Y-m-d')}/{$this->to->format('Y-m-d')}",
            'company' => $this->company->name,
            'message' => __('The questionnaire for ":company" for the period ":period" has been updated.'),
            'action' => $actionUrl,
        ];
    }

    public static function questionnaireListByQuestionId($ids)
    {
        return self::whereIn('id', $ids)
            ->with('company')
            ->get();
    }

    /**
     * Get the taxonomy data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function taxonomy(): HasOne
    {
        return $this->hasOne(Taxonomy::class);
    }

    /**
     * Get the physical risks data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function physicalRisks(): HasMany
    {
        return $this->hasMany(PhysicalRisks::class);
    }

    /**
     * Redirects to the appropriate page of the questionnaire
     *
     * @return string
     */
    public function questionnaireWelcome()
    {
        $redirect = route('tenant.questionnaires.show', ['questionnaire' => $this->id]);
        $route = $this->type->routes['welcome'] ?? null;
        if (isset($route) && Route::has('tenant.' . $route)) {
            return route('tenant.' . $route, ['questionnaire' => $this->id]);
        }
        return $redirect;
    }

    /**
     * Redirects to the appropriate page of the questionnaire
     *
     * @return string
     */
    public function questionnaireScreen($page = 'show')
    {
        $redirect = route('tenant.questionnaires.show', ['questionnaire' => $this->id]);
        $route = $this->type->routes[$page] ?? null;
        if (isset($route) && Route::has('tenant.' . $route)) {
            return route('tenant.' . $route, ['questionnaire' => $this->id]);
        }
        return $redirect;
    }

    /**
     * Redirects to the appropriate page of the questionnaire
     *
     * @return string
     */
    public function questionnaireReport()
    {
        $redirect = route('tenant.dashboard', ['questionnaire' => $this->id]);
        $route = $this->type->routes['report'] ?? null;
        if (isset($route) && Route::has('tenant.' . $route)) {
            return route('tenant.' . $route, ['questionnaire' => $this->id]);
        }
        return $redirect;
    }

    /**
     * Redirects to the appropriate page of the questionnaire
     *
     * @return string
     */
    public function reviewModal()
    {
        $modal = $this->type->modals['review'] ?? null;
        if (isset($modal)) {
            return $modal;
        }
        return 'questionnaires.modals.review';
    }

    /**
     * Check if the questionnaire ahs the progress bar
     */
    public function hasProgress(): bool
    {
        return $this->type->progress ?? true;
    }

    /**
     * Check if the questionnaire has a submit route
     */
    public function hasSubmitRoute(): bool
    {
        if (!isset($this->type->modals['submit'])) {
            return true;
        }

        return $this->type->modals['submit'] !== null
            ? true
            : false;
    }

    /**
     * Replicate Questionnaire
     */
    public function replicateQuestionnaire(): Questionnaire
    {
        // fetch all questinnaire table column
        $questionnaireAllColumns = Schema::getColumnListing('questionnaires');
        $questionnaireCastColumns = $this->getCasts();
        $oldQuestionnaire = $this->toArray();

        unset($oldQuestionnaire['id']); // remove the ID column

        $data = [];
        foreach ($oldQuestionnaire as $questionnaireColumn => &$questionnaireValue) {
            if (!in_array($questionnaireColumn, $questionnaireAllColumns)) {
                $data[$questionnaireColumn] = $questionnaireValue;
                unset($oldQuestionnaire[$questionnaireColumn]);
                continue;
            }

            $isDateTimeColumn = $questionnaireCastColumns[$questionnaireColumn] ?? false;
            if ($isDateTimeColumn == 'date' || $isDateTimeColumn == 'datetime') {
                $questionnaireValue = carbon()->parse($questionnaireValue)->format('Y-m-d H:i:s');
                continue;
            }

            $questionnaireValue = is_array($questionnaireValue)
                ? json_encode($questionnaireValue)
                : $questionnaireValue;
        }

        $oldQuestionnaire['completed_at'] = null;
        $oldQuestionnaire['submitted_at'] = null;
        $oldQuestionnaire['is_ready'] = false;
        $oldQuestionnaire['deleted_at'] = null;

        $oldQuestionnaire['result_at'] = null;

        $oldQuestionnaire['data'] = json_encode($data ?? []);

        // Creating with db to not fire jobs and observers
        $id = DB::table('questionnaires')->insertGetId($oldQuestionnaire);

        // Assign the users to the new questionnaire
        $assignedUsers = $this->users->pluck('id')->toArray() ?? [];
        if (auth()->check()) {
            $assignedUsers[] = auth()->user()->id;
        }

        $newQuestionnaire = self::find($id);
        $newQuestionnaire->assignUsers($assignedUsers, User::find($newQuestionnaire->created_by_user_id));

        // Copy the answers
        CopyAnswers::dispatch($newQuestionnaire, $this)
            ->delay(now()->addSeconds(1));

        return $newQuestionnaire;
    }


    public function countMaturityScore()
    {
        $allCategories = $this->categories;

        $maturity = 0;

        foreach ($allCategories as $i => &$category) {
            $category['weight'] = Answer::weightByQuestionnaireByCategory($this->id, $category['id']);
            $category['maturity'] = $this->calcMaturityByCategory($category);
        }

        $mainCategories = array_filter($allCategories, fn ($category) => !($category['parent_id'] ?? false));

        foreach ($allCategories as &$mainCategory) {

            if ($mainCategory['parent_id']) {
                continue;
            }

            $categories = array_filter(
                $allCategories,
                function ($category) use ($mainCategory) {
                    return $category['parent_id'] == $mainCategory['id'] && $category['questions_sum_weight'] > 0;
                }
            );

            $countCategories = count($categories);

            $mainCategory['maturity'] = !$countCategories
                ? 0
                : array_sum(array_column($categories, 'maturity')) / $countCategories;

            $mainCategory['maturity_final'] = round($mainCategory['maturity'], 2);

            if ($this->type->hasPonderation()) {
                $mainCategory['maturity_final'] = round($mainCategory['maturity'] * ($mainCategory['ponderation'] / 100), 2);
            }

            $maturity += $mainCategory['maturity_final'];
        }

        if ($this->type->hasPonderation()) {
            $totalMaturity = $maturity;
        } else {
            $totalMaturity = count($mainCategories) > 0 ? round($maturity / count($mainCategories), 2) : 0;
        }

        return [
            'allCategories' => $allCategories,
            'maturity' => $totalMaturity // questionnaire maturity
        ];
    }

    function buildTree($questions)
    {
        $grouped = [];
        $anwers = $this->answers;

        foreach ($questions as $question) {
            $question['parent_id'] = !$question['parent_id'] ? 0 : $question['parent_id'];
            $question['answer'] = $anwers->where('question_id', $question['id'])->first()->toArray();
            $grouped[$question['parent_id']][] = $question;
        }

        $fnTree = function ($subQuestions) use (&$fnTree, $grouped) {
            foreach ($subQuestions as $k => $sibling) {
                $id = $sibling['id'];
                if (isset($grouped[$id])) {
                    $sibling['children'] = $fnTree($grouped[$id]);
                }
                $subQuestions[$k] = $sibling;
            }
            return $subQuestions;
        };

        return $fnTree($grouped[0]);
    }

    public function getSumOfWeights($questions, &$questionWeight = 0, &$answerWeight = 0, $isChild = false)
    {
        foreach ($questions as $question) {
            $question['parent_id'] = $question['parent_id'] == 0 ? null : $question['parent_id'];
            if (isset($question['answer']) && $question['answer']['value'] == 'yes') {
                $questionWeight = $questionWeight + $question['weight'];
                $answerWeight = $answerWeight + $question['answer']['weight'];
                if (isset($question['children']) && !empty($question['children'])) {
                    $this->getSumOfWeights($question['children'], $questionWeight, $answerWeight, true);
                }
            } else if (!$question['parent_id']) {
                $questionWeight = $questionWeight + $question['weight'];
            } else if ($isChild) {
                $questionWeight = $questionWeight + $question['weight'];
            }
        }
        return [
            'questionWeight' => $questionWeight,
            'answerWeight' => $answerWeight
        ];
    }

    /**
     * Get the product from the questionnaire type
     */
    public function getProductsFromType($typeId)
    {
        return QuestionnaireType::find($this->type)->getProduct();
    }

    /**
     * Get all categories with their childrens and subchildrens
     * @return array<mixed>
     */
    public function getCategoriesRecursive(): array
    {
        $questions = $this->questions();

        $categories  = Category::where('model_id', $this->questionnaire_type_id)
            ->where('model_type', QuestionnaireType::class)
            ->get();

        $this->categories = $categories->map(function ($category) use ($questions) {
            return [
                ...$category->toArray(),
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'parent_id' => $category->parent_id,
                'active' => false,
                'questions' => $questions->where('category_id', $category->id),
                'childrens' => [],
            ];
        });

        return collect($this->categories)->map(function ($category) use ($questions) {
            $category['questions'] = $questions->where('category_id', $category['id']);
            $category['childrens'] = $this->parseChildsFromCategories($category);
            return $category;
        })->filter(function ($category) {
            return !$category['parent_id'];
        })->toArray();
    }

    /**
     * Recursive function to parse childs from categories.
     * If a category has childs, it will be added to the category as childrens.
     * @param array $category
     * @return array
     */
    public function parseChildsFromCategories($category)
    {
        return collect($this->categories)->where('parent_id', $category['id'])
            ->map(function ($child, $key) {
                $child['childrens'] = $this->parseChildsFromCategories($child);
                return $child;
            })->values()->toArray();
    }

    /**
     * Check if wallet is enabled
     * @return Attribute
     */
    public function questionnaireStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->submitted_at !== null) {
                    return QuestionnaireStatusEnum::SUBMITTED;
                }
                return QuestionnaireStatusEnum::ONGOING;
            }
        );
    }

    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    /**
     * Get the questions for the maturity
     * @param array $tagsSlug
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function questionsForMaturity(array $tagsSlug = []): \Illuminate\Database\Eloquent\Collection
    {
        $questQuestions = $this->questions;
        $questQuestionsIds = array_column($questQuestions, 'id');
        $questionnaireId = $this->id;

        return Question::withTrashed()
            ->whereHas('answer', function ($q) use ($questionnaireId) {
                $q->where('questionnaire_id', $questionnaireId);
            })
            ->with('parent.parent')
            ->with('internalTags')
            ->with(['questionOptions' => function ($query) {
                $query->where(
                    function ($query) {
                        $query->whereNull('deleted_at')
                            ->orWhere('deleted_at', '>=', $this->created_at);
                    }
                )->where('created_at', '<', $this->created_at);
            }])
            ->whereIn('id', $questQuestionsIds)
            ->where("questionnaire_type_id", $this->questionnaire_type_id)
            ->get()
            ->transform(
                function ($question) use ($questQuestions, $questQuestionsIds, $questionnaireId) {
                    $key = array_search($question['id'], $questQuestionsIds, false);
                    $questQuestion = $questQuestions[$key];
                    $question['questionnaire_id'] = $questionnaireId;
                    $question['enabled'] = $questQuestion['enabled'];
                    return $question;
                }
            );
    }
}
