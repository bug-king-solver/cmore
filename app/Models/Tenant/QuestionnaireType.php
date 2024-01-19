<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use App\Models\Traits\Catalog\ProductItem;
use App\Models\Traits\QueryBuilderScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class QuestionnaireType extends Model
{
    use HasFactory;
    use QueryBuilderScopes;
    use SoftDeletes;
    use HasDataColumn;
    use HasTranslations;
    use ProductItem;

    /**
     * Param name used for the questionnaire.show route.
     * Used in: App\Models\Tenant\Product::class
     */
    public $productButtonGotoParamName = 'questionnaire';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'has_score' => 'bool'
    ];

    protected $fillable = [
        'id', 'enabled', 'name', 'slug', 'data', 'has_score'
    ];

    protected $translatable = [
        'name'
    ];

    /**
     * The attributes that should be cast.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id', 'enabled', 'name', 'slug' , 'dashboard_mini'
        ];
    }

    /**
     * Merge translatable attributes with the default attributes.
     */
    public function getCasts()
    {
        return array_merge(parent::getCasts(), [
            array_fill_keys($this->getTranslatableAttributes(), 'array'),
            static::getDataColumn() => 'array',
        ]);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get the answers for the questionnaire.
     */
    public function defaultCategories()
    {
        return $this->hasMany(Category::class, 'model_id', 'id')->where('model_type', self::class);
    }

    /**
     * Get the questionnaires for the questionnaire type.
     */
    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class);
    }

    public function product()
    {
        return $this->morphOne(Product::class, 'productable');
    }

    /**
     * Get a questionnaire type by its slug.
     *
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getBySlug(string $slug)
    {
        return self::where('slug', $slug)->get();
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::OnlyOwnData()
            ->orderBy('name');
    }

    /**
     * Copy default categories for a questionnaire type.
     *
     * @param int $questionnaireTypeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function copyDefaultCategories(int $questionnaireTypeId)
    {
        return self::find($questionnaireTypeId)
            ->defaultCategories()
            ->withCount(['questions' => function ($query) {
                $query->whereNull('parent_id');
            }])
            ->withSum(['questions' => function ($query) {
                $query->whereNull('parent_id');
            }], 'weight')
            ->get();
    }

    public function hasPonderation()
    {
        $file = base_path("database/data/ponderations/{$this->id}.json");
        return file_exists($file);
    }

    /**
     * Check if the questionnaire type can be duplicated.
     */
    public function canDuplicate()
    {
        return $this->can_duplicate ?? false;
    }

    public function scopeHasDashboardMini($query)
    {
        return $query->where('data->dashboardMini', true);
    }
}
