<?php

namespace App\Models\Tenant;

use App\Models\Tenant\BusinessSectorType;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class BusinessSector extends Model
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

    /**
     * Translatable columns
     */
    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['enabled', 'name', 'data', 'business_sector_type_id', 'note', 'parent_id'];

    /**
     * Get the companies for the business sector.
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Get the business sector type that owns the business sector.
     */
    public function sectorType(): BelongsTo
    {
        return $this->belongsTo(BusinessSectorType::class, 'business_sector_type_id', 'id');
    }

    /**
     * Get a list of the indicators to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::orderBy('name');
    }

    /**
     * Get a list of the questionnaires
     */
    public function questionnaires()
    {
        return $this->hasManyThrough(
            Questionnaire::class,
            Company::class
        );
    }

    /**
     * Get the sector that owns the business activity.
     */
    public function parent()
    {
        return $this->belongsTo(BusinessSector::class, 'parent_id');
    }
}
