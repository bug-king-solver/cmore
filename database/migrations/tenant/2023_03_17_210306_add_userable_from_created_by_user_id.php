<?php

use App\Models\Tenant\Company;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Target;
use Illuminate\Database\Migrations\Migration;
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
        $companies = Company::select('id', 'created_by_user_id')->get();
        $targets = Target::select('id', 'created_by_user_id')->get();

        if (Schema::hasColumn('questionnaires', 'deleted_at')) {
            // $questionnaires = Questionnaire::select('id', 'created_by_user_id')->get();
            // /** loop in every model. Check if has users where created_by_user_id exists and if not , attach  */
            // foreach ($questionnaires as $questionnaire) {
            //     if ($questionnaire->users()->count() === 0) {
            //         $questionnaire->users()->attach($questionnaire->created_by_user_id);
            //     }
            // }
        }

        foreach ($companies as $company) {
            if ($company->users()->count() === 0) {
                $company->users()->attach($company->created_by_user_id);
            }
        }

        foreach ($targets as $target) {
            if ($target->users()->count() === 0) {
                $target->users()->attach($target->created_by_user_id);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $companies = Company::select('id', 'created_by_user_id')->get();
        $targets = Target::select('id', 'created_by_user_id')->get();

        if (Schema::hasColumn('questionnaires', 'deleted_at')) {
            $questionnaires = Questionnaire::select('id', 'created_by_user_id')->get();
            $questionnaires->each(function ($questionnaire) {
                $questionnaire->users()->sync([]);
            });
        }

        $companies->each(function ($company) {
            $company->users()->sync([]);
        });

        $targets->each(function ($target) {
            $target->users()->sync([]);
        });
    }
};
