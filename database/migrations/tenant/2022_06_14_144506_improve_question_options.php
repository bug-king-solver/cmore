<?php

namespace Database\Migrations\Tenant;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('question_question_option', function (Blueprint $table) {
            $table->dropForeign('question_question_option_question_option_id_foreign');
            $table->unsignedBigInteger('question_option_id')->nullable()->change();
            DB::query('UPDATE question_options SET question_option_id = null;');
        });

        Schema::dropIfExists('question_options');
        Schema::rename('question_question_option', 'question_options');

        Schema::table('question_options', function (Blueprint $table) {
            $table->string('question_option_type')->nullable()->after('question_id');
            $table->index(['question_option_type', 'question_option_id']);

            $table->renameIndex(
                'question_question_option_initiative_id_foreign',
                'question_option_initiative_id_foreign'
            );
            $table->renameIndex('question_question_option_question_id_foreign', 'question_option_question_id_foreign');
            $table->renameIndex('question_question_option_sdg_id_foreign', 'question_option_sdg_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
