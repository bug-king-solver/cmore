<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BenchmarkReport extends Model
{
    use SoftDeletes;

    protected $connection = 'central';

    protected $casts = [
        'enabled' => 'bool',
        'ready' => 'bool',
        'ready_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'note',
        'reporter_id',
        'validator_id',
        'period_id',
        'company_id',
        'currency',
        'validated',
        'ready',
        'ready_at',
        'time_to_ready',
        'benchmark_period_id',
        'benchmark_company_id'
    ];

    public function reporter()
    {
        return $this->belongsTo(Admin::class, 'reporter_id');
    }

    public function validator()
    {
        return $this->belongsTo(Admin::class, 'validator_id');
    }

    public function benchmark_period()
    {
        return $this->belongsTo(BenchmarkPeriod::class, 'benchmark_period_id');
    }

    public function benchmark_company()
    {
        return $this->belongsTo(BenchmarkCompany::class, 'benchmark_company_id');
    }

    public function benchmark_data()
    {
        return $this->hasMany(BenchmarkData::class);
    }

    public static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            $model->time_to_ready = calcTimeDiffInMin($model->created_at, request()->ready_at);
        });

        static::creating(function ($model) {
            $model->time_to_ready = calcTimeDiffInMin(date('Y-m-d H:i:s'), request()->ready_at);
        });
    }

    public function scopeReady($query)
    {
        return $query->whereNotNull('ready_at');
    }

    public function scopeValidated($query)
    {
        return $query->where('validated', 1);
    }

    public function getTitleAttribute()
    {
        return $this->benchmark_period->name . ' ' . $this->benchmark_company->name;
    }
}
