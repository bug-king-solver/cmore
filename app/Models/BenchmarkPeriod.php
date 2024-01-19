<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class BenchmarkPeriod extends Model
{
    use HasTranslations;
    use SoftDeletes;

    protected $connection = 'central';

    /**
     * Translatable columns
     */
    public $translatable = ['name'];

    protected $casts = [
        'enabled' => 'bool',
        'from' => 'date',
        'to' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'note',
        'name',
        'from',
        'to',
    ];

    /**
     * Get the reports for the indicator.
     */
    public function benchmark_reports()
    {
        return $this->hasMany(BenchmarkReport::class);
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

    public function scopeReportYear($query, $years)
    {
        foreach ($years as $year) {
            $query->orWhereYear('from', $year);
        }

        return $query;
    }
}
