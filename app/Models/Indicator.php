<?php

namespace App\Models;

use App\Models\Tenant\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Indicator extends Model
{
    use HasTranslations;
    use SoftDeletes;

    protected $connection = 'central';

    /**
     * Translatable columns
     */
    public $translatable = ['name', 'description'];

    protected $casts = [
        'enabled' => 'bool',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'unit_qty',
        'unit_default',
        'calc',
        'name',
        'description',
        'has_benchmarking',
        'is_financial',
    ];

    /**
     * Get the category for the indicator
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the data for the indicator.
     */
    public function data()
    {
        return $this->hasMany(BenchmarkData::class);
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
}
