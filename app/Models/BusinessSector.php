<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class BusinessSector extends Model
{
    use HasTranslations;

    protected $connection = 'central';

    /**
     * Translatable columns
     */
    public $translatable = ['name'];

    protected $casts = [
        'enabled' => 'bool',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['enabled', 'note', 'parent_id', 'name'];

    /**
     * Get the companies for the business sector.
     */
    public function benchmark_companies()
    {
        return $this->hasMany(Company::class);
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
