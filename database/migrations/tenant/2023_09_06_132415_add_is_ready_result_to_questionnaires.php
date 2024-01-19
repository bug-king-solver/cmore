<?php

use App\Models\Tenant\Questionnaire;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->timestamp('result_at')->nullable()->after('submitted_at');
        });

        if (Schema::hasColumn('questionnaires', 'deleted_at')) {
            Questionnaire::whereNotNull('submitted_at')->update(['result_at' => Carbon::now()]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('result_at');
        });
    }
};
