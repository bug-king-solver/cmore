<?php

namespace App\Models\Tenant\Compliance\Reputational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisKeywordsFrequencyYearly extends Model
{
    use HasFactory;

    protected $table = 'reputation_analysis_keywords_frequency_yearly';

    protected $casts = [
        'extracted_at' => 'date',
    ];

    protected $fillable = ['ainfo_id', 'data', 'extracted_at', 'year'];
}
