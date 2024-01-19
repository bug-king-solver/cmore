<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Filters\ReferenceFilter;
use App\Models\Tenant\Indicator;
use App\Models\Tenant\Validator;
use App\Models\Traits\ReportingPeriodsTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class Data extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use LogsActivity;
    use HasFilters;
    use IsSearchable;
    use HasDataColumn;
    use ReportingPeriodsTrait;


    protected $casts = [
        'reported_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $searchable = [
        'name',
    ];

    protected array $filters = [
        ReferenceFilter::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'indicator_id',
        'questionnaire_id',
        'value',
        'reported_at',
        'created_at',
        'updated_at',
        'user_id',
        'origin',
        'validator_requested',
        'validator_status',
        'auditor_requested',
        'auditor_status',
        'data',
        'reporting_period_id'
    ];

    /**
     * The attributes that should be cast.
     */
    public static function getCustomColumns(): array
    {
        return array_merge((new Data())->getFillable(), [
            'id',
            'created_at',
            'updated_at',
        ]);
    }

    public function getCasts()
    {
        return array_merge(parent::getCasts(), [
            static::getDataColumn() => 'array',
        ]);
    }

    public function scopeSearch(Builder $query, ?string $search, ?array $searchable = null): Builder
    {
        return $query->when(
            $search,
            fn (Builder $query) =>
            $this->applySearchQuery(
                $query->join('indicators', 'data.indicator_id', '=', 'indicators.id'),
                $search,
                $searchable
            )
        );
    }
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'company_id' => $this->company_id,
            'indicator_id' => $this->indicator_id,
            'questionnaire_id' => $this->questionnaire_id,
            'value' => $this->value,
            'reported_at' => $this->reported_at,
        ];
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (float) $value != (float) rtrim($value, '0') ? $value : rtrim(rtrim($value, '0'), '.')
        );
    }

    /**
     * Get the company that owns the data.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user that owns the data.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the indicator that owns the data.
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public static function mainBenchmarkingMonthly(int $indicatorId, int $companyId, array $years)
    {
        $data = self::select(DB::raw('SUM(value) AS value'), DB::raw('DATE_FORMAT(reported_at, "%Y-%m") AS date'))
            ->where('indicator_id', $indicatorId)
            ->where('company_id', $companyId)
            ->where(function ($query) use ($years) {
                foreach ($years as $year) {
                    $query->orWhere('reported_at', 'like', "{$year}%");
                }
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        // Add missing data
        $datesFound = array_column($data, 'date');
        $previousValue = 0;
        foreach ($years as $year) {
            for ($i = 1; $i <= 12; $i++) {
                $date = $year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (!in_array($date, $datesFound, false)) {
                    $data[] = [
                        'value' => $previousValue,
                        'date' => $date,
                    ];
                }
            }
        }

        // Sort data
        $datesFound = array_column($data, 'date');
        array_multisort($datesFound, SORT_ASC, $data);

        return array_column($data, 'value');
    }

    public static function mainBenchmarkingYearly(int $indicatorId, int $companyId, array $years)
    {
        $data = self::select(DB::raw('SUM(value) AS value'), DB::raw('YEAR(reported_at) date'))
            ->where('indicator_id', $indicatorId)
            ->where('company_id', $companyId)
            ->where(function ($query) use ($years) {
                foreach ($years as $year) {
                    $query->orWhere('reported_at', 'like', "{$year}%");
                }
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        // Add missing data
        $datesFound = array_column($data, 'date');
        foreach ($years as $year) {
            if (!in_array($year, $datesFound, false)) {
                $data[] = [
                    'value' => 0,
                    'date' => $year,
                ];
            }
        }

        // Sort data
        $datesFound = array_column($data, 'date');
        array_multisort($datesFound, SORT_ASC, $data);

        return array_column($data, 'value');
    }

    public static function distributionBenchmarking(int $indicatorId, int $companyId, $year)
    {
        $revenueIndicatorId = 168;

        $data = self::select('indicator_id', DB::raw('SUM(value) AS value'))
            ->whereIn('indicator_id', [$revenueIndicatorId, $indicatorId])
            ->where('company_id', $companyId)
            ->where('reported_at', 'like', "{$year}%")
            ->groupBy('indicator_id')
            ->get();

        if (count($data) < 2) {
            return [];
        }
        $x = 0;
        $y = 0;
        foreach ($data as $row) {
            if ($revenueIndicatorId === $row->indicator_id) {
                $x = $row->value;
            } else {
                $y = $row->value;
            }
        }
        return ['x' => $x, 'y' => $y];
    }

    /**
     * Get a list of the data to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::orderBy('reported_at', 'desc')
            ->with('company', 'indicator.sources');
    }

    public static function getMedianAndDaviationData(array $where)
    {
        $data = self::select('value', DB::raw('YEAR(reported_at) reported_year'))
            ->where('indicator_id', $where['indicator'])
            ->whereIn('company_id', $where['companies'])
            ->where(function ($query) use ($where) {
                foreach ($where['years'] as $year) {
                    $query->orWhere('reported_at', 'like', "{$year}%");
                }
            })
            ->get()
            ->toArray();

        $medianYearWiseData = [];
        foreach ($data as $medianDataArr) {
            $medianYearWiseData[$medianDataArr['reported_year']][] = $medianDataArr['value'];
        }

        $returnMedianData = [];
        $returnDaviationData = [];
        foreach ($where['years'] as $year) {
            $returnMedianData[$year] = isset($medianYearWiseData[$year])
                ? calculateMedian($medianYearWiseData[$year])
                : 0;
            $returnDaviationData[$year] = isset($medianYearWiseData[$year])
                ? standardDeviation($medianYearWiseData[$year])
                : 0;
        }

        return ['median' => array_values($returnMedianData), 'daviation' => array_values($returnDaviationData)];
    }
}
