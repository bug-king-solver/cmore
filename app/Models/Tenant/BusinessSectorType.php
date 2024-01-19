<?php

namespace App\Models\Tenant;

use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class BusinessSectorType extends Model
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

    protected $fillable = [
        'name',
        'name_secondary',
        'enabled',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Translatable columns
     */
    public $translatable = ['name', 'name_secondary'];

    /**
     * Get the business sectors for the business sector type.
     */
    public function businessSectors(): HasMany
    {
        return $this->hasMany(BusinessSector::class);
    }

    /**
     * Get the label for the Main Business Sector based on the configuration
     */
    public static function labelForMain()
    {
        return self::select('name')->pluck('name')->join('/');
    }

    /**
     * Get the label for the Secondary Business Sector based on the configuration
     */
    public static function labelForSecondary()
    {
        $name = self::select('name_secondary')->pluck('name_secondary')->join('/');
        return $name !== "//"
            ? $name
            : __('Secondary Sectors');
    }
}
