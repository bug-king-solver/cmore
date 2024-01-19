<?php

namespace App\Models\Tenant\GarBtar;

use App\Models\Tenant\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankSimulation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'company_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * Belongs to company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Has many relationship with bank assets simulation
     */
    public function assets(): HasMany
    {
        return $this->hasMany(BankSimulationAssets::class);
    }
}
