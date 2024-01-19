<?php

use Database\Seeders\Tenant\GarBtarSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allowedTenantId = [
            "3201246a-67d0-4062-a387-39bc4558b3e1",
            "062109b2-47ff-4906-9218-fc4f36b2f173",
            "77288c60-23c5-4c6a-b131-7b57aec39e51",
            "d7f4ecf1-71a4-4dcf-8381-834ea8fd3e20"
        ];

        if (in_array(tenant()->id, $allowedTenantId)) {
            tenant()->run(function () {
                $seeder = new GarBtarSeeder();
                $seeder->run();
            });
        }
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
