<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Tenant\Scopes\InstancesEnableScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class BusinessActivities extends Model
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
    public $translatable = ['name', 'note'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['enabled', 'name','note','nace', 'code', 'parent_id', 'description'];

    protected $casts = [
        'enabled' => 'boolean',
        'code' => 'string',
        'parent_id' => 'integer',
    ];

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
     * Get the sector that owns the business activity.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
