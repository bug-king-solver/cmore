<?php

namespace App\Http\Livewire\Companies\Modals\Ratios;

use App\Models\Enums\Regulamentation\AssetTypeEnum;
use App\Models\Enums\Taxonomy\AcronymForObjectives;
use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\Taxonomy\Taxonomy;
use App\Models\Tenant\Questionnaires\Taxonomy\TaxonomyActivities;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Validator;
use LivewireUI\Modal\ModalComponent;

class BaseAsset extends ModalComponent
{
    use AuthorizesRequests;

    public array $assetTypeList;

    public $taxonomyList;

    public $activityList;

    public $company;

    public Taxonomy | int | null $taxonomy;

    public TaxonomyActivities | int | null $taxonomyActivity;

    public bool $isSimulation;

    public $questionnaireList;

    public string $assetType;

    public string $amount;

    public string $flow;

    public bool $fillMode;

    public bool | null $purpose;

    public bool | null $eligible;

    public bool | null $aligned;

    public string | null $ccm;

    public string | null $cca;

    public string | null $activity;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function rules()
    {
        return [
            'amount' => 'numeric|required',
            'flow' => 'numeric|required',
            'assetType' => 'required',
            'fillMode' => 'required',
            'purpose' => 'required_with:fillMode|exclude_if:assetType,3',
            'taxonomy' => 'exclude_if:fillMode,false|required_if:purpose,true',
            'taxonomyActivity' => 'required_with:taxonomy',
            'eligible' => 'required_with:purpose|required_if:assetType,3',
            'aligned' => 'required_with:purpose|required_if:assetType,3',
            'ccm' => 'required_with:aligned|numeric|min:0|max:100',
            'cca' => 'required_with:aligned|numeric|min:0|max:100',
            'activity' => 'required_with:aligned',
        ];
    }

