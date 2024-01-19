<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Sdg extends Model
{
    use HasTranslations;
    use HasFactory;

    /**
     * Translatable columns
     */
    public $translatable = ['name', 'icon'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'icon'];
}
