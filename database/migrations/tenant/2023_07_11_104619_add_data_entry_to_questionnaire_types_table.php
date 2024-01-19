<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dataColumn = [
            'routes' => [
                'welcome' => 'questionnaires.welcome',
                'show' => 'questionnaires.show',
                'edit' => 'questionnaires.edit',
                'report' => 'tenant.dashboard',
            ],
            'modals' => [
                'submit' => 'questionnaires.modals.submit',
                'delete' => 'questionnaires.modals.delete',
                'review' => 'questionnaires.modals.review',
            ],
            'progress' => true,
            'source' => [
                'categories' => 'database',
                'questions' => 'database',
            ]
        ];

        DB::table('questionnaire_types')
            ->update([
                'data' => json_encode($dataColumn),
            ]);

        // loop into  DB::table('questionnaire_types') to update slug - must be unique
        foreach (DB::table('questionnaire_types')->get() as $questionnaireType) {
            $slug = Str::slug($questionnaireType->name);

            $count = DB::table('questionnaire_types')
                ->whereRaw('LOWER(name) = ?', [strtolower($questionnaireType->name)])
                ->where('id', '!=', $questionnaireType->id)
                ->count();

            if ($count >= 1) {
                $slug = $slug . '-' . $questionnaireType->id;
            }

            DB::table('questionnaire_types')
                ->where('id', $questionnaireType->id)
                ->update([
                    'slug' => $slug,
                ]);
        }

        $dataColumn = [
            'routes' => [
                'welcome' => 'taxonomy.show',
                'show' => 'taxonomy.show',
                'edit' => 'questionnaires.edit',
                'report' => null,
            ],
            'modals' => [
                'submit' => null,
                'delete' => 'questionnaires.modals.delete',
                'review' => 'questionnaires.taxonomy.modals.review',
            ],
            'progress' => false,
            'source' => [
                'categories' => 'disk',
                'questions' => 'disk',
            ],
        ];

        DB::table('questionnaire_types')
            ->whereRaw('LOWER(slug) = ?', ['taxonomy'])
            ->update([
                'data' => json_encode($dataColumn),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //set slug and data to null
        DB::table('questionnaire_types')
            ->update([
                'slug' => null,
                'data' => null,
            ]);
    }
};