    public function messages()
    {
        return [
            'eligible.required_if' => __('The eligible field is required'),
            'aligned.required_if' => __('The aligned field is required'),
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function loadBaseData($assetType, $company)
    {
        $this->company = $company;

        $this->assetTypeList = AssetTypeEnum::toArray();
        // filter to use only the key 1,2 and 3
        $this->assetTypeList = collect($this->assetTypeList)->filter(function ($value, $key) use ($assetType) {
            return ($key < 4) || ($assetType !== null && $assetType === $key);
        })->toArray();

        $this->assetTypeList = parseKeyValueForSelect($this->assetTypeList, 'id', 'name');

        $this->questionnaireList = Questionnaire::whereNotNull('submitted_at')
            ->where('company_id', $this->company->id)
            ->where('questionnaire_type_id', 10)
            ->orderBy('submitted_at', 'DESC')
            ->get();

        $this->activityList = [];
        $this->taxonomyList = [];

        foreach ($this->questionnaireList as $questionnaire) {
            $title = "{$questionnaire->id} - {$questionnaire->company->name}";
            $title .= " ({$questionnaire->from->format('Y-m-d')} - {$questionnaire->to->format('Y-m-d')})";
            $this->taxonomyList[] = [
                'id' => $questionnaire->taxonomy->id,
                'title' => $title,
            ];
        }

        if (count($this->taxonomyList) > 0) {
            $this->fillMode = true;
        } else {
            $this->fillMode = false;
        }

        if ($this->asset->id) {
            $this->amount = $this->asset[BankAssets::TOTAL_VALUE];
            $this->flow = $this->asset[BankAssets::CHANGE_IN_THE_FINANCIAL_YEAR] ?? 0;
            $this->assetType = $this->asset[BankAssets::TYPE];
            $this->purpose = (isset($this->asset[BankAssets::SPECIFIC_PURPOSE]))
                ? $this->asset[BankAssets::SPECIFIC_PURPOSE] === BankAssets::YES
                : null;
            $this->eligible = isset($this->asset[BankAssets::SIMULATION][BankAssets::ELIGIBLE])
                ? $this->asset[BankAssets::SIMULATION][BankAssets::ELIGIBLE] === BankAssets::YES
                : null;
            $this->aligned = isset($this->asset[BankAssets::SIMULATION][BankAssets::ALIGNED])
                ? $this->asset[BankAssets::SIMULATION][BankAssets::ALIGNED] === BankAssets::YES
                : null;
            $this->ccm = $this->asset[BankAssets::VN_CCM_ELIGIBLE] * 100;
            $this->cca = $this->asset[BankAssets::VN_CCA_ELIGIBLE] * 100;
            $this->activity = $this->asset[BankAssets::SIMULATION][BankAssets::ACTIVITY] ?? null;
            if ($this->asset[BankAssets::SIMULATION][BankAssets::REAL] ?? false) {
                $this->fillMode = false;
            } else {
                $this->fillMode = $this->asset[BankAssets::SIMULATION][BankAssets::FILL_MODE] ?? false;
                $this->taxonomy = $this->asset[BankAssets::SIMULATION][BankAssets::TAXONOMY] ?? null;
                if (isset($this->taxonomy)) {
                    $this->loadTaxonomyActivities($this->taxonomy);
                    $this->taxonomyActivity = $this->asset[BankAssets::SIMULATION][BankAssets::TAXONOMY_ACTIVITY] ?? null;
                }
            }
            if ($this->assetType == '3') {
                $this->purpose = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.tenant.companies.modals.ratios.baseasset');
    }

    public function updating()
    {
        $this->resetErrorBag();
    }

    public function updatingAssetType($value)
    {
        $this->purpose = null;
        if ($value === '3') {
            $this->purpose = true;
            $this->ccm = null;
            $this->cca = null;
            $this->eligible = null;
            $this->aligned = null;
            $this->activity = null;
            $this->taxonomy = null;
            $this->taxonomyActivity = null;
            if ($this->fillMode) {
                $this->loadTaxonomies();
            }
        }
    }

    public function updatingfillMode($value)
    {
        $this->ccm = null;
        $this->cca = null;
        $this->eligible = null;
        $this->aligned = null;
        $this->activity = null;
        $this->taxonomy = null;
        $this->taxonomyActivity = null;
        if (isset($this->assetType) && $this->assetType === '3') {
            $this->purpose = true;
            if ($value == '1') {
                $this->loadTaxonomies();
            }
        } else {
            $this->purpose = null;
        }
    }

    public function updatingPurpose($value)
    {
        $this->ccm = null;
        $this->cca = null;
        $this->eligible = null;
        $this->aligned = null;
        $this->activity = null;
        $this->taxonomy = null;
        $this->taxonomyActivity = null;

        if (!$value && $this->fillMode) {
            $this->eligible = true;
            $this->aligned = true;
            $this->ccm = 0;
            $this->cca = 0;
        }

        $this->taxonomy = null;
        $this->taxonomyActivity = null;

        if ($value == "1" && $this->fillMode) {
            $this->loadTaxonomies();
        }
    }

    private function loadTaxonomies()
    {
        $this->taxonomy = array_first($this->taxonomyList)['id'] ?? null;
        $this->updatingTaxonomy($this->taxonomy);
        $this->taxonomyActivity = array_first($this->activityList)['id'] ?? null;
        $this->updatingTaxonomyActivity($this->taxonomyActivity);
    }

    private function loadTaxonomyActivities($value)
    {
        $activityList = TaxonomyActivities::where('taxonomy_id', $value)->get();
        $this->activityList = [];
        foreach ($activityList as $activity) {
            $title = "{$activity->name} (ID: {$activity->taxonomy->questionnaire_id}-{$activity->identifier})";
            $this->activityList[] = [
                'id' => $activity->id,
                'title' => $title,
            ];
        }
    }

    /**
     * Handle updating of taxonomy field.
     *
     * @param mixed $value
     * @return void
     */
    public function updatingTaxonomy(int|null $value)
    {
        $this->taxonomyActivity = null;
        $this->loadTaxonomyActivities($value);
    }

    /**
     * Handle updating of taxonomy activity field.
     *
     * @param mixed $value
     * @return void
     */
    public function updatingTaxonomyActivity(int|null $value)
    {
        if (!isset($value)) {
            return;
        }
        $activityInfo = TaxonomyActivities::where('id', $value)->first();
        $data = $activityInfo->summary;
        $value = "not-applicable";
        $acronymObjetiveCCM = AcronymForObjectives::fromValue('Mitigação das alterações climáticas');
        $acronymObjetiveCCA = AcronymForObjectives::fromValue('Adaptação às alterações climáticas');
        $this->ccm = $data['contribute']['objectives'][$acronymObjetiveCCM]['percentage'];
        $this->cca = $data['contribute']['objectives'][$acronymObjetiveCCA]['percentage'];
        if (isset($data['contribute']['objectives'][$acronymObjetiveCCM]['transition_enabling']) || isset($data['contribute']['objectives'][$acronymObjetiveCCA]['transition_enabling'])) {
            $transition_enabling = "";
            if (isset($data['contribute']['objectives'][$acronymObjetiveCCM]['transition_enabling'])) {
                $transition_enabling = $data['contribute']['objectives'][$acronymObjetiveCCM]['transition_enabling'];
            } else if (isset($data['contribute']['objectives'][$acronymObjetiveCCA]['transition_enabling'])) {
                $transition_enabling = $data['contribute']['objectives'][$acronymObjetiveCCA]['transition_enabling'];
            }
            if ($transition_enabling === "C") {
                $value = "enabling";
            } elseif ($transition_enabling === "T") {
                $value = "transitional";
            }
        } else {
            if ($data['contribute']['objectives'][$acronymObjetiveCCM]['enabling']['percentage'] > 0) {
                $value = "enabling";
            } elseif ($data['contribute']['objectives'][$acronymObjetiveCCM]['transition']['percentage'] > 0) {
                $value = "transitional";
            } else if ($data['contribute']['objectives'][$acronymObjetiveCCA]['enabling']['percentage'] > 0) {
                $value = "enabling";
            } elseif ($data['contribute']['objectives'][$acronymObjetiveCCA]['transition']['percentage'] > 0) {
                $value = "transitional";
            }
        }
        $this->eligible = true;
        $this->aligned = true;
        $this->activity = $value;
    }

    public function saveBase($asset, $simulation = null, $bank = false)
    {
        $data = $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {
                $ccm = $validator->getData()['ccm'];
                $cca = $validator->getData()['cca'];
                if (!empty($ccm) && !empty($cca)) {
                    if (($ccm + $cca) > 100) {
                        $validator->errors()->add('ccm_cca', __('The sum of CCA and CCM should be equal or lower than 100'));
                    }
                }
            });
        })->validate();

        if (isset($simulation)) {
            $asset->bank_simulation_id = $simulation->id;
        }

        $data['eligible'] = $data['eligible'] ?? $this->eligible ?? null;
        $data['aligned'] = $data['aligned'] ?? $this->aligned ?? null;

        $data['cca'] = $data['cca'] ?? $this->cca ?? null;
        $data['ccm'] = $data['ccm'] ?? $this->ccm ?? null;

        $data['activity'] = $data['activity'] ?? $this->activity ?? null;

        $asset[BankAssets::SIMULATION] = [
            BankAssets::REAL => false,
            BankAssets::BANK => $bank,
            BankAssets::FILL_MODE => $this->fillMode,
            BankAssets::TAXONOMY => $this->taxonomy ?? null,
            BankAssets::TAXONOMY_ACTIVITY => $this->taxonomyActivity ?? null,
            BankAssets::ACTIVITY => $data['activity'] ?? null,
            BankAssets::ELIGIBLE => $data['eligible'] ? BankAssets::YES : BankAssets::NO,
            BankAssets::ALIGNED => $data['aligned'] ? BankAssets::YES : BankAssets::NO,
        ];

        $asset[BankAssets::NIPC] = $this->company->vat_number;
        $asset[BankAssets::TYPE] = (int)$data['assetType'];
        $asset[BankAssets::TOTAL_VALUE] = (float)$data['amount'];
        $asset[BankAssets::NACE_CODE] = null;
        $asset[BankAssets::SPECIFIC_PURPOSE] = isset($data['purpose']) ? ($data['purpose'] ? BankAssets::YES : BankAssets::NO) : null;

        $asset[BankAssets::NAME_COMPANY] = $this->company->name;

        $asset[BankAssets::SUBJECT_NFDR] = BankAssets::YES;
        $asset[BankAssets::EUROPEAN_COMPANY] = BankAssets::YES; // Check this value
        $asset[BankAssets::ENTITY_TYPE] = 1; // Check this value

        $asset[BankAssets::CHANGE_IN_THE_FINANCIAL_YEAR] = (float)$data['flow'];

        $indexKPI = ['VN', 'CAPEX', 'OPEX'];

        foreach ($indexKPI as $value) {
            $asset[constant(BankAssets::class . "::{$value}_CCA_ELIGIBLE")] = null;
            $asset[constant(BankAssets::class . "::{$value}_CCA_ALIGNED")] = null;
            $asset[constant(BankAssets::class . "::{$value}_CCA_ADAPTING")] = null;
            $asset[constant(BankAssets::class . "::{$value}_CCA_ENABLING")] = null;
            $asset[constant(BankAssets::class . "::{$value}_CCM_ELIGIBLE")] = null;
            $asset[constant(BankAssets::class . "::{$value}_CCM_ALIGNED")] = null;
            $asset[constant(BankAssets::class . "::{$value}_CCM_TRANSITIONAL")] = null;
            $asset[constant(BankAssets::class . "::{$value}_CCM_ENABLING")] = null;
        }

        $cca = calculateDivision($data['cca'], 100);
        $ccm = calculateDivision($data['ccm'], 100);

        $valuesCCMCCA = [
            'ccm' => [
                $indexKPI[0] => $ccm,
                $indexKPI[1] => $ccm,
                $indexKPI[2] => $ccm,
            ],
            'cca' => [
                $indexKPI[0] => $cca,
                $indexKPI[1] => $cca,
                $indexKPI[2] => $cca,
            ]
        ];

        if ($this->fillMode && !$this->purpose) {
            $taxonomy = collect($this->questionnaireList)->first()->taxonomy;
            $valuesCCMCCA = $taxonomy->summary;
            $acronymObjetiveCCM = AcronymForObjectives::fromValue('Mitigação das alterações climáticas');
            $acronymObjetiveCCA = AcronymForObjectives::fromValue('Adaptação às alterações climáticas');
            if (isset($valuesCCMCCA['contribute']['objectives'])) {
                $valuesCCMCCA = [
                    'ccm' => [
                        $indexKPI[0] => ($valuesCCMCCA['contribute']['objectives'][$acronymObjetiveCCM]['volume']['percentage'] / 100),
                        $indexKPI[1] => ($valuesCCMCCA['contribute']['objectives'][$acronymObjetiveCCM]['capex']['percentage'] / 100),
                        $indexKPI[2] => ($valuesCCMCCA['contribute']['objectives'][$acronymObjetiveCCM]['opex']['percentage'] / 100),
                    ],
                    'cca' => [
                        $indexKPI[0] => ($valuesCCMCCA['contribute']['objectives'][$acronymObjetiveCCA]['volume']['percentage'] / 100),
                        $indexKPI[1] => ($valuesCCMCCA['contribute']['objectives'][$acronymObjetiveCCA]['capex']['percentage'] / 100),
                        $indexKPI[2] => ($valuesCCMCCA['contribute']['objectives'][$acronymObjetiveCCA]['opex']['percentage'] / 100),
                    ]
                ];
            }
        }

        foreach ($indexKPI as $value) {
            $asset[constant(BankAssets::class . "::{$value}_CCA_ELIGIBLE")] = $valuesCCMCCA['cca'][$value];
            $asset[constant(BankAssets::class . "::{$value}_CCA_ALIGNED")] = $valuesCCMCCA['cca'][$value];
            $asset[constant(BankAssets::class . "::{$value}_CCM_ELIGIBLE")] = $valuesCCMCCA['ccm'][$value];
            $asset[constant(BankAssets::class . "::{$value}_CCM_ALIGNED")] = $valuesCCMCCA['ccm'][$value];
            if ($data['activity'] === 'enabling') {
                $asset[constant(BankAssets::class . "::{$value}_CCA_ADAPTING")] = 0;
                $asset[constant(BankAssets::class . "::{$value}_CCA_ENABLING")] = $valuesCCMCCA['cca'][$value];
                $asset[constant(BankAssets::class . "::{$value}_CCM_TRANSITIONAL")] = 0;
                $asset[constant(BankAssets::class . "::{$value}_CCM_ENABLING")] = $valuesCCMCCA['ccm'][$value];
            } elseif ($data['activity'] === 'transitional') {
                $asset[constant(BankAssets::class . "::{$value}_CCA_ADAPTING")] = $valuesCCMCCA['cca'][$value];
                $asset[constant(BankAssets::class . "::{$value}_CCA_ENABLING")] = 0;
                $asset[constant(BankAssets::class . "::{$value}_CCM_TRANSITIONAL")] = $valuesCCMCCA['ccm'][$value];
                $asset[constant(BankAssets::class . "::{$value}_CCM_ENABLING")] = 0;
            } else {
                $asset[constant(BankAssets::class . "::{$value}_CCA_ADAPTING")] = 0;
                $asset[constant(BankAssets::class . "::{$value}_CCA_ENABLING")] = 0;
                $asset[constant(BankAssets::class . "::{$value}_CCM_TRANSITIONAL")] = 0;
                $asset[constant(BankAssets::class . "::{$value}_CCM_ENABLING")] = 0;
            }
        }

        $asset->save();
        $this->closeModal();
        $this->emit('companyRefresh');
        $this->emit('refreshComponent');
    }
}
