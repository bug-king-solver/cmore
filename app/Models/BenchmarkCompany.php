<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenchmarkCompany extends Model
{
    protected $connection = 'central';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'note',
        'business_sector_id',
        'name',
        'ticker',
        'headquarters',
    ];

    public function reporter()
    {
        return $this->belongsTo(Admin::class, 'reporter_id');
    }

    /**
     * Get the business sector that owns the company.
     */
    public function business_sector()
    {
        return $this->belongsTo(BusinessSector::class);
    }

    public function benchmark_reports()
    {
        return $this->hasMany(BenchmarkReport::class);
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::orderBy('name')
            ->with('business_sector');
    }
}
