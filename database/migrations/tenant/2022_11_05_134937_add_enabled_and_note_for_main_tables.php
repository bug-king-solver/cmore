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
        // I used DB::statement to have effect in soft deletes

        // Questionnaire types
        Schema::table('questionnaire_types', function (Blueprint $table) {
            $table->boolean('enabled')->default(false)->after('id');
            $table->string('note')->nullable()->after('enabled');
        });

        // Business sectors
        Schema::table('business_sectors', function (Blueprint $table) {
            $table->boolean('enabled')->default(false)->after('id');
            $table->string('note')->nullable()->after('enabled');
        });

        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('enabled')->default(false)->after('id');
            $table->string('note')->nullable()->after('enabled');
        });

        // Indicators
        Schema::table('indicators', function (Blueprint $table) {
            $table->boolean('enabled')->default(false)->after('id');
            $table->string('note')->nullable()->after('enabled');
        });

        // Initiative
        Schema::table('initiatives', function (Blueprint $table) {
            $table->boolean('enabled')->default(false)->after('id');
            $table->string('note')->nullable()->after('enabled');
        });

        // Questions
        Schema::table('questions', function (Blueprint $table) {
            $table->boolean('enabled')->default(false)->after('id');
            $table->string('note')->nullable()->after('enabled');
        });

        // Question options
        Schema::table('question_options', function (Blueprint $table) {
            $table->boolean('enabled')->default(false)->after('id');
            $table->string('note')->nullable()->after('enabled');
        });

        // All added data is the default data
        DB::statement('UPDATE questionnaire_types SET enabled = 1');
        DB::statement('UPDATE business_sectors SET enabled = 1');
        DB::statement('UPDATE categories SET enabled = 1');
        DB::statement('UPDATE indicators SET enabled = 1');
        DB::statement('UPDATE initiatives SET enabled = 1');
        DB::statement('UPDATE questions SET enabled = 1');
        DB::statement('UPDATE question_options SET enabled = 1');

        tenant()->update([
            'upgrade' => [
                'default_questionnaire_types' => true,
                'default_business_sectors' => true,
                'defaultCategories' => true,
                'default_indicators' => true,
                'default_initiatives' => true,
                'default_questions' => true,
                'default_question_options' => true,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_types', function (Blueprint $table) {
            $table->dropColumn('enabled', 'note');
        });

        Schema::table('business_sectors', function (Blueprint $table) {
            $table->dropColumn('enabled', 'note');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('enabled', 'note');
        });

        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('enabled', 'note');
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropColumn('enabled', 'note');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('enabled', 'note');
        });

        Schema::table('question_options', function (Blueprint $table) {
            $table->dropColumn('enabled', 'note');
        });
    }
};
