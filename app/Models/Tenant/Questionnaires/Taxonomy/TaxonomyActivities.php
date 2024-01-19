<?php

namespace App\Models\Tenant\Questionnaires\Taxonomy;

use App\Models\Enums\Taxonomy\AcronymForObjectives;
use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Traits\Taxonomy\TaxonomyTrait;
use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxonomyActivities extends Model
{
    use HasFactory;
    use TaxonomyTrait;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'taxonomy_id',
        'business_activities_id',
        'summary',
        'contribute',
        'dnsh',
        'code'
    ];

    protected $casts = [
        'taxonomy_id' => 'integer',
        'business_activities_id' => 'integer',
        'summary' => 'json',
        'contribute' => 'json',
        'dnsh' => 'json',
    ];

    /**
     * Get the default business resume.
     *
     * @return array
     */
    public static function activiyResumeDefault(): array
    {
        return [
            'volume' => [
                "value" => 0,
                "percentage" => 0,
            ],
            'capex' => [
                "value" => 0,
                "percentage" => 0,
            ],
            'opex' => [
                "value" => 0,
                "percentage" => 0,
            ],
            "contribute" => [
                "objectives" => []
            ],
            "dnsh" => [
                "objectives" => []
            ],
        ];
    }

    public static function getDefaultObjetiveArray(): array
    {
        $defaultArray = [
            "value" => 0,
            "percentage" => 0,
        ];
        return [
            "active" => true,
            "percentage" => 0,
            "volume" => $defaultArray,
            "capex" => $defaultArray,
            "opex" => $defaultArray,
            "enabling" => $defaultArray,
            "transition" => $defaultArray
        ];
    }

    /**
     * Get the default business resume.
     *
     * @return array
     */
    public static function defaultQuestionsArray(): array
    {
        return [
            'verified' => null,
            'imported' => 0,
            'hasPercentage' => 0,
            'data' => []
        ];
    }

    //booted method of the model
    protected static function booted()
    {
        static::creating(function ($taxonomyActivities) {
            $npsContribute = $taxonomyActivities->createContributeAndNps(false);
            if (!$taxonomyActivities->contribute) {
                $taxonomyActivities->contribute = $npsContribute['cs'];
            }
            if (!$taxonomyActivities->dnsh) {
                $taxonomyActivities->dnsh = $npsContribute['dnsh'];
            }

            if (empty($taxonomyActivities->summary)) {
                $taxonomyActivities->summary = self::activiyResumeDefault();
            }

            $resume = $taxonomyActivities->summary;
            if (!isset($resume['contribute']['objectives']) || !$resume['contribute']['objectives']) {
                foreach ($npsContribute['cs']['data'] as $item) {
                    $resume['contribute']['objectives'][AcronymForObjectives::fromValue($item['name'][auth()->user()->locale ?? app()->getLocale()])] = self::getDefaultObjetiveArray();
                }
            }
            if (!isset($resume['dnsh']['objectives']) || !$resume['dnsh']['objectives']) {
                foreach ($npsContribute['dnsh']['data'] as $item) {
                    $resume['dnsh']['objectives'][AcronymForObjectives::fromValue($item['name'][auth()->user()->locale ?? app()->getLocale()])] = self::getDefaultObjetiveArray();
                }
            }
            $taxonomyActivities->summary = $resume;

            $taxonomyActivities->identifier = Taxonomy::find($taxonomyActivities->taxonomy_id)
                ->activities()
                ->withoutGlobalScopes()
                ->count() + 1;
        });

        static::created(function ($taxonomyActivities) {
            self::calcTaxonomyValues(Taxonomy::find($taxonomyActivities->taxonomy_id));
        });

        static::updated(function ($taxonomyActivities) {
            self::calcTaxonomyValues(Taxonomy::find($taxonomyActivities->taxonomy_id));

            $questions = [];
            if ($taxonomyActivities->contribute == "" || $taxonomyActivities->dnsh == "") {
                $questions = self::getQuestions($taxonomyActivities->code);
            }

            if ($taxonomyActivities->contribute == "") {
                $taxonomyActivities->resetContributeSubstantial($questions);
            }

            if ($taxonomyActivities->dnsh == "") {
                $taxonomyActivities->resetNps($questions);
            }
        });

        static::deleted(function ($taxonomyActivities) {
            // update the taxonomy table
            self::calcTaxonomyValues(Taxonomy::find($taxonomyActivities->taxonomy_id));
        });
    }

    /**
     * Get the taxonomy associated with the taxonomy activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    /**
     * Get the activity associated with the taxonomy activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sector()
    {
        return $this->belongsTo(BusinessActivities::class, 'business_activities_id');
    }

    /**
     * Get the code attribute for the taxonomy activity.
     *
     * @return string
     */
    public function getCodeAttribute(): string
    {
        $arr = explode('-', $this->sector->name);
        $result = trim($arr[0]);
        return "$result";
    }

    /**
     * Get the description attribute for the taxonomy activity.
     *
     * @return string
     */
    public function getDescriptionAttribute(): string
    {
        $code = $this->getCodeAttribute();
        $description = '';

        $businessActivites = BusinessActivities::where('code', $code)->first();

        if ($businessActivites) {
            $description = $businessActivites->description;
        }

        return $description ?? '';
    }


    /**
     * Get the activity resume attribute for the taxonomy activity.
     */
    public function getActivityIsAlignedAttribute()
    {
        $contribute = parseStringToArray($this->contribute);
        $dnsh = parseStringToArray($this->dnsh);
        $safeguard = parseStringToArray(Taxonomy::find($this->taxonomy_id)->safeguard);
        $safeguardIsVerified = $safeguard['verified'] ?? null;
        $contributeIsVerified = $contribute['verified'] ?? null;
        $npsIsVerified = $dnsh['verified'] ?? null;
        if ($contributeIsVerified === 1 && $safeguardIsVerified === 1) {
            if (!$this->hasNps || ($this->hasNps && $npsIsVerified === 1)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the NPS is answered
     */
    public function getNpsIsAnsweredAttribute()
    {
        $contribute = parseStringToArray($this->contribute);
        $dnsh = parseStringToArray($this->dnsh);

        // If dont have dnsh , return true
        if (empty($dnsh['data']) || (isset($dnsh['imported']) && $dnsh['imported'] === 1)) {
            return true;
        }

        $contributeIsVerified = $contribute['verified'] ?? null;
        // If the contribute is not aligned , we dont need to asnwer the dnsh
        if ($contributeIsVerified === 0) {
            return true;
        }

        foreach ($dnsh['data'] as $objective) {
            if ($objective['verified'] === 0 || $objective['verified'] === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the activity status attribute
     */
    public function getActivityReportStatusAttribute()
    {
        if ($this->activityIsAligned) {
            return __('Eligible and aligned');
        }
        return __('Eligible and not aligned');
        // return __('Not Eligible');
    }

    /**
     * Get the activity status attribute
     */
    public function getGetReportTableIndexAttribute()
    {
        if ($this->activityIsAligned) {
            return 1;
        }
        return 2;
    }

    /**
     * Get the activity status color attribute
     */
    public function getActivityReportStatusColorAttribute()
    {
        switch ($this->activity_report_status) {
            case __('Not Eligible'):
                return 'bg-[#e3253e]';
            case __('Eligible and aligned'):
                return 'bg-[#008131]';
            case __('Eligible and not aligned'):
                return 'bg-[#dda83a]';
        }
        return 'bg-esg5';
    }

    /**
     * Generate the array of questions for the activity.
     */
    public function createContributeAndNps(bool $toSave = true)
    {
        $defaultArray = self::defaultQuestionsArray();
        $questions = self::getQuestions($this->code);
        $return = [];

        $defaultArray['hasPercentage'] = 1;
        $defaultArray['data'] = $questions['cs'];
        $return['cs'] = $defaultArray;

        $defaultArray['hasPercentage'] = 0;
        $defaultArray['data'] = $questions['dnsh'];
        $return['dnsh'] = $defaultArray;

        if ($toSave) {
            $this->contribute = $return['cs'];
            $this->dnsh = $return['dnsh'];
            $this->save();
            return;
        }
        return $return;
    }

    /**
     * Reset the contribute substantial
     */
    public function resetContributeSubstantial($questions = [])
    {
        $defaultArray = self::defaultQuestionsArray();
        $defaultArray['hasPercentage'] = 1;
        if (empty($questions)) {
            $questions = self::getQuestions($this->code);
        }

        $defaultArray['data'] = $questions['cs'];
        $this->contribute = $defaultArray;
        $this->save();
    }

    /**
     * Reset the dnsh
     */
    public function resetNps($questions = [])
    {
        $defaultArray = self::defaultQuestionsArray();
        if (empty($questions)) {
            $questions = self::getQuestions($this->code);
        }

        $defaultArray['data'] = $questions['dnsh'];
        $this->dnsh = $defaultArray;
        $this->save();
    }

    /**
     * Get the percentage of the aligneds value from this activiti to the taxonomy
     */
    public function getAlignedTaxonomyPercentageAttribute()
    {
        if (!$this->activityIsAligned) {
            return 0;
        }

        $contribute = $this->filterContributeObjectivesData();
        $percent = 0;
        foreach ($contribute as $objective) {
            if ($objective['verified'] === 1) {
                $percent += $objective['percentage'];
            }
        }
        return $percent;
    }

    /**
     * Get hasNps attribute
     */
    public function getHasNpsAttribute()
    {
        $dnsh = parseStringToArray($this->dnsh);
        return isset($dnsh['data'][0]['name']);
    }

    /**
     * parse the contribute substantial
     * @return array|Collection
     */
    public function filterContributeObjectivesData()
    {
        $contribute = parseStringToArray($this->contribute);
        $contribute = collect($contribute['data'] ?? []);
        return  collect($contribute ?? []);
    }

    /**
     * Filter the objectives that isnt anwered in the contribute substantial
     * @return array|Collection
     */
    public function filterNpsObjectivesData()
    {
        $contribute = parseStringToArray($this->contribute);
        $contribute = collect($contribute['data'] ?? []);

        return collect($this->dnsh['data'] ?? [])->filter(function ($objective) use ($contribute) {
            $return = translateJson($objective['name']) != '';
            $isUnavailableCs = $contribute->filter(function ($cs) use ($objective) {
                return translateJson($cs['name']) == translateJson($objective['name']) && $cs['verified'] === 1;
            })->first();

            if ($isUnavailableCs) {
                $return = false;
            }
            return $return;
        });
    }


    /**
     * Set the result to contribute substantial data and save ( if necessary )
     * @param bool $update
     */
    public function completeContribute($update = true)
    {
        $substantitalContribute = parseStringToArray($this->contribute);
        $data = parseStringToArray($substantitalContribute['data'] ?? '');
        $isElegible = 0;

        foreach ($data as $d) {
            if ($d['percentage'] > 0 && $d['verified'] === 1) {
                $isElegible = 1;
            }
        }

        $substantitalContribute['verified'] = $isElegible;
        $this->contribute = $substantitalContribute;
        if ($update) {
            $this->updated_at = now();
            $this->update();
        }
        return $substantitalContribute;
    }

    /**
     * Set the result to NPS data and save ( if necessary )
     */
    public function completeNps($update = true)
    {
        $npsObjectives = $this->filterNpsObjectivesData();
        $arr = parseStringToArray($this->dnsh);
        $isElegible = null;
        $hasUnasnwered = false;
        $hasNotElegible = false;

        foreach ($npsObjectives as $objective) {
            if ($objective['verified'] === 0) {
                $hasNotElegible = true;
            } elseif ($objective['verified'] === null) {
                $hasUnasnwered = true; //unused for now
            } elseif ($objective['verified'] === 1) {
                $isElegible = 1;
            }
        }

        if ($hasNotElegible) {
            $isElegible = 0;
        }

        $arr['verified'] = $isElegible;
        $this->dnsh = $arr;

        if ($update) {
            $this->updated_at = now();
            $this->update();
        }

        return $arr;
    }

    /**
     * Reset the summary for DNSH and contribute
     */
    public function resetDNSHAndContributeSummary()
    {

        $npsContribute = $this->createContributeAndNps(false);
        $resume = $this->summary;
        foreach ($npsContribute['cs']['data'] as $item) {
            $resume['contribute']['objectives'][AcronymForObjectives::fromValue($item['name'][auth()->user()->locale ?? app()->getLocale()])] = self::getDefaultObjetiveArray();
        }
        foreach ($npsContribute['dnsh']['data'] as $item) {
            $resume['dnsh']['objectives'][AcronymForObjectives::fromValue($item['name'][auth()->user()->locale ?? app()->getLocale()])] = self::getDefaultObjetiveArray();
        }
        $this->summary = $resume;
        $this->save();
    }

    /**
     * Retrieve user type » self managed
     * @return Attribute
     */
    public function identifierLabel(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => "{$this->name} (ID: {$this->taxonomy->questionnaire_id}-{$this->identifier})",
        );
    }
}
