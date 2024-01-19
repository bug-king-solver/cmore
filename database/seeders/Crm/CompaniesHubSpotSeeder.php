<?php

namespace Database\Seeders\Crm;

use App\Models\Crm\Company;
use App\Services\HubSpot\HubSpot;
use Illuminate\Database\Seeder;

class CompaniesHubSpotSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hubSpot = new HubSpot(HubSpot::COMPANIES);
        if (!$hubSpot->token) {
            $this->command->error("Must be have HUBSPOT_API_TOKEN in env file");
            return 0;
        }
        $hubSpot->setUpConnection();

        $this->command->info("Seeding companies from HubSpot");

        $hubSpot->associations = Company::getAssociationsHubSpot();
        Company::CreateOrUpdateFromHubSpot($hubSpot->getAllData());

    }
}
