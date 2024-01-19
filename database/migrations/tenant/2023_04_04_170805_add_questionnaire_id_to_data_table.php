<?php

use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data', function (Blueprint $table) {
            $table->foreignIdFor(Questionnaire::class, 'questionnaire_id')
                ->after('indicator_id')
                ->nullable()
                ->constrained('questionnaires')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data', function (Blueprint $table) {
            $table->dropForeign(['questionnaire_id']);
            $table->dropColumn('questionnaire_id');
        });
    }
};
