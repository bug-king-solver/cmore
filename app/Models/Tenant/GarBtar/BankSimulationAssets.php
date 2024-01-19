<?php

namespace App\Models\Tenant\GarBtar;

use App\Http\Livewire\Traits\Taxonomy\BankAssetsTrait;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class BankSimulationAssets extends Model
{

    use HasDataColumn;
    use BankAssetsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_simulation_id',
        'data',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'bank_simulation_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * Belongs to Bank Simulation
     */
    public function bankSimulation()
    {
        return $this->belongsTo(BankSimulation::class);
    }
}
