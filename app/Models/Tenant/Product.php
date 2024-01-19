<?php

namespace App\Models\Tenant;

use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Product extends Model
{
    use HasTranslations;
    use HasFactory;
    use HasDataColumn;
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

    protected $fillable = [
        'id',
        'enabled',
        'category_id',
        'productable_type',
        'productable_id',
        'icon',
        'title',
        'description',
        'price',
        'catalog',
    ];

    protected $casts = [
        'data' => 'array',
        'enabled' => 'bool',
        'catalog' => 'bool',
    ];

    /**
     * Translatable columns
     */
    public $translatable = [
        'title',
        'description'
    ];

    /**
     * The attributes that should be cast.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'enabled',
            'category_id',
            'productable_type',
            'productable_id',
            'icon',
            'title',
            'description',
            'price',
            'catalog',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    public function getCasts()
    {
        return array_merge(parent::getCasts(), [
            'data' => 'array',
        ]);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get buy button href attribute
     */
    public function buttonGotoRouteParamName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $parts = explode('\\', $this->productable_type);
                return $this->productable->productButtonGotoParamName ?? strtolower(end($parts));
            }
        );
    }

    /**
     * Get buy button href attribute
     */
    public function buttonBuyHref(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => isset($attributes['buttons']['buy'])
                ? route($attributes['buttons']['buy']['route'], $attributes['buttons']['buy']['params'] ?? [])
                : '#',
        );
    }

    /**
     * Get buy button href attribute
     */
    public function buttonInfoHref(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => isset($attributes['buttons']['info'])
                ? route($attributes['buttons']['info']['route'], $attributes['buttons']['info']['params'] ?? [])
                : '#',
        );
    }

    /**
     * Get buy button href attribute
     */
    public function buttonGotoRoute(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['buttons']['goto']['route'] ?? null,
        );
    }
}
