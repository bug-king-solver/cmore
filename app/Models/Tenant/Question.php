<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Answer;
use App\Models\Tenant\QuestionOption;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use App\Models\Tenant\Source;
use App\Models\Traits\HasInternalTags;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Comments\Support\Config as SpatieConfig;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Question extends Model
{
    use HasTranslations;
    use HasFactory;
    use HasDataColumn;
    use SoftDeletes;
    use HasInternalTags;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new EnabledScope());
        static::addGlobalScope(new InstancesEnableScope());
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'weight' => 'float',
    ];

    /**
     * Translatable columns
     */
    public $translatable = ['description', 'information'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'enabled',
        'questionnaire_type_id',
        'category_id',
        'indicator_id',
        'parent_id',
        'source_id',
        'source_ref',
        'order',
        'weight',
        'answer_type',
        'description',
        'information',
        'allow_not_applicable',
        'allow_not_reportable',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'enabled',
            'questionnaire_type_id',
            'category_id',
            'indicator_id',
            'parent_id',
            'source_id',
            'source_ref',
            'order',
            'weight',
            'answer_type',
            'description',
            'information',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    public function getCasts()
    {
        return array_merge(parent::getCasts(), [
            array_fill_keys($this->getTranslatableAttributes(), 'array'),
            static::getDataColumn() => 'array',
        ]);
    }

    /**
     * Get the children questions.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * To enable questions, we just one first level from the specific question
     *
     * Example 1: To enable questions: we get only the first level of subquestions, because
     *            the next levels of subquestions will be enabled when user answer to a specific question
     *
     * Example 2: To disable questions: we get all subquestions recursively,
     *            because we want to delete all children questions
     */
    public static function childrenIdsRecursive($depth, $questionId, $ids = [])
    {
        $subQuestions = self::withTrashed()->select('id')->where('parent_id', $questionId)->get();

        foreach ($subQuestions as $question) {
            $ids[] = $question->id;

            if ($depth === 0) {
                $ids = array_merge(self::childrenIdsRecursive(0, $question->id, $ids), $ids);
            }
        }

        return array_unique($ids);
    }

    /**
     * Get the parent question.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the category that owns the question.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the category that owns the question.
     */
    public function questionnaire()
    {
        return $this->belongsTo(QuestionnaireType::class, 'questionnaire_type_id');
    }

    /**
     * Get the indicator that owns the question.
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    /**
     * Get the source that owns the question.
     */
    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * The options that belong to the question.
     */
    public function questionOptions()
    {
        return $this->hasMany(QuestionOption::class)
            ->withTrashed()
            ->with('option', 'indicator.internalTags')
            ->orderBy('order');
    }

    /**
     * Get the answer associated with the question.
     *
     * NOTE: When use it, be sure to identify the questionnaire.
     *
     * Example: ->with('answer', self::answerRelationship($this->id))
     */
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    /**
     * Get comments through answers.
     */
    public function comments()
    {
        return $this->hasManyThrough(
            SpatieConfig::getCommentModelName(),
            Answer::class,
            'question_id',
            'commentable_id'
        )->where('commentable_type', Answer::class);
    }

    /**
     * Get comments through answers.
     */
    public function users()
    {
        return $this->hasManyThrough(
            Userable::class,
            Answer::class,
            'question_id',
            'userable_id'
        )->where('userable_type', Answer::class);
    }

    /**
     * Get the users who are able to validate the answer.
     */
    public function usersCanValidateAnswer()
    {
        return $this->hasManyThrough(
            Userable::class,
            Answer::class,
            'question_id',
            'userable_id'
        )->where('userable_type', Answer::class)
          ->whereNotNull('assignment_type');
    }

    /**
     * Get attachments through answers.
     */
    public function attachments()
    {
        return $this->hasManyThrough(Attachable::class, Answer::class, 'question_id', 'attachable_id')
            ->where('attachable_type', Answer::class);
    }

    /**
     * Set the user's first name.
     *
     * TODO::Change this to a mutator to prevent multiple queries when accessing it
     *
     * @param  string  $value
     * @return void
     */
    public function getRefAttribute($value)
    {
        $ref = "{$this->order}.";
        $parent = $this->parent;

        while ($parent) {
            $ref = "{$parent->order}.{$ref}";
            $parent = $parent->parent;
        }

        return $ref;
    }

    /**
     * Check if is a poc (tenant has all features)
     */
    public function businessSectorOnly(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => parseOnlyAndExcepts($attributes['sector_only'] ?? []),
        );
    }

    /**
     * Check if is a poc (tenant has all features)
     */
    public function businessSectorExcept(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => parseOnlyAndExcepts($attributes['sector_except'] ?? []),
        );
    }

    /**
     * Check if is a poc (tenant has all features)
     */
    public function sizeOnly(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => parseOnlyAndExcepts($attributes['company_size_only'] ?? []),
        );
    }

    /**
     * Check if is a poc (tenant has all features)
     */
    public function sizeExcept(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => parseOnlyAndExcepts($attributes['company_size_except'] ?? []),
        );
    }

    public static function copy($questionnaireType)
    {
        return self::select('id', 'parent_id', 'category_id', 'weight', 'data')
            ->where('questionnaire_type_id', $questionnaireType)->get();
    }

    /**
     * Get all questions for a specific category
     */
    public static function allByCategory(Category $category)
    {
        return self::where('category_id', $category->id)->get()->sortBy('ref');
    }
}
