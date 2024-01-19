<?php

namespace App\Models\Tenant\Compliance\Reputational;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisEmotions extends Model
{
    use HasFactory;

    protected $table = 'reputation_analysis_emotions';

    protected $casts = [
        'extracted_at' => 'date',
    ];

    protected $fillable = ['ainfo_id', 'data', 'extracted_at'];
}
