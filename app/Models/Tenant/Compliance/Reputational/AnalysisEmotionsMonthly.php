<?php

namespace App\Models\Tenant\Compliance\Reputational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisEmotionsMonthly extends Model
{
    use HasFactory;

    protected $table = 'reputation_analysis_emotions_monthly';

    protected $casts = [
        'extracted_at' => 'date',
    ];

    protected $fillable = ['ainfo_id', 'data', 'extracted_at', 'year', 'month'];
}
