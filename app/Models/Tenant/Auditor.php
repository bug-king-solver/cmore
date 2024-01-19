<?php

namespace App\Models\Tenant;

use App\Models\Indicator;
use App\Nova\Tenant\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUsers;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Auditor extends Model
{
    use HasUsers;
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['indicator_id', 'company_id', 'status', 'type', 'frequency'];

    /**
     * Get the company for the source.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the indicator for the source.
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    /**
     * Get the log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function assignedUserMessage($auditer)
    {
        return [
            'userName' => $auditer->name,
            'auditor' => $this->name,
            'message' => __(':userName assigned you as an auditor.'),
        ];
    }
}
