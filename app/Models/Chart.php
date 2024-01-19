<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chart extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'central';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $cast = [
        'indicators' => 'array',
        'structure' => 'array',
    ];

    protected $fillable = [
        'name',
        'slug',
        'structure',
        'indicators',
        'type',
        'enabled',
    ];

    public function getStructureAttribute($value)
    {
        return parseStringToArray($value);
    }

    public function getIndicatorsAttribute($value)
    {
        return parseStringToArray($value);
    }
}
