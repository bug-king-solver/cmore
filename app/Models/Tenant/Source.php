<?php

namespace App\Models\Tenant;

use App\Enums\Sources\SourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Source extends Model
{
    use HasTranslations;
    use HasFactory;

    /**
     * Translatable columns
     */
    protected $fillable = ['name', 'type'];

    public $translatable = ['name'];

    protected $casts = [
        'type' => SourceType::class,
    ];

    /**
     * Get the questions for the source.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function indicators()
    {
        return $this->belongsToMany(Indicator::class, 'indicator_source')
            ->using(IndicatorSource::class)
            ->withPivot('reference');
    }

    public function reports()
    {
        return $this->hasMany(SourceReport::class);
    }
}
