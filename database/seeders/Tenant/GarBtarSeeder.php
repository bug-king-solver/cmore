<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\GarBtar\BankAssets;
use App\Models\Traits\Filesystem\FileManagerTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Class GarBtarSeeder
 */
class GarBtarSeeder extends Seeder
{
    use FileManagerTrait;

    /**
     * Run the database seeds.
     * php artisan tenants:seed --class=Database\\Seeders\\Tenant\\GarBtarSeeder
     * @return void
     */
    public function run()
    {
        BankAssets::truncate();
        $jsonFile = \File::get(base_path() . '/database/data/gar-btar-sibs/explanatory-notes/processed_results.json');
        $jsonArray = json_decode($jsonFile, true);
        foreach ($jsonArray as $item) {
            $assetData = [];

            $company = Company::updateOrCreate(['vat_number' => $item['nipc']], [
                'name' => $item['nome_da_empresa'] ?? 'No name'
            ]);
            $assetData['company_id'] = $company->id;
            $assetData['original'] = json_encode($item);
            $assetData['data'] = $item;
            BankAssets::create($assetData);
        }
    }
}
