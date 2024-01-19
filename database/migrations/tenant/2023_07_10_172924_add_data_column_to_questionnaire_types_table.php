<?php

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
        Schema::table('questionnaire_types', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->json('data')->nullable()->after('slug');
            $table->dropColumn('redirectAfterCreate');
            $table->dropColumn('hasProgress');
            $table->dropColumn('questionnaireRoute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_types', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->dropColumn('slug');
            $table->boolean('hasProgress')->default(false)->after('name');
            $table->string('redirectAfterCreate')->nullable()->default('questionnaires.welcome')->after('hasProgress');
            $table->string('questionnaireRoute')->nullable()->default('questionnaires.show')->after('hasProgress');
        });
    }
};
