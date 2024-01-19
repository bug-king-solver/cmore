<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\QuestionnaireType;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    use HasFactory;
    use SoftDeletes;

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

    protected $casts = [
        'extra' => 'array'
    ];

    /**
     * Translatable columns
     */
    public $translatable = ['name', 'description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'enabled', 'model_type', 'parent_id', 'name', 'description'];

    /**
     * Get the children categories.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the questionnaire for the category.
     */
    public function questionnaire()
    {
        return $this->belongsToMany(QuestionnaireType::class);
    }

    /**
     * Get the indicators for the category.
     */
    public function indicators()
    {
        return $this->hasMany(Indicator::class);
    }

    /**
     * Get the questions for the category.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the all the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class)->withoutGlobalScope(EnabledScope::class)->whereCatalog(true);
    }

    /**
     * Get the all the categories with products.
     */
    public static function hasProducts()
    {
        return self::whereHas('products')->with('products');
    }

    /**
     * Get the questions for the category.
     */
    public function questionnaireAnswer()
    {
        if ($this->questionnaireId) {
            return $this->hasManyThrough(Answer::class, Question::class, 'category_id', 'question_id')
                ->where('questionnaire_id', $this->questionnaireId)
                ->whereNotNull('value');
        } else {
            return $this->hasManyThrough(Answer::class, Question::class, 'category_id', 'question_id')
                ->whereNull('questionnaire_id')
                ->whereNotNull('value');
        }
    }

    /**
     * Get the questions for the category.
     */
    public function answers($questionnaireId)
    {
        return $this->hasManyThrough(Answer::class, Question::class, 'category_id', 'question_id')
            ->where('questionnaire_id', $questionnaireId)
            ->where(function ($query) {
                $query->whereNotNull('value')
                    ->orWhere('not_applicable', true)
                    ->orWhere('not_reported', true);
            });
    }

    /**
     * Get the questions for the category.
     */
    public function answersCount($questionnaireId)
    {
        return $this->answers($questionnaireId)
            ->count();
    }

    public function getNameAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }

    public static function findWithChildrenCount(int $id)
    {
        return self::find($id)->withCount(['children']);
    }

    public static function main()
    {
        return self::whereNull('parent_id')
            ->orderBy('order')
            ->with('children')
            ->get();
    }

    public static function subIds()
    {
        return self::select('id')
            ->whereNotNull('parent_id')
            ->orderBy('order')
            ->get()
            ->pluck('id')
            ->toArray();
    }

    public static function ordered($main = null)
    {
        if (!$main) {
            $main = self::main();
        }

        $ordered = collect();
        foreach ($main as $category) {
            $ordered->push($category);

            foreach ($category->children as $child) {
                $ordered->push($child);
            }
        }

        return $ordered;
    }

    public static function orderedIds()
    {
        return self::ordered()->map(
            fn ($category, $key) => [
                'id' => $category->id,
                'parent_id' => $category->parent_id,
            ]
        );
    }

    public static function getAllowedCategory(self $category, ?array $allowedCategories = null)
    {
        // Get the first subcategory from the first category
        if (!$allowedCategories) {
            return $category;
        } else {
            return self::find(in_array($category->id, $allowedCategories, false) ? $category->id : 4, false);
        }
    }

    public static function listForQuestionnaire($questCategories)
    {
        $ids = array_column($questCategories, 'id');

        return self::with(['children' => function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        }])
            ->find($ids)
            ->sortBy('order');
    }

    public static function loadForQuestionnaire($questCategories)
    {
        if (!$questCategories) {
            return collect();
        }

        $questCategories = array_values($questCategories);
        $questCategoriesIds = array_column($questCategories, 'id');

        return self::listForQuestionnaire($questCategories)
            ->transform(
                function ($category) use ($questCategories, $questCategoriesIds) {
                    $key = array_search($category->id, $questCategoriesIds, false);
                    $questCategory = $questCategories[$key];

                    // Override the parent ID from the category model.
                    // Usgin the parent_id from the category saved on the json of the questionnaire.
                    $category['parent_id'] = $questCategory['parent_id'];

                    $category['questions_count'] = $questCategory['questions_count'];
                    $category['questions_sum_weight'] = $questCategory['questions_sum_weight'];
                    $category['questions_answered'] = $questCategory['questions_answered'];
                    $category['weight'] = $questCategory['weight'];
                    $category['progress'] = $questCategory['progress'];
                    $category['ponderation'] = $questCategory['ponderation'];
                    $category['maturity'] = $questCategory['maturity'];
                    $category['maturity_final'] = $questCategory['maturity_final'];

                    return $category;
                }
            );
    }

    /**
     * calc the ponderation for the category
     * @param int $questionnaireTypeId
     * @param int $categoryId
     * @param null|int $businessSectorId
     * @param bool|null $hasMaturity
     */
    public static function ponderation($questionnaireTypeId, $categoryId, int $businessSectorId, ?bool $hasMaturity = false)
    {
        if (!$hasMaturity) {
            $hasMaturity = QuestionnaireType::where('has_score', '1')
            ->where('id', $questionnaireTypeId)
            ->first();
        }

        if (!$hasMaturity) {
            return 0;
        }

        $file = base_path("database/data/ponderations/{$questionnaireTypeId}.json");
        $ponderations = file_exists($file)
            ? file_get_contents($file)
            : null;

        $ponderations = $ponderations
            ? json_decode($ponderations, true)
            : [];

        return $ponderations[$businessSectorId - 1][$categoryId] ?? 0;
    }


    /**
     * Get the parent categoriable model (user or post).
     */
    public function categoryable(): MorphTo
    {
        return $this->morphTo();
    }
}
