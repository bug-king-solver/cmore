<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Question;
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
        // The seeder was fixed
        if (tenant()->created_at > '2022-04-12') {
            return;
        }

        $csvFile = fopen(base_path('database/data/questionnaire-v1/questions.csv'), 'r');

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            if ($firstline) {
                $firstline = false;
                continue;
            }

            DB::table('questions')->update(['source_id' => $data[3] ?: null]);
        }

        fclose($csvFile);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('questions')->update(['source_id' => null]);
    }
};
