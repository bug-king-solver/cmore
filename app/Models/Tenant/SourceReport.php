<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Data;
use App\Models\Traits\ReportingPeriodsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class SourceReport extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ReportingPeriodsTrait;


    protected $fillable = [
        'source_id',
        'company_id',
        'questionnaire_id',
        'data',
        'reporting_period_id',
        'from',
        'to'
    ];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
        'reporting_period_id' => 'integer',
        'data' => 'array',
        'synchronized_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (SourceReport $model) {
            $items = [];
            // Get All References Against Source
            $populatedDatas = $model->populateData();
            foreach ($populatedDatas as $category => $subcategories) {
                foreach ($subcategories as $subcategory => $indicators) {
                    foreach ($indicators as $key => $report) {
                        $report['source_indicator'] = collect($report['source_indicator'] ?? [])
                            ->map(function ($indicator) use ($model, $report, $key) {

                                $indicatorIds = $indicator->indicator ?? [];

                                if (is_object($indicatorIds)) {
                                    $indicatorIds = collect($indicatorIds)->toArray();
                                } elseif (!is_array($indicatorIds)) {
                                    $indicatorIds = [$indicatorIds];
                                }

                                $indicatorIds = array_filter($indicatorIds, fn ($indicator) => is_numeric($indicator));


                                $indicatorValues = [];
                                $value = "";
                                if ($model->company && !empty($indicatorIds)) {
                                    $indicatorValues = Data::where("company_id", $model->company_id)
                                        ->whereIn('indicator_id', $indicatorIds)
                                        ->with('indicator')
                                        ->whereHas('indicator')
                                        ->select('indicator_id', 'created_at', 'value');

                                    if ($model->questionnaire_id) {
                                        $indicatorValues = $indicatorValues->where('questionnaire_id', $model->questionnaire_id);
                                    }

                                    $indicatorValues = $indicatorValues->orderBy('created_at')
                                        ->groupBy('indicator_id', 'created_at', 'value')
                                        ->get()
                                        ->groupBy('indicator_id');

                                    $indicatorValues = $indicatorValues->map(function ($group) {
                                        return $group->sortByDesc('created_at')->first();
                                    })->values()->all();

                                    $indicators = array_column($indicatorValues, 'indicator');

                                    switch ($indicator->action) {
                                        case 'concatenate':
                                            $value = implode(",", array_column($indicators, "name"));
                                            break;
                                        case 'sum':
                                            $value = array_sum(array_column($indicatorValues, "value"));
                                            break;
                                        default:
                                            $value = implode(",", array_column($indicatorValues, "value"));
                                            break;
                                    }
                                }
                                $indicator->indicator_value = $value;
                                return $indicator;
                            });

                        $items[$category][$subcategory][] = $report;
                    }
                }
            }
            $model->data = $items;
        });
    }

    /**
     * Get the file path for GRI
     * @return string|null
     */
    private function getFilePathForGRI(): ?string
    {
        switch ($this->source_id) {
            case 1:
                return base_path() . '/database/data/frameworks/data/gri.json';
            case 7:
                return base_path() . '/database/data/frameworks/data/csrd.json';
            default:
                return null;
        }
    }

    /**
     * Get the source that owns the SourceReport
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sources()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    /**
     * Get the company that owns the SourceReport
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the questionnaire that owns the SourceReport
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id');
    }

    public static function list()
    {
        return self::with('company')->with('sources')->get();
    }

    public function getIndicators(): array
    {
        $indicators = [];
        foreach ($this->data as $item) {
            $indicators = array_merge($indicators, $item['indicators'] ?? []);
        }
        return $indicators;
    }

    private function getData()
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->from)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->to)->endOfDay();
        $results = Data::whereIn('indicator_id', $this->getIndicators())
            ->where('company_id', '=', $this->company_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('updated_at', '>', $this->synchronized_at);

        if ($this->questionnaire_id) {
            $results = $results->where('questionnaire_id', $this->questionnaire_id);
        }
        return $results->orderBy('updated_at', 'desc');
    }

    public function hasDataToUpdate()
    {
        return $this->getData()->get()->count() > 0;
    }

    public function getQueryForIndicator(): array
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->from)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $this->to)->endOfDay();
        $query = [
            'companies' => [$this->company->id],
        ];
        if ($this->questionnaire) {
            $query['questionnaires'] = [$this->questionnaire->id];
        } else {
            $query['between'] = [
                'from' => $startDate,
                'to' => $endDate,
            ];
        }
        return $query;
    }

    /**
     * @return array
     */
    public function populateData(): array
    {
        $file = File::get($this->getFilePathForGRI());
        $gri = json_decode($file);

        $referenceData = [];

        foreach ($gri as $category => $subcategories) {
            foreach ($subcategories as $subcategory => $indicators) {
                foreach ($indicators as $key => $indicator) {
                    $referenceData[$category][$subcategory][$key] = [
                        'reference' => $indicator->reference,
                        'source_code' => $indicator->source_code ?? '',
                        'location' => '',
                        'comment' => '',
                        'source_indicator' => $indicator->source_indicator ?? [],
                        'external_assurance' => $indicator->external_assurance ?? false,
                        'title' => $indicator->title,
                        'subtitle' => $indicator->subtitle,
                        'description' => $indicator->description,
                    ];
                }
            }
        }
        return $referenceData;
    }

    public function getSuggestions($key): array
    {
        $suggestions = [];
        $query = $this->getQueryForIndicator();
        $indicators = Indicator::with('data')->whereIn('id', $this->data[$key]['indicators'])->get();
        foreach ($indicators as $indicator) {
            if ($currentValue = $indicator->filterData($query)) {
                $currentValue = $currentValue->first()->value ?? "";
            } else {
                $currentValue = "";
            }
            $location = $indicator->name . ': ' . $currentValue;
            $suggestions[] = $location;
        }
        return $suggestions;
    }

    public function forceUpdate(bool $clearComments, bool $clearExternal)
    {
        $dataStored = $this->getData()->get();
        $indicators = $dataStored->pluck('indicator_id')->toArray();
        $referenceData = [];
        foreach ($this->data as $key => $item) {
            $item['comment'] = ($clearComments)
                ? ""
                : $item['comment'];
            $item['external_assurance'] = ($clearExternal)
                ? false
                : $item['external_assurance'];
            foreach ($item['indicators'] as $indicator) {
                if (!in_array($indicator, $indicators)) {
                    continue;
                }
                $item['location'] = implode("\n", $this->getSuggestions($key));
            }
            $referenceData[] = $item;
        }

        $this->data = $referenceData;
        $this->synchronized_at = Carbon::now();
        $this->save();
    }
}
