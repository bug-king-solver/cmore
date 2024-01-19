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
        Schema::table('initiatives', function (Blueprint $table) {
            $table->unsignedDecimal('impact')->change();
        });

        $data = [
            1 => 0,
            2 => 0,
            3 => 0.42,
            4 => 1.14975,
            5 => 0.2497,
            7 => 1.0997,
            8 => 0.28,
            9 => 6.304725,
            10 => 0.344575,
            11 => 0.0098,
            12 => 2.2575,
            13 => 0.1575,
            47 => 0.375,
            48 => 0.9583333333,
            49 => 2.625,
            15 => 4.7775,
            16 => 1.995,
            50 => 3.5,
            17 => 0.39975,
            18 => 0.15,
            19 => 0.375,
            20 => 0.87,
            57 => 3.5,
            21 => 0.12,
            23 => 3.5,
            24 => 2.625,
            27 => 0.8972115385,
            28 => 1.570288462,
            29 => 1.346153846,
            30 => 1.346153846,
            31 => 8.75,
            32 => 2.175,
            33 => 3.833333333,
            34 => 1.916666667,
            46 => 0.9583333333,
            35 => 3.833333333,
            36 => 3.258333333,
            37 => 0.575,
            38 => 1.916666667,
            39 => 0.4791666667,
            40 => 1.4375,
            41 => 0.75,
            42 => 2.1875,
            43 => 4.375,
            44 => 0.75,
            45 => 1.5,
            51 => 2.1875,
            52 => 0.8972115385,
            53 => 1.346153846,
            54 => 1.346153846,
            58 => 8.75,
            55 => 0.375,
            56 => 3.833333333,
        ];

        foreach ($data as $id => $value) {
            DB::table('initiatives')
                ->where('id', $id)
                ->update(['impact' => $value]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->unsignedInteger('impact')->change();
        });
    }
};
