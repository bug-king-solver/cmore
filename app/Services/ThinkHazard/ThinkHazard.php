<?php

namespace App\Services\ThinkHazard;

class ThinkHazard
{
    private $apiUrl = "http://thinkhazard.org/en/report/";
    private $client;

    private $countriesPath;
    private $hazardsPath;

    private $countryCode;
    private $regionCode;
    private $cityCode;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->countriesPath = base_path() . "/database/data/naturalhazard/countries.json";
        $this->hazardsPath = base_path() . "/database/data/naturalhazard/hazards";
    }

    /**
     * Get all countries from json list
     * @return object|array
     */
    public function getCountries(): object|array
    {
        return collect($this->loadJson($this->countriesPath));
    }

    /**
     * Get all regions from json list
     */
    public function getRegionsByCountryCode($countryCode)
    {
        if (!$countryCode) {
            throw new \Exception("Country code is required");
        }
        $country = collect($this->loadJson($this->hazardsPath . "/country-{$countryCode}.json"));
        if (isset($country['regions'])) {
            return collect($country['regions'])->filter(function ($item) use ($countryCode) {
                return strtolower($item['country_code']) === strtolower($countryCode);
            });
        }

        return [];
    }

    /**
     * Get all regions from json list
     */
    public function getCitiesByRegionCode($countryCode, $regionCode)
    {
        if (!$countryCode) {
            throw new \Exception("Country code is required");
        }

        $country = collect($this->loadJson($this->hazardsPath . "/country-{$countryCode}.json"));
        if (isset($country['regions'])) {
            $regions = collect($country['regions'])->filter(function ($item) use ($countryCode) {
                return strtolower($item['country_code']) === strtolower($countryCode);
            });

            return collect($regions)->filter(function ($item) use ($regionCode) {
                return strtolower($item['region_code']) === strtolower($regionCode);
            })->first()['cities'] ?? [];
        }

        return [];
    }

    /**
     * Get an specific country from json list , by name
     * @param string $name
     * @return string|null
     */
    public function getCountry(string $nameOrCode): array|null
    {
        $countries = $this->loadJson($this->countriesPath);
        $country = $countries->filter(function ($item) use ($nameOrCode) {
            return strtolower($item['country_name']) === strtolower($nameOrCode) ||
                strtolower($item['country_code']) === strtolower($nameOrCode);
        })->first();

        $this->countryCode = (int)$country['country_code'] ?? null;

        return $country;
    }

    /**
     * Get an specific region from json list , by name
     * @param string $name
     * @param null|int $countryCode
     * @return null|string
     */
    public function getRegion(string $nameOrCode, null|int $countryCode = null): array|null
    {
        if (!$countryCode) {
            if ($this->countryCode) {
                $countryCode = $this->countryCode;
            } else {
                throw new \Exception("Country code is required");
            }
        }

        $country = collect($this->loadJson($this->hazardsPath . "/country-{$countryCode}.json"));

        $region = null;
        if (isset($country['regions'])) {
            $region = collect($country['regions'])->filter(function ($item) use ($nameOrCode) {
                return strtolower($item['region_name']) === strtolower($nameOrCode) ||
                    strtolower($item['region_code']) === strtolower($nameOrCode);
            })->first();

            $this->regionCode = (int)$region['region_code'] ?? null;
        }

        return $region;
    }

    /**
     * Get an specific city from json list , by name
     * @param string $name
     * @param null|int $countryCode
     * @param null|int $region
     */
    public function getCity(string $nameOrCode, null|int $region = null, null|int $countryCode = null): array|null
    {
        if (!$countryCode) {
            if ($this->countryCode) {
                $countryCode = $this->countryCode;
            } else {
                throw new \Exception("Country code is required");
            }
        }

        if (!$region) {
            if ($this->regionCode) {
                $region = $this->regionCode;
            } else {
                throw new \Exception("Region code is required");
            }
        }

        $country = collect($this->loadJson($this->hazardsPath . "/country-{$countryCode}.json"));

        $city = null;
        if (isset($country['regions'])) {
            $region = collect($country['regions'])->filter(function ($item) use ($region) {
                return strtolower($item['region_code']) === strtolower($region);
            })->first();

            if (isset($region['cities'])) {
                $city = collect($region['cities'])->filter(function ($item) use ($nameOrCode) {
                    return strtolower($item['city_name']) === strtolower($nameOrCode) ||
                        strtolower($item['city_code']) === strtolower($nameOrCode);
                })->first();

                $this->cityCode = ((int)$city['city_code'] ?? null);
            }
        }

        return $city;
    }


    /**
     * Load json  file as collection
     * @param string $path
     * @return object|array
     */
    public function loadJson(string $path): object|array
    {
        $json = file_get_contents($path);
        return collect(json_decode($json, true));
    }

    /**
     * Send request to ThinkHazard API
     * @param int $locationCode
     */
    public function getHazards(int $locationCode)
    {
        $url = $this->apiUrl . $locationCode . ".json";
        $response = $this->client->request('GET', $url);
        $response = json_decode($response->getBody()->getContents());
        return $this->parseResponseData($response);
    }

    /**
     * parse data to be more easy to use
     * @param object $data
     */
    public function parseResponseData($data): array
    {
        $all = [];
        foreach ($data as $hazard) {
            $all[] = [
                'name' => $hazard->hazardtype->hazardtype,
                'name_slug' => strtolower($hazard->hazardtype->mnemonic),
                'risk' => $hazard->hazardlevel->title,
                'risk_slug' => strtolower($hazard->hazardlevel->mnemonic),
            ];
        }

        return $all;
    }
}
