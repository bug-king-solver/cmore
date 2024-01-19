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
        // check if the table questions has the column answer_type
        if (Schema::hasColumn('questions', 'answer_type')) {
            // change the column answer_type to varchar
            Schema::table('questions', function (Blueprint $table) {
                $table->string('answer_type')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // check if the table questions has the column answer_type
        if (Schema::hasColumn('questions', 'answer_type')) {
            // change the column answer_type to varchar
            Schema::table('questions', function (Blueprint $table) {
                $table->string('answer_type')->change();
            });
        }
    }
};
