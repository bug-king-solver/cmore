<?php

namespace App\Http\Livewire\Companies;

use App\Events\CreatedCompany;
use App\Http\Livewire\Traits\BreadcrumbsTrait;
use App\Http\Livewire\Traits\CompanyAddressTrait;
use App\Http\Livewire\Traits\HasCustomColumns;
use App\Http\Livewire\Traits\HasProductItem;
use App\Http\Livewire\Traits\TabsTrait;
use App\Models\Enums\Companies\Relation;
use App\Models\Enums\Companies\Type;
use App\Models\Enums\CompanySize;
use App\Models\Enums\CompanyTypeEnum;
use App\Models\Tenant\BusinessSectorType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\ReportingPeriod;
use App\Models\Tenant\Tag;
use App\Models\User;
use App\Services\ThinkHazard\ThinkHazard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Form extends Component
{
    use AuthorizesRequests;
    use HasCustomColumns;
    use HasProductItem;
    use WithPagination;

    use CompanyAddressTrait {
        rulesCompanyAddressTrait as traitRules;
    }

    use TabsTrait;
    use BreadcrumbsTrait;

    protected $feature = 'companies';

    public $countriesList;

    public $countriesIdsList;

    public $businessSectorsLists;

    public $companySizes;

    public Company | int $company;

    public $name;

    public $commercial_name;

    public $type;

    public $typesList = [];

    public string | array $relation = [];

    public $relationsList = [];

    public $parent;

    public $business_sector;

    public $country;

    public $vat_country;

    public $vat_number;

    public $vat_secundary_data = [];

    public $vat_secundary;

    public $size;

    public $founded_at;

    public $color;

    public $userablesList;

    public $userablesId = [];

    public $companiesList;

    public $businessSectorsList;

    public $companySizesList;

    public $taggableList;

    public $taggableIds = [];

    public $ownerUserList = [];

    public $createdByUserId;

    public $isOwner = false;

    public $businessSectorSecondary = [];

    public $hiddenColumns = [];

    public $companyFlow;

    public $currentStep = 0;

    public $questionnaire;

    public $locations = [];

    public $hazardCountriesList;

    protected $listeners = [
        'flowUpdated' => '$refresh',
        'nextStep' => 'nextStep',
        'previousStep' => 'previousStep'
    ];

    protected function rules()
    {
        $this->hiddenColumns = Company::hiddenColumns();
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'commercial_name' => ['nullable', 'max:255'],
            'parent' => ['nullable', 'exists:companies,id'],
            'business_sector' => ['required', 'exists:business_sectors,id'],
            'country' => ['required', Rule::in($this->countriesIdsList)],
            'vat_country' => ['required', Rule::in($this->countriesIdsList)],
            'vat_number' => ['required', 'max:255'],
            'vat_secundary' => ['nullable'],
            'size' => ['required', Rule::in(CompanySize::casesArray('value'))],
            'founded_at' => ['nullable', 'date', 'after:1850-01-01', 'before:' . now()->format('Y-m-d')],
            'relation.*' => [
                'nullable',
                Rule::in(Relation::keys()),
            ],
            'color' => ['nullable'],
            'createdByUserId' => ['nullable', 'exists:users,id'],
        ];

        if (tenant()->usersTypeSelfManaged && auth()->user()->is_internal_user) {
            $rules['type'] = ['required', Rule::in(Type::keys())];
        }

        $rules = $this->mergeCustomRules($rules);

        // if any rule is present in hidden columns, remove it
        foreach ($this->hiddenColumns as $column) {
            if (isset($rules[$column])) {
                unset($rules[$column]);
            }
        }

        return array_merge(
            $this->traitRules(),
            $rules,
        );
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'vat_number.integer' => __('This field only accepts integer numbers'),
            'locations.*.name.required' => __('Name field is required'),
            'locations.*.country_code.required' => __('Country field is required'),
            'locations.*.region_code.required' => __('Region field is required'),
            'locations.*.city_code.required' => __('City field is required'),
            'locations.*.latitude' => __('Latitude field is required'),
            'locations.*.longitude' => __('Longitude field is required'),
        ];
    }

    /**
     *
     */
    public function mount(?Company $company)
    {
        $this->tabList = [
            [
                'label' => __('General'),
                'slug' => str_replace(" ", "_", strtolower('General')),
                'active' => true
            ],
            [
                'label' => __('Location'),
                'slug' => str_replace(" ", "_", strtolower('Location'))
            ]
        ];

        $hazard = new ThinkHazard();
        $this->hazardCountriesList = $hazard->getCountries();
        $this->countryList = parseDataForSelect(
            $this->hazardCountriesList,
            'country_code',
            'country_name'
        );

        $this->company = $company;
        $this->resource = $company;

        $this->hiddenColumns = Company::hiddenColumns();

        tenant()->see_only_own_data || $this->authorize(
            !$this->company->exists
                ? 'companies.create'
                : "companies.update.{$this->company->id}"
        );

        $this->countriesList = getCountriesForSelect();
        $this->countriesIdsList = array_column($this->countriesList, 'id');

        $users = User::list(auth()->user()->id)->get();


        $this->userablesList = parseDataForSelect($users, 'id', 'name', 'avatar');

        $this->ownerUserList = parseDataForSelect($users, 'id', 'name');

        $this->companiesList = parseDataForSelect(Company::list()->get(), 'id', 'name');

        $this->businessSectorsList = getBusinessSectorsForSelect();
        $this->typesList = parseKeyValueForSelect(Type::casesArray());
        $this->relationsList = parseKeyValueForSelect(Relation::casesArray());
        $this->companySizesList = parseKeyValueForSelect(CompanySize::casesArray());
        $this->taggableList = getTagsForSelect();

        if ($this->company->exists) {
            $this->name = $this->company->name;
            $this->commercial_name = $this->company->commercial_name;
            $this->type = $this->company->type->value ?? [];
            $this->relation = $this->company->relation ?? [];
            $this->parent = $this->company->parent_id;
            $this->business_sector = $this->company->business_sector_id;
            $this->country = $this->company->country;
            $this->vat_country = $this->company->vat_country;
            $this->vat_number = $this->company->vat_number;
            $this->size = $this->company->size;
            $this->founded_at = $this->company->founded_at
                ? $this->company->founded_at->format('Y-m-d')
                : null;
            $this->color = $this->company->color;
            $this->createdByUserId = $this->company->created_by_user_id;

            $this->vat_secundary_data = $this->company->vat_secundary;
            $this->vat_secundary = array_keys($this->vat_secundary_data ?? []);

            if (auth()->user()->id === $this->createdByUserId) {
                $this->isOwner = true;
                $this->ownerUserList = array_merge(
                    $this->ownerUserList,
                    [[
                        'id' => $this->createdByUserId,
                        'title' => auth()->user()->name,
                    ]]
                );
            }

            $this->locations = $this->company->locations
                ? $this->company->locations->toArray()
                : [];

            if (!empty($this->locations)) {
                foreach ($this->locations as $key => $row) {
                    if ($row['country_code'] != null) {
                        $this->updateRegion($row['country_code'], $key);
                        $this->updateCity($row['region_code'], $key);
                    }
                }
            } else {
                $this->addNewAddressOptions();
            }


            $this->userablesId = $this->company->users
                ? $this->company->users->pluck('id', null)->toArray()
                : [];

            $this->customColumnsData = $this->company->only($this->customColumnsIds);
            $this->taggableIds = $this->company->tags
                ? $this->company->tags->pluck('id', null)->toArray()
                : [];
            $this->businessSectorSecondary = $this->company->businessSectorSecondary
                ? $this->company->businessSectorSecondary->pluck('id', null)->toArray()
                : [];
        } else {
            $this->addNewAddressOptions();
        }

        $this->addBreadcrumb(__('Companies'), route('tenant.companies.index'));

        if ($this->company->exists) {
            $this->addBreadcrumb($this->company->name);
        }
    }

    /**
     * @return View
     */
    public function render(): View
    {
        $flow = (tenant()->hasCreatingfeature['flow'] ?? null) && (($this->company->flow ?? null) || !$this->company->exists);

        if ($flow) {
            $this->companyFlow = $this->company->flow ?? Company::defaultFlowStructure();
            $this->currentStep = $this->companyFlow['current'] ?? 0;
        }

        $currentStep = $this->companyFlow['steps'][$this->currentStep] ?? null;
        if (isset($currentStep['step'])) {
            if (isset($currentStep['questionnaire_created_id']) && $currentStep['questionnaire_created_id'] !== null) {
                $this->questionnaire  = Questionnaire::find($currentStep['questionnaire_created_id']);
            }

            if (($this->companyFlow['done'] ?? false) !== true) {
                return view('livewire.tenant.companies.flow-form');
            }
        }

        return view('livewire.tenant.companies.form');
    }

    /**
     * Global livewire update event.
     * @param $propertyName
     * @return void
     */
    public function updating($propertyName): void
    {
        $this->resetErrorBag($propertyName);
    }

    public function updatingVatSecundary($value, $key)
    {
        foreach ($value as $i => $val) {
            // check if $val exists on vat secundary data id , and if yes , unset it
            $arr = array_filter($this->vat_secundary_data ?? [], function ($item) use ($val) {
                return $item['id'] == $val;
            });

            if (!empty($arr)) {
                unset($value[$i]);
            }
        }

        $arr = array_merge($this->vat_secundary_data, parseKeyValueForSelect($value));
        $this->vat_secundary_data = $this->fixOrderAndIdFromSecundaryVat($arr);
    }

    /**
     * @param $value
     */
    public function updatedVatSecundary($value, $key)
    {
        //set the vat_secundary as array  where the $value is on title from $arr
        $this->vat_secundary = array_map(function ($item) use ($value) {
            return in_array($item['title'], $value)
                ? $item['id']
                : (in_array($item['id'], $value)
                    ? $item['id']
                    : null);
        }, $this->vat_secundary_data);

        // remove null values but keep zero value
        foreach ($this->vat_secundary ?? [] as $key => $value) {
            if ($value === null) {
                unset($this->vat_secundary[$key]);
            }
        }
    }

    public function updatedType($value)
    {
        if ($value !== Type::EXTERNAL) {
            $this->relation = [];
            $this->emit('resetInputField');
        }
    }

    public function fixOrderAndIdFromSecundaryVat($array)
    {
        $result = [];
        foreach ($array as $item) {
            $result[$item['title']] = $item;
        }

        $result = array_values($result);
        foreach ($result as $key => $item) {
            $result[$key]['id'] = $key;
        }

        return $result;
    }

    /**
     * Save the company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save($redirect = true)
    {
        $this->rules();

        tenant()->see_only_own_data || $this->authorize(
            !$this->company->exists
                ? 'companies.create'
                : "companies.update.{$this->company->id}"
        );

        $newVatSecundary = [];
        $vatSecundaryBadPattern = [];


        foreach ($this->vat_secundary ?? [] as $key => $vat) {
            $number = array_filter($this->vat_secundary_data, function ($item) use ($vat) {
                return $item['id'] == $vat;
            });

            $number = $number[array_key_first($number)] ?? null;
            if (!$number) {
                continue;
            }

            // check if containe especial characters, if do , fire error
            $hasSpecialCharacteres = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $number['title']);
            $patternIsValid = preg_match('/^([a-z]{2})[0-9]+$/i', $number['title']);

            if ($patternIsValid === 0 || $hasSpecialCharacteres === 1) {
                unset($this->vat_secundary_data[$key]); // remove from the vat list, the wrong values
                $vatSecundaryBadPattern[] = $number['title'];
            } else {
                $newVatSecundary[] = $number;
            }
        }

        if (!empty($vatSecundaryBadPattern)) {
            $this->vat_secundary = []; // reset the selected secundary vats
            $this->vat_secundary_data = $this->fixOrderAndIdFromSecundaryVat($this->vat_secundary_data);

            $this->addError('vat_secundary', __("The following VAT numbers are not valid: :numbers", [
                'numbers' => implode(', ', $vatSecundaryBadPattern),
            ]));
            return false;
        }

        $data = $this->validate();
        $data['vat_secundary'] = $newVatSecundary;

        $assigner = auth()->user();

        $data['parent_id'] = $data['parent'] ?? null;
        $data['business_sector_id'] = $data['business_sector'] ?? null;

        $data['founded_at'] = !empty($data['founded_at'])
            ? $data['founded_at']
            : null;

        $data = $this->addCustomData($data);

        if ($data['createdByUserId']) {
            $data['created_by_user_id'] = $data['createdByUserId'];
        }

        if (!$this->company->exists) {
            $data['created_by_user_id'] = auth()->user()->id;
            $this->company = Company::create($data);

            foreach ($this->locations as $key => $value) {
                if ($value != '') {
                    $this->company->locations()->create([
                        'name' => $value['name'],
                        'headquarters' => $value['headquarters'] ?? 0,
                        'country_code' => $value['country_code'],
                        'region_code' => $value['region_code'],
                        'city_code' => $value['city_code'],
                        'latitude' => $value['latitude'] ?? null,
                        'longitude' => $value['longitude'] ?? null,
                    ]);
                }
            }

            event(new CreatedCompany(auth()->user(), $this->company));
        } else {
            $this->company->update($data);

            $currentLocations = $this->company->locations()->get()->toArray();
            $locationsDeleted = array_diff_key($currentLocations, $this->locations);

            foreach ($locationsDeleted as $key => $value) {
                $check = $this->company->locations()->find($value['id'] ?? null);
                if ($check) {
                    $check->delete();
                }
            }

            foreach ($this->locations as $key => $value) {
                if ($value != '') {
                    $this->company->locations()->updateOrCreate(
                        [
                            'id' => $value['id'] ?? null,
                        ],
                        [
                            'name' => $value['name'],
                            'headquarters' => $value['headquarters'] ?? 0,
                            'country_code' => $value['country_code'],
                            'region_code' => $value['region_code'],
                            'city_code' => $value['city_code'],
                            'latitude' => $value['latitude'] ?? null,
                            'longitude' => $value['longitude'] ?? null,
                        ]
                    );
                }
            }
        }

        $this->company->assignUsers($this->userablesId, $assigner);
        $this->company->assignTags($this->taggableIds, $assigner);
        $this->company->businessSectorSecondary()->sync($this->businessSectorSecondary);

        // If is the first compant of the user, redirect to the introduction page
        if ($redirect) {
            if (tenant()->is_product_type_pro && $this->company->wasRecentlyCreated) {
                return redirect()->route('tenant.companies.welcome', [
                    'name' => $this->company->name,
                ]);
            }

            return redirect(route('tenant.companies.index'));
        }

        return true;
    }

    public function nextStep()
    {
        $flow = $this->company->flow ?? $this->companyFlow;

        $currentStep = $this->currentStep;
        $nextStep = $currentStep + 1;

        if ($currentStep == 0) {
            $wasSaved = $this->save(false);
            if (!$wasSaved) {
                return;
            }
        }

        if (isset($flow['steps'][$currentStep])) {
            if ($flow['steps'][$currentStep]['type'] == 'questionnaire') {
                if ($flow['steps'][$currentStep]['questionnaire_created_id'] != null) {
                    $questionnaire = Questionnaire::find($flow['steps'][$this->currentStep]['questionnaire_created_id']);
                    if ($questionnaire && $questionnaire->submitted_at == null) {
                        $questionnaire->submit();
                    }
                }
            }
        }

        if (isset($flow['steps'][$nextStep])) {
            if ($flow['steps'][$nextStep]['type'] == 'questionnaire') {
                if ($flow['steps'][$nextStep]['questionnaire_created_id'] == null) {
                    $questionnaire = Questionnaire::create([
                        'is_ready' => false,
                        'from' => carbon()->startOfYear(),
                        'to' => carbon()->endOfYear(),
                        'countries' => [$this->company->country],
                        'questionnaire_type_id' => $flow['steps'][$nextStep]['questionnaire_type_id'],
                        'created_by_user_id' => auth()->user()->id,
                        'company_id' => $this->company->id,
                        'reporting_period_id' => ReportingPeriod::first()->id,
                        'data' => [
                            'created_from_company' => $this->company->id,
                            'create_async' => true,
                        ]
                    ]);

                    $questionnaire->assignUsers([], $questionnaire->createdBy);
                    $flow['steps'][(int)$nextStep]["questionnaire_created_id"] = $questionnaire->id;
                }
            }
            if ($flow['steps'][$nextStep]['type'] == 'dashboard') {
                if ($flow['steps'][$nextStep]['questionnaire_created_id'] == null) {
                    $flow['steps'][(int)$nextStep]["questionnaire_created_id"] = $flow['steps'][$currentStep]['questionnaire_created_id'];
                }
            }
        }

        if ($flow['total'] === $nextStep) {
            $flow['done'] = true;
        }

        // check fi flow is different from the one saved
        $flow['current'] = $nextStep;

        $this->company->flow = $flow;
        $this->company->update();


        if (isset($flow['done']) && $flow['done']) {
            return redirect()->route('tenant.companies.show', [
                'company' => $this->company->id,
            ]);
        }

        //check if company was create recently
        if ($this->company->wasRecentlyCreated) {
            // return company edit
            return redirect()->route('tenant.companies.form', [
                'company' => $this->company->id,
            ]);
        }

        $this->emit('flowUpdated');
    }

    public function previousStep()
    {
        $flow = $this->company->flow;
        $currentStep = $this->currentStep;
        $flow['current'] = $currentStep - 1 >= 0
            ? $currentStep - 1
            : 0;

        $this->company->flow = $flow;
        $this->company->update();
        $this->emit('flowUpdated');
    }
}
