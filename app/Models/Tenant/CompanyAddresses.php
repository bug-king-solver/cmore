<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
use App\Services\ThinkHazard\ThinkHazard;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyAddresses extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'headquarters',
        'country_code',
        'region_code',
        'city_code',
        'latitude',
        'longitude'
    ];

    /** @var array<string> $appends */
    protected $appends = [
        'country',
        'region',
        'city',
    ];

    /** @var array<string> $casts */
    protected $casts = [
        'country_code' => 'int',
        'region_code' => 'int',
        'city_code' => 'int',
        'headquarters' => 'boolean',
    ];

    /**
     * Belongs to company
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Has many relationship with physcalRisks
     * @return HasMany
     */
    public function physicalRisks(): HasMany
    {
        return $this->hasMany(PhysicalRisks::class);
    }

    /**
     * Get the city name.
     */
    public function getHazardNameAttribute()
    {
        $hazard = new ThinkHazard();
        if ($this->city_code && $this->region_code && $this->country_code) {
            return $hazard->getCity($this->city_code, $this->region_code, $this->country_code) ?? null;
        } else {
            return null;
        }
    }

    /**
     * Get the full location address attribute.
     * @return Attribute
     */
    public function nameHeadquarter(): Attribute
    {
        return Attribute::make(
            get: function () {
                return "{$this->name} " . ($this->headquarters
                    ? "(Headquarters) "
                    : '');
            }
        );
    }

    /**
     * Get the total of aligned value of assets
     */
    public function location(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $hazardApiData = $this->hazardName;
                return [
                    'city_name' => $hazardApiData['city_name'] ?? null,
                    'region_name' => $hazardApiData['region_name'] ?? null,
                    'country_name' => $hazardApiData['country_name'] ?? null,
                ];
            },
        );
    }

    /**
     * Get the full location address attribute.
     * @return Attribute
     */
    public function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $location = $this->location;
                return "{$location['city_name']} , {$location['region_name']} - {$location['country_name']}";
            }
        );
    }

    /**
     * Get the total of aligned value of assets
     */
    public function country(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->location['country_name'] ?? null;
            },
        );
    }

    /**
     * Get the total of aligned value of assets
     */
    public function region(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->location['region_name'] ?? null;
            },
        );
    }

    /**
     * Get the total of aligned value of assets
     */
    public function city(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->location['city_name'] ?? null;
            },
        );
    }
}
