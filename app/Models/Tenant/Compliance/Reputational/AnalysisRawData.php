<?php

namespace App\Models\Tenant\Compliance\Reputational;

use App\Models\Tenant\Compliance\Reputational\AnalysisInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalysisRawData extends Model
{
    use HasFactory;

    protected $table = 'reputation_analysis_raw_data';

    protected $fillable = ['reputation_analysis_info_id', 'language', 'data', 'extracted_at'];

    public function analysis_info()
    {
        return $this->belongsTo(AnalysisInfo::class, 'reputation_analysis_info_id');
    }
}
