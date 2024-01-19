<?php

namespace App\Models\Tenant\Compliance\Reputational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisKeywordsFrequencyDaily extends Model
{
    use HasFactory;

    protected $table = 'reputation_analysis_keywords_frequency_daily';

    protected $casts = [
        'extracted_at' => 'date',
        'data' => 'array',
    ];

    protected $fillable = ['ainfo_id', 'data', 'extracted_at', 'year', 'month', 'week_of_year'];
}
