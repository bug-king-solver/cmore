<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // $dataColumn = [
        //     'routes' => [
        //         'welcome' => 'co2calculator.index',
        //         'show' => 'co2calculator.index',
        //         'edit' => 'questionnaires.edit',
        //         'report' => null,
        //     ],
        //     'modals' => [
        //         'submit' => null,
        //         'delete' => 'questionnaires.modals.delete',
        //         'review' => null,
        //     ],
        //     'progress' => false,
        //     'source' => [
        //         'categories' => 'disk',
        //         'questions' => 'disk',
        //     ],
        // ];

        // DB::table('questionnaire_types')
        //     ->insert([
        //         'enabled' => 1,
        //         'note' => 'co2-calculator',
        //         'name' => json_encode(['en' => 'Calculadora Emissões de gases com efeito de estufa']),
        //         'slug' => 'co2-calculator',
        //         'data' => json_encode($dataColumn),
        //     ]);
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
