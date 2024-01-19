<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Models\Traits\QueryBuilderScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class SharingOption extends Model
{
    use HasFactory;
    use QueryBuilderScopes;
    use HasDataColumn;

    protected $fillable = [
        'id', 'identifier', 'enabled', 'logo', 'name', 'commercial_name', 'data'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new EnabledScope());
    }

    /**
     * Get the companies for the business sector.
     */

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'sharing_options_companies')->withPivot('name as sharing_option_name');
    }

    /**
     * The attributes that should be cast.
     */
    public static function getCustomColumns(): array
    {
        return array_merge((new self())->getFillable(), []);
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
            'identifier' => $this->identifier,
            'created_at' => $this->created_at,
        ];
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
}
