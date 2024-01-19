<?php

namespace App\Services\Questionnaires;

class Co2CalculatorService
{
    public $emissionFactorPath;
    public $emissionFactors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emissionFactorPath = base_path() . "/database/data/emissions/emission_factor.json";
        $this->emissionFactors = $this->loadJson($this->emissionFactorPath);
    }

    /**
     * Load json file as collection
     * @param string $path
     * @return object|array
     */
    public function loadJson(string $path): object|array
    {
        $json = file_get_contents($path);
        return collect(json_decode($json, true));
    }

    /**
     * Get emission factor by key
     * @param string $key
     * @return object|array
     */
    public function findByKey($key)
    {
        $arr = $this->emissionFactors->filter(function ($item, $index) use ($key) {
            return $index === $key;
        })->first() ?? [];

        return collect($arr);
    }

    /**
     * Filter emission factor by unit
     * @param object|array $emissionsFactor
     * @param string $unit
     * @return object|array
     */
    public function filterEmissionByUnit($emissionsFactor, $unit)
    {
        if (preg_match('/\./', $unit)) {
            $unit = explode('.', $unit)[1];
        }

        $arr = $emissionsFactor->filter(function ($item, $index) use ($unit) {
            return trim($item['unit']) === trim($unit);
        })->first() ?? [];

        return $arr;
    }
}
