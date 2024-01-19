<?php

namespace App\Models\Tenant\Analysis;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsFrequency extends Model
{
    use HasFactory;

    protected $table = 'analysis_terms_frequency';

    protected $casts = [
        'weight' => 'float',
        'reported_at' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term',
        'weight',
        'year',
        'month',
        'day',
        'week_year',
        'day_week',
        'reported_at',
    ];

    public static function wordCloud(string $from, string $to): array
    {
        return self::
            select('term', 'weight')
            ->where('reported_at', '>=', $from)
            ->where('reported_at', '<=', $to)
            ->get()
            ->transform(fn ($termFrequency) => [$termFrequency->term, $termFrequency->weight])
            ->toArray();
    }

    public static function termsBetweenDates(string $from, string $to): Collection
    {
        return self::
            select('term')
            ->where('reported_at', '>=', $from)
            ->where('reported_at', '<=', $to)
            ->get();
    }
}
