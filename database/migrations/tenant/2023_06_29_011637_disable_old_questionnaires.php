<?php

namespace Database\Migrations\Tenant;

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
        DB::table('questionnaire_types')
            ->whereIn('id', [1, 2, 6, 7])
            ->update(['enabled' => false]);

        DB::table('questionnaire_types')
            ->where('id', 7)
            ->update(['name' => 'Screen']);

        DB::table('questionnaire_types')
            ->where('id', 8)
            ->update(['name' => 'Assess']);

        DB::table('questionnaire_types')
            ->where('id', 9)
            ->update(['name' => 'Screen']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
