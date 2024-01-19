<?php

namespace App\Models\Tenant;

use App\Models\Enums\ReportingPeriodsEnum;
use App\Models\Enums\ReportingPeriodsTypeEnum;
use App\Models\Traits\Filters\IsSortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;

class ReportingPeriod extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasFilters;
    use IsSearchable;
    use IsSortable;

    protected $fillable = [
        'type',
        'name',
        'year',
        'typeOrder',
        'custom_name',
        'id_year',
        'id_semester',
        'id_quarter',
        'enabled_questionnaires_filter',
        'enabled_questionnaires_reporting',
        'enabled_monitoring_filter',
        'enabled_monitoring_reporting',
    ];

    protected $casts = [
        'id_year' => 'integer',
        'id_semester' => 'integer',
        'id_quarter' => 'integer',
        'typeOrder' => 'integer',
        'year' => 'integer',
        'enabled_questionnaires_filter' => 'boolean',
        'enabled_questionnaires_reporting' => 'boolean',
        'enabled_monitoring_filter' => 'boolean',
        'enabled_monitoring_reporting' => 'boolean',
    ];

    /** @var array<string,mixed> $filters  */
    protected array $filters = [];

    /** @var array<int,string> $searchable  */
    protected array $searchable = [
        'name', 'custom_name',
    ];
    /** @var array<string,mixed> $sortable  */
    protected array $sortable = [
        'id' => 'Id',
        'name' => 'Name',
        'custom_name' => 'Custom name',
    ];

    /**
     * Get the reporting period's year.
     * @return BelongsTo
     */
    public function year(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id_year');
    }

    /**
     * Get the reporting period's semester.
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id_semester');
    }

    /**
     * Get the reporting period's quarter.
     * @return BelongsTo
     */
    public function quarter(): BelongsTo
    {
        return $this->belongsTo(self::class, 'id_quarter');
    }


    /**
     * Get the reporting period START date.
     * @return Attribute
     */
    public function startDate(): Attribute
    {
        return Attribute::make(
            get: fn () => ReportingPeriodsTypeEnum::from($this->type)
                ->startDate($this->year, $this->typeOrder)
        );
    }

    /**
     * Get the reporting period END date.
     * @return Attribute
     */
    public function endDate(): Attribute
    {
        return Attribute::make(
            get: fn () => ReportingPeriodsTypeEnum::from($this->type)
                ->endDate($this->year, $this->typeOrder)
        );
    }

    /**
     * Check if the reporting period is enabled for questionnaires filter.
     * @return Attribute
     */
    public function enableQuestionnaireFilter(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['enabled_questionnaires_filter'] ? __('Active') : __('Not active'),
        );
    }

    /**
     * Check if the reporting period is enabled for questionnaires reporting.
     * @return Attribute
     */
    public function enableQuestionnaireReporting(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['enabled_questionnaires_reporting'] ? __('Active') : __('Not active'),
        );
    }

    /**
     * Check if the reporting period is enabled for monitoring filter.
     * @return Attribute
     */
    public function enableMonitoringFilter(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['enabled_monitoring_filter'] ? __('Active') : __('Not active'),
        );
    }

    /**
     * Check if the reporting period is enabled for monitoring reporting.
     * @return Attribute
     */
    public function enableMonitoringReporting(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['enabled_monitoring_reporting'] ? __('Active') : __('Not active'),
        );
    }

    /**
     * Return all reporting periods available for questionnaires form.
     * @return array<string,mixed>
     */
    public static function questionnaireFormList(): array
    {
        return parseDataForSelect(
            self::questionnaireForm()->get(),
            'id',
            'custom_name'
        );
    }

    /**
     * Return all reporting periods available for monitoring form.
     * @return array<string,mixed>
     */
    public static function monitoringFormList(): array
    {
        return parseDataForSelect(
            self::questionnaireForm()->get(),
            'id',
            'custom_name'
        );
    }

    /**
     * Check if the reporting period is enabled for questionnaires filter.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQuestionnaireForm(Builder $query): Builder
    {
        return $query->where('enabled_questionnaires_filter', 1);
    }

    /**
     * Check if the reporting period is enabled for questionnaires filter.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQuestionnaireReporting(Builder $query): Builder
    {
        return $query->where('enabled_questionnaires_reporting', 1);
    }

    /**
     * Check if the reporting period is enabled for questionnaires filter.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMonitoringForm(Builder $query): Builder
    {
        return $query->where('enabled_monitoring_filter', 1);
    }

    /**
     * Check if the reporting period is enabled for questionnaires filter.
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMonitoringReporting(Builder $query): Builder
    {
        return $query->where('enabled_monitoring_reporting', 1);
    }
}
