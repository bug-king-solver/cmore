<?php

namespace Database\Seeders;

use App\Jobs\SeedTenantDatabaseAllJob;
use Database\Seeders\Tenant\BusinessActivitiesDescriptionSeeder;
use Database\Seeders\Tenant\BusinessActivitiesSeeder;
use Database\Seeders\Tenant\BusinessSectorSeeder;
use Database\Seeders\Tenant\BankEcoSystemSeeder;
use Database\Seeders\Tenant\BusinessSectorTypeSeeder;
use Database\Seeders\Tenant\CategoryQuestionnaireTypeSeeder;
use Database\Seeders\Tenant\CategorySeeder;
use Database\Seeders\Tenant\IndicatorSeeder;
use Database\Seeders\Tenant\InitiativeSeeder;
use Database\Seeders\Tenant\InternalTagSeeder;
use Database\Seeders\Tenant\PermissionSeeder;
use Database\Seeders\Tenant\ProductsSeeder;
use Database\Seeders\Tenant\QuestionOptionsSeeder;
use Database\Seeders\Tenant\QuestionOptions\MatrixSeeder;
use Database\Seeders\Tenant\QuestionOptions\SimpleSeeder;
use Database\Seeders\Tenant\QuestionSeeder;
use Database\Seeders\Tenant\QuestionnaireTypeSeeder;
use Database\Seeders\Tenant\RoleSeeder;
use Database\Seeders\Tenant\SdgSeeder;
use Database\Seeders\Tenant\SourceSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @description = This seeder is used to seed the tenant database when a new tenant is created.
     *
     * @return void
     */
    public function run()
    {
        $tenant = tenant();

        $seeders = [
            RoleSeeder::class,
            PermissionSeeder::class,
            InternalTagSeeder::class,
            CategorySeeder::class,
            ProductsSeeder::class,
            BusinessSectorTypeSeeder::class,
            BusinessSectorSeeder::class,
            BankEcoSystemSeeder::class,
            InitiativeSeeder::class,
            SourceSeeder::class,
            SdgSeeder::class,
            IndicatorSeeder::class,
            SimpleSeeder::class,
            MatrixSeeder::class,
            QuestionnaireTypeSeeder::class,
            CategoryQuestionnaireTypeSeeder::class,
            QuestionSeeder::class,
            QuestionOptionsSeeder::class,
            BusinessActivitiesSeeder::class,
            BusinessActivitiesDescriptionSeeder::class,

        ];

        $batch = [];
        foreach ($seeders as $seeder) {
            $batch[] = new SeedTenantDatabaseAllJob($tenant, $seeder, $all = true);
        }

        $tenant->run(function () {
            activity()->event("batch.start")->log("First job seed 💣 \r\n");
        });

        Bus::chain($batch)
            ->onQueue('tenant_seeders')
            ->dispatch();
    }
}
