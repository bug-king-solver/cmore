<?php

use App\Jobs\SeedTenantDatabaseAllJob;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\BusinessSectorType;
use Database\Seeders\Tenant\BusinessSectorSeeder;
use Database\Seeders\Tenant\BusinessSectorTypeSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $businessSectorType = BusinessSectorType::withoutGlobalScopes()->count();
        $businessSector = BusinessSector::withoutGlobalScopes()->count();

        // validation to not run the seeder when create a new tenant
        // If the tenant already have sector type , we dont need to run the seeder
        // if the tenant dont have business sector , means that is a new tenant and we dont need to run the seeder
        if ($businessSectorType > 0 || $businessSector == 0) {
            return ;
        }

        $tenant = tenant();
        $job[] = new SeedTenantDatabaseAllJob($tenant, BusinessSectorTypeSeeder::class, $all = true);
        $job[] = new SeedTenantDatabaseAllJob($tenant, BusinessSectorSeeder::class, $all = true);
        $job[] = Artisan::call('cache:clear');
        Bus::chain($job)->dispatch();
        Artisan::call('cache:clear');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
