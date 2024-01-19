<?php

namespace App\Models\Tenant\Compliance\Reputational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisKeywordsFrequency extends Model
{
    use HasFactory;

    protected $table = 'reputation_analysis_keywords_frequency';

    protected $casts = [
        'extracted_at' => 'date',
        'data' => 'array',
    ];

    protected $fillable = ['ainfo_id', 'data', 'extracted_at'];
}
