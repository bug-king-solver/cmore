<?php

namespace App\Models;

use App\Models\Benchmarking;
use App\Models\BenchmarkReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\Currency\Exchanger;
use App\Models\Indicator;

class BenchmarkData extends Model
{
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
        'indicator_id',
        'value',
        'value_usd',
        'value_eur',
        'validated',
        'ready',
        'ready_at',
        'time_to_ready',
    ];

    /**
     * Get the company that owns the data.
     */
    public function benchmark_report()
    {
        return $this->belongsTo(BenchmarkReport::class);
    }

    /**
     * Get the company that owns the data.
     */
    public function benchmark_company()
    {
        return $this->belongsTo(BenchmarkCompany::class);
    }

    /**
     * Get the indicator that owns the data.
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public static function list()
    {
        return self::orderBy('reported_at', 'desc')
            ->with('company', 'indicator');
    }

    public static function boot()
    {
        parent::boot();

        $exchanger = new Exchanger();
        
        static::creating(function ($model) use($exchanger) {
            $baseCurrency = $model->benchmark_report->currency;
            if ($model->indicator->is_financial == '1' && $model->value != "" && $baseCurrency != "") {
                $value = floatval($model->value);
                $periodFrom = $model->benchmark_report->benchmark_period->from->format('Y-m-d');
                $model->value_usd = $exchanger->convert($baseCurrency, 'USD', $value, $periodFrom)->format();
                $model->value_eur = $exchanger->convert($baseCurrency, 'EUR', $value, $periodFrom)->format();
            } else {
                $model->value_usd = null;
                $model->value_eur = null;
            }
        });

        static::created(function ($model) {
            if ($model->indicator_id == '64') {
                $model->benchmark_report->employees = $model->value;
                $model->benchmark_report->save();
            }
            if ($model->indicator_id == '168') {
                $model->benchmark_report->revenue = $model->value;
                $model->benchmark_report->save();
            }
        });

        static::updating(function ($model) use($exchanger) {
            $baseCurrency = $model->benchmark_report->currency;
            if ($model->indicator->is_financial == '1' && $model->value != "" && $baseCurrency != "") {
                $value = floatval($model->value);
                $periodFrom = $model->benchmark_report->benchmark_period->from->format('Y-m-d');
                $model->value_usd = $exchanger->convert($baseCurrency, 'USD', $value, $periodFrom)->format();
                $model->value_eur = $exchanger->convert($baseCurrency, 'EUR', $value, $periodFrom)->format();
            } else {
                $model->value_usd = null;
                $model->value_eur = null;
            }
        });

        static::updated(function ($model) {
            if ($model->indicator_id == '64') {
                $model->benchmark_report->employees = $model->value;
                $model->benchmark_report->save();
            }
            if ($model->indicator_id == '168') {
                $model->benchmark_report->revenue = $model->value;
                $model->benchmark_report->save();
            }
        });
    }
}
