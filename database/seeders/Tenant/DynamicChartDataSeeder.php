<?php

namespace Database\Seeders\Tenant;

use App\Models\Chart;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Company;
use App\Models\Tenant\Data;
use App\Models\Tenant\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;

class DynamicChartDataSeeder extends Seeder
{
    /** artisan tenants:seed --class=Database\\Seeders\\Tenant\\DynamicChartDataSeeder
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tenantsId = ['062109b2-47ff-4906-9218-fc4f36b2f173', '3201246a-67d0-4062-a387-39bc4558b3e1'];

        if (!in_array(tenant()->id, $tenantsId) && config('app.env') != 'local') {
            return;
        }

        $businessSectorId = BusinessSector::first();
        if (!$businessSectorId) {
            return;
        }

        $charts = Chart::where('type', 'chart')->get();
        $indicators = [];
        foreach ($charts as $chart) {
            $indicators = array_merge($indicators, $chart->indicators);
        }

        $tag = Tag::updateOrCreate(['name' => 'dashboards'], ['name' => 'dashboards', 'color' => 'blue']);
        $companies = Company::whereHas('tags', function ($q) {
            $q->whereName('dashboards');
        })->get();

        $faker = \Faker\Factory::create();

        if ($companies->count() == 0) {
            $countries = Countries::getAlpha3Codes();

            for ($i = 0; $i <= 5; $i++) {
                $company = Company::create(
                    [
                        'business_sector_id' => $faker->numberBetween(1, 13),
                        'name' => $faker->company(),
                        'country' => $faker->randomElement($countries),
                        'vat_country' => $faker->randomElement($countries),
                        'vat_number' => $faker->randomNumber(),
                        'founded_at' => $faker->date(),
                    ]
                );

                $company->tags()->syncWithPivotValues(
                    $tag->id,
                    [
                        'assigner_id' => 1,
                        'assigner_type' => 'App\Models\Tenant\User',
                    ]
                );
            }

            $companies = Company::whereHas('tags', function ($q) {
                $q->whereName('dashboards');
            })->get();
        }

        foreach ($companies as $company) {
            $data = [];

            foreach ($indicators as $indicatorId) {
                for ($i = 1; $i <= 12; $i++) {
                    $reported = carbon()->subMonth($i);
                    $data[] = [
                        'indicator_id' => $indicatorId,
                        'value' => rand(5, 350),
                        'reported_at' => $reported->format('Y-m-d H:i:s'),
                        'company_id' => $company->id,
                        'created_at' => $reported->format('Y-m-d H:i:s'),
                        'updated_at' => $reported->format('Y-m-d H:i:s'),
                    ];
                }
            }

            Data::insert($data);
        }
    }
}
