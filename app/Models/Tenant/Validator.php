<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Concerns\Interfaces\Userable;
use App\Models\Indicator;
use App\Nova\Tenant\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUsers;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Validator extends Model
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

    /**
     * Create the assigned user message
     */
    public function assignedUserMessage($assigner)
    {
        return [
            'userName' => $assigner->name,
            'validator' => $this->name,
            'message' => __(':userName assigned you as a validator.'),
        ];
    }
}
