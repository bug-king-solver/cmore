<?php

namespace Database\Seeders;

use App\Models\Traits\Filesystem\FileManagerTrait;
use Illuminate\Database\Seeder;

class NaturalHazardSeeder extends Seeder
{
    use FileManagerTrait;

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $countriesUrl = "https://raw.githubusercontent.com/GFDRR/thinkhazardmethods/master/source/download/ADM0_TH.csv";
        $regionUrl = "https://raw.githubusercontent.com/GFDRR/thinkhazardmethods/master/source/download/ADM1_TH.csv";
        $cityUrl = "https://raw.githubusercontent.com/GFDRR/thinkhazardmethods/master/source/download/ADM2_TH.csv";

        $countries = $this->getCsv($countriesUrl);
        $regions = $this->getCsv($regionUrl);
        $cities = $this->getCsv($cityUrl);

        $countries = $this->mapData([
            'country_code',
            'country_name',
            'country_iso2',
            'country_iso3',
        ], $countries);

        $regions = $this->mapData([
            'region_code',
            'region_name',
            'country_code',
            'country_name',
        ], $regions);

        $cities = $this->mapData([
            'city_code',
            'city_name',
            'region_code',
            'region_name',
            'country_code',
            'country_name',
        ], $cities);

        $dir = base_path() . '/database/data/naturalhazard';
        $dirHazards = base_path() . '/database/data/naturalhazard/hazards';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!is_dir($dirHazards)) {
            mkdir($dirHazards, 0777, true);
        }

        if (count($countries) > 0) {
            $this->saveFileLocally($dir, "countries", $countries);
        }
        
        $replacements = [
            '¡' => 'i',
            '├' => '',
            '▒' => 'n',
            '│' => 'ó',
            '®' => 'é',
        ];

        foreach ($regions as $i => $region) {
            $regions[$i]['region_name'] = $this->replaceCharacters($region['region_name'], $replacements);

            $regions[$i]['cities'] = array_values(array_filter($cities, function ($city) use ($region) {
                return $city['region_code'] === $region['region_code'];
            }));
        }

        foreach ($regions as $i => $region) {
            $regions[$i]['region_name'] = $this->replaceCharacters($region['region_name'], $replacements);

            $regions[$i]['cities'] = array_values(array_filter($cities, function ($city) use ($region) {
                return $city['region_code'] === $region['region_code'];
            }));

            foreach ($regions[$i]['cities'] as $j => $city) {
                $regions[$i]['cities'][$j]['city_name'] = $this->replaceCharacters($city['city_name'], $replacements);
            }
        }

        foreach ($countries as $i => $country) {
            $allData = $country;
            $allData['regions'] = array_values(array_filter($regions, function ($region) use ($country) {
                return $region['country_code'] === $country['country_code'];
            }));

            if (count($allData) > 0) {
                $this->saveFileLocally(
                    $dirHazards,
                    "country-{$allData['country_code']}",
                    $allData
                );
            }
        }
    }

    /**
     * Get CSV data from URL.
     */
    public function getCsv($url)
    {
        $data = file_get_contents($url);
        $data = str_replace("\u{FEFF},", ",", $data);
        $data = explode("\r\n", $data);
        return $data;
    }

    /**
     * Map countries.
     */
    public function mapData($header, $data)
    {
        $all = [];
        foreach ($data as $i => $country) {
            $arr = explode(";", $country);

            if ($i == 0 || !isset($arr[1])) {
                continue; // skip header (first line )
            }

            $parsedData = [];
            foreach ($header as $i => $key) {
                $parsedData[$key] = $arr[$i] ?? null;
            }

            $all[] = $parsedData;
        }

        return $all;
    }

    protected function replaceCharacters($string, $replacements)
    {
        return str_replace(array_keys($replacements), array_values($replacements), $string);
    }
}
