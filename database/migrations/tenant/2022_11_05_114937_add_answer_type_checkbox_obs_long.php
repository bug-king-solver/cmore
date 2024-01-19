<?php

namespace Database\Migrations\Tenant;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "ALTER TABLE `questions` CHANGE `answer_type` `answer_type` ENUM('binary','checkbox',
            'checkbox-obs','checkbox-obs-long','text','text-long','integer','decimal','matrix',
            'countries-multi','sdgs-multi','countries-all','currency')
             CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(
            "ALTER TABLE `questions` CHANGE `answer_type` `answer_type` ENUM('binary','checkbox','checkbox-obs',
            'text','text-long','integer','decimal','matrix','countries-multi','sdgs-multi',
            'countries-all','currency') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;"
        );
    }
};
