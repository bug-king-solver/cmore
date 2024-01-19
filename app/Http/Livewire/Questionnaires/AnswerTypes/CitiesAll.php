<?php

namespace App\Http\Livewire\Questionnaires\AnswerTypes;

use App\Http\Livewire\Traits\QuestionTrait;
use App\Services\ThinkHazard\ThinkHazard;
use Livewire\Component;

class CitiesAll extends Component
{
    use QuestionTrait;

    public $regions = [];
    public $cities = [];
    public $regionList = [];
    public $citiesList = [];
    public $isInitialLoad = true;

    protected $listeners = ['regionsUpdated' => 'updatedRegions'];

    protected $rules = [
        'regions.*' => 'required',
        'cities.*' => 'required',
    ];

    public function beforeMount()
    {
        $this->loadRegionsForPortugal();
    }

    public function afterMount()
    {
        $values = json_decode($this->answer->value, true);
        if ($values) {
            $this->regions = $values['regions'] ?? [];
            $this->cities = $values['cities'] ?? [];
            $this->updatedRegions($this->regions);
        }
        $this->isInitialLoad = false;
    }

    public function loadRegionsForPortugal()
    {
        $hazard = new ThinkHazard();
        $regions = $hazard->getRegionsByCountryCode('199');
        $this->regionList = parseDataForSelect($regions, 'region_code', 'region_name');
    }

    public function updatedRegions($value)
    {
        // show the cities selected onload and only remove them when the user changes the region
        if (!$this->isInitialLoad) {
            $this->cities = [];
        }

        $this->citiesList = [];

        if (!empty($value)) {
            $hazard = new ThinkHazard();
            foreach ($value as $regionCode) {
                $cities = $hazard->getCitiesByRegionCode('199', $regionCode);
                $this->citiesList = array_merge($this->citiesList, parseDataForSelect($cities, 'city_code', 'city_name'));
            }
        }
    }

    public function save()
    {
        $this->validate();
        $this->beforeSave();

        $value = [
            'regions' => $this->regions,
            'cities' => $this->cities,
        ];

        $this->answer->value = json_encode($value);
        $this->answer->save();

        $this->afterSave();
    }
}
