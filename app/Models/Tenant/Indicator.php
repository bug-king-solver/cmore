<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Filters\Indicatores\TypeFilter;
use App\Models\Tenant\Filters\ReferenceFilter;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use App\Models\Traits\HasInternalTags;
use App\Models\Traits\QueryBuilderScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;
use App\Models\Traits\Filters\IsSortable;

class Indicator extends Model
{
    use HasTranslations;
    use HasFactory;
    use QueryBuilderScopes;
    use HasInternalTags;
    use SoftDeletes;
    use HasFilters;
    use IsSearchable;
    use HasDataColumn;
    use IsSortable;

    protected array $searchable = [
        'name',
    ];

    protected array $filters = [
        TypeFilter::class,
        // ReferenceFilter::class,
    ];



    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Don't use the EnabledScope, because it is used in a lot of things.
        // Force to show only the enabled ones in the specific case.
        //static::addGlobalScope(new EnabledScope());
        static::addGlobalScope(new InstancesEnableScope());
    }

    /**
     * Translatable columns
     */
    public $translatable = ['name', 'description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'category_id',
        'unit_qty',
        'unit_default',
        'calc',
        'name',
        'label',
        'description',
        'is_financial',
        'has_benchmarking',
        'data'
    ];

    protected array $sortable = [
        'id' => 'Id',
        'name' => 'Name'
    ];

    /**
     * The attributes that should be cast.
     */
    public static function getCustomColumns(): array
    {
        return array_merge((new Indicator())->getFillable(), [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    public function getCasts()
    {
        return array_merge(parent::getCasts(), [
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
            'description' => $this->description,
        ];
    }

    /**
     * Get the data for the indicator.
     */
    public function data()
    {
        return $this->hasMany(Data::class)->orderBy('reported_at', 'desc');
    }

    /**
     * Get the data for the indicator.
     */
    public function dataNoOrder()
    {
        return $this->hasMany(Data::class);
    }

    /**
     * Get the questions for the indicator.
     */
    public function questions()
    {
        return $this->hasMany(self::class);
    }

    public function sources()
    {
        return $this->belongsToMany(Source::class, 'indicator_source')
            ->using(IndicatorSource::class)
            ->withPivot('reference');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Get a list of the indicators to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return new Indicator();
    }

    /**
     * Get the data for the indicator with query.
     */
    public function filterData(array $filters)
    {
        if (!$this->data) {
            return;
        }
        $results = $this->data;
        if (isset($filters['companies'])) {
            $results = $results->whereIn('company_id', $filters['companies']);
        }
        if (isset($filters['questionnaires'])) {
            $results = $results->whereIn('questionnaire_id', $filters['questionnaires']);
        }
        if (isset($filters['between'])) {
            $results = $results->whereBetween('reported_at', [$filters['between']['from'], $filters['between']['to']]);
        }
        return $results;
    }

    public function validator()
    {
        return $this->hasMany(Validator::class, 'indicator_id');
    }
}
