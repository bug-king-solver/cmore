<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('layout')->nullable();
            $table->json('filters')->nullable();
            $table->timestamps();
        });

        /*DB::table('tenant1110bc17-4d76-419c-b73c-57e44fe05681.dashboard_templates')->insert([
            'name' => 'General Template',
            'description' => "General information dashboard template",
            'layout' => '{
                            "rows": [
                                [
                                    "g1",
                                    "g2",
                                    "g3"
                                ],
                                [
                                    "g4",
                                    "g5"
                                ]
                            ]
                        }',
            'created_at' => Carbon::now(),
        ]);

        DB::table('tenant1110bc17-4d76-419c-b73c-57e44fe05681.dashboard_templates')->insert([
            'name' => 'Environment Template',
            'description' => "Environment information dashboard template",
            'created_at' => Carbon::now(),
        ]);

        DB::table('tenant1110bc17-4d76-419c-b73c-57e44fe05681.dashboard_templates')->insert([
            'name' => 'Social Template',
            'description' => "Social information dashboard template",
            'created_at' => Carbon::now(),
        ]);

        DB::table('tenant1110bc17-4d76-419c-b73c-57e44fe05681.dashboard_templates')->insert([
            'name' => 'Governance Template',
            'description' => "Governance information dashboard template",
            'created_at' => Carbon::now(),
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_templates');
    }
};
