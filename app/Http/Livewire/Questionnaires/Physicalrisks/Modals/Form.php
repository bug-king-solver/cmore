<?php

namespace App\Http\Livewire\Questionnaires\Physicalrisks\Modals;

use App\Http\Livewire\Traits\CompanyAddressTrait;
use App\Models\Enums\PhysicalRisksRelevanceEnum;
use App\Models\Tenant\BusinessActivities;
use App\Models\Tenant\CompanyAddresses;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Questionnaires\PhysicalRisks\PhysicalRisks;
use App\Services\ThinkHazard\ThinkHazard;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use AuthorizesRequests;

    use CompanyAddressTrait {
        rulesCompanyAddressTrait as traitRules;
    }

    public $questionnaire;

    public $physicalRisks;

    public $locations = [];

    public $name;
    public $sector;
    public $activity;
    public $note;
    public $relevant;
    public $relevanceDescription;

    /** list */
    public $sectorList;
    public $activityList;

    public $hazardCountriesList;

    public $relevantList;

    public $locationList;
    public $locationId = null;

    protected $listeners = [
        'physicalRiskChanges' => '$refresh',
    ];

    /**
     * Get the maximum width of the modal.
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    /**
     * Validation rules.
     */
    public function rules()
    {
        if ($this->physicalRisks->exists) {
            return [
                'name' => 'required',
                'relevant' => 'required',
            ];
        } else {
            return array_merge([
                'name' => 'required',
                'activity' => 'required',
                'relevant' => 'required',
            ], $this->traitRules());
        }
    }

    protected $messages = [
        'locations.*.name' => 'name field is required',
        'locations.*.country' => 'Country field is required',
        'locations.*.region' => 'Region field is required',
        'locations.*.city' => 'City field is required',
        'locations.*.latitude' => 'Latitude field is required.',
        'locations.*.longitude' => 'Longitude field is required.'
    ];

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(Questionnaire $questionnaire, ?PhysicalRisks $physicalRisks)
    {
        $this->questionnaire = $questionnaire;
        $this->physicalRisks = $physicalRisks;

        $hazard = new ThinkHazard();
        $this->hazardCountriesList = $hazard->getCountries();
        $this->countryList = parseDataForSelect(
            $this->hazardCountriesList,
            'country_code',
            'country_name'
        );

        $this->sectorList = getBusinessSectorsForSelect();

        $this->locationList = $this->questionnaire->company->companyAddressAllArray();

        $this->locationList = parseKeyValueForSelect($this->locationList);

        $this->relevantList = parseKeyValueForSelect(
            PhysicalRisksRelevanceEnum::toArray()
        );

        if ($this->physicalRisks->exists) {
            $this->name = $this->physicalRisks->name;
            $this->note = $this->physicalRisks->note;
            $this->relevant = $this->physicalRisks->relevant;
            $this->relevanceDescription = PhysicalRisksRelevanceEnum::from($this->relevant)->description();
        }

        $this->addNewAddressOptions();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return void
     */
    public function render(): View
    {
        if ($this->physicalRisks->exists) {
            return view('livewire.tenant.physicalrisks.modals.form');
        } else {
            return view('livewire.tenant.physicalrisks.modals.newform');
        }
    }

    /**
     * Save the activity.
     *
     * @return void
     */
    public function save()
    {
        if (!$this->physicalRisks->exists) {
            $this->saveData();
        } else {
            $data = $this->validate();

            $this->physicalRisks->update([
                'name' => $data['name'],
                'note' => $this->note,
                'relevant' => $this->relevant
            ]);

            $this->closeModal();
            $this->emit('physicalRiskRefresh');
        }
    }

    /**
     * Save another data
     */
    public function saveAndClear()
    {
        $this->saveData('other');
    }

    /**
     * Save data
     */
    public function saveData($type = 'add')
    {
        $data = $this->validate();
        if ($this->locationId == null) {
            $location = null;

            if (!isset($this->locations[array_key_first($this->locations)]['name'])) {
                $this->addError('locations', __("You need to provide at least one location"));
                return false;
            }

            foreach ($this->locations as $key => $value) {
                $location = $this->questionnaire->company->locations()->create([
                    'name' => $value['name'],
                    'headquarters' => 0,
                    'country_code' => $value['country_code'],
                    'region_code' => $value['region_code'],
                    'city_code' => $value['city_code'],
                    'latitude' => $value['latitude'] ?? null,
                    'longitude' => $value['longitude'] ?? null,
                ]);
            }
        } else {
            $location = DB::table('company_addresses')
                ->where('id', $this->locationId)
                ->first();
        }

        $hazard = new ThinkHazard();
        $hazardData = $hazard->getHazards($location->city_code);

        foreach ($hazardData as &$item) {
            $item['enabled'] = true;
        }

        PhysicalRisks::create([
            'created_by_user_id' => auth()->id(),
            'questionnaire_id' => $this->questionnaire->id,
            'business_sector_id' => $data['activity'],
            'name' => $data['name'],
            'company_address_id' => $location->id,
            'hazards' => $hazardData,
            'note' => $this->note,
            'relevant' => $this->relevant
        ]);

        $this->emptyFileds();

        if ($type == 'add') {
            $this->dispatchBrowserEvent('closeForm');
        }

        $this->emit('physicalRiskChanges');
        $this->emit('physicalRiskRefresh');
    }

    /**
     * Handle updating of relevant field.
     */
    public function updatedRelevant($value)
    {
        if ($value === null || $value == "") {
            $this->relevanceDescription = null;
            return;
        }
        $this->relevanceDescription = PhysicalRisksRelevanceEnum::from($value)->description();
    }

    /**
     * All fields will be empty
     */
    protected function emptyFileds()
    {
        $this->name = '';
        $this->sector = '';
        $this->activity = '';
        $this->note = '';
        $this->relevant = '';
        $this->relevanceDescription = '';
        $this->locationId = '';
        $this->country = '';
        $this->region = '';
        $this->city = '';
        $this->locations = [];
        $this->addNewAddressOptions();
        $this->emit('resetInputField');
    }
}
