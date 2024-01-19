<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Question;
use App\Models\Tenant\Source;
use DB;
use Illuminate\Database\Seeder;

class FixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('DELETE FROM question_options WHERE `question_options`.`id` = 1167');
    }
}
