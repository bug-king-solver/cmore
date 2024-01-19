<?php

namespace App\Http\Livewire\Traits;

use App\Services\ThinkHazard\ThinkHazard;

trait CompanyAddressTrait
{
    public array $countryList = [];
    public array $regionList = [];
    public array $citiesList = [];


    /**
     * Define the validation rules.
     * @return array<string>
     */
    protected function rulesCompanyAddressTrait(): array
    {
        return [
            'locations.*.name' => ['nullable', 'string', 'max:255'],
            'locations.*.country_code' => ['required_with:locations.*.name,!=,'],
            'locations.*.region_code' => ['required_with:locations.*.country_code,!=,'],
            'locations.*.city_code' => ['required_with:locations.*.region_code,!=,'],
            'locations.*.latitude' => [
                'required_with:locations.*.longitude'
            ],
            'locations.*.longitude' => [
                'required_with:locations.*.latitude'
            ],
        ];
    }

    /**
     * Handle updating of country field.
     * @return void
     */
    public function addNewAddressOptions(): void
    {
        $checklists = $this->locations;
        end($checklists);
        $key = key($checklists);
        $this->locations[$key + 1] = '';
    }

    /**
     * Reove checklist options.
     * @return void
     */
    public function removeChecklistOptions($key): void
    {
        unset($this->locations[$key]);
    }

    /**
     * Update locations field.
     * @return void
     */
    public function updatedLocations($value, $key): void
    {
        if (!$value) {
            return;
        }

        $field = explode('.', $key);

        if ($field[1] == 'country_code') {
            $this->updateRegion($value, $field[0]);
        } elseif ($field[1] == 'region_code') {
            $this->updateCity($value, $field[0]);
        }
    }

    /**
     * update Region based on country selection
     * @return void
     */
    protected function updateRegion($value, $key): void
    {
        if (!$value) {
            return;
        }
        $hazard = new ThinkHazard();
        $regions = $hazard->getRegionsByCountryCode($value);
        $this->regionList[$key] = parseDataForSelect($regions, 'region_code', 'region_name');
        $this->citiesList[$key] = [];
    }

    /**
     * Handle updating of region field.
     * @return void
     */
    protected function updateCity($value, $key): void
    {
        if (!$value) {
            return;
        }
        $hazard = new ThinkHazard();
        $cities = $hazard->getCitiesByRegionCode($this->locations[$key]['country_code'], $value);
        $this->citiesList[$key] = parseDataForSelect($cities, 'city_code', 'city_name');
    }
}
