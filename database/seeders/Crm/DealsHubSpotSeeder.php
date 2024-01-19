<?php

namespace Database\Seeders\Crm;

use App\Models\Crm\Deal;
use App\Services\HubSpot\HubSpot;
use Illuminate\Database\Seeder;

class DealsHubSpotSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hubSpot = new HubSpot(HubSpot::DEALS);
        if (!$hubSpot->token) {
            $this->command->error("Must be have HUBSPOT_API_TOKEN in env file");
            return 0;
        }
        $hubSpot->setUpConnection();

        $this->command->info("Seeding deals from HubSpot");
        $hubSpot->associations = Deal::getAssociationsHubSpot();

        Deal::CreateOrUpdateFromHubSpot($hubSpot->getAllData());

    }
}
