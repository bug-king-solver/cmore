<?php

namespace App\Models\Tenant\Compliance\Reputational;

use App\Models\Tenant\Compliance\Reputational\AnalysisRawData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalysisInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reputation_analysis_info';

    protected $casts = [
        'search_terms' => 'array',
        'alternative_names' => 'array',
        'handles' => 'array',
        'competitors' => 'array',
    ];

    protected $fillable = [
        'company_id',
        'created_by_user_id',
        'name',
        'alternative_names',
        'handles',
        'language',
        'search_terms',
        'competitors',
    ];

    public function rawdata()
    {
        return $this->hasMany(AnalysisRawData::class, 'reputation_analysis_info_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
