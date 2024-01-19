<?php

namespace App\Models\Tenant\Compliance\Reputational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisSentimentsYearly extends Model
{
    use HasFactory;

    protected $table = 'reputation_analysis_sentiments_yearly';

    protected $casts = [
        'extracted_at' => 'date',
    ];

    protected $fillable = ['ainfo_id', 'data', 'extracted_at', 'year'];
}
