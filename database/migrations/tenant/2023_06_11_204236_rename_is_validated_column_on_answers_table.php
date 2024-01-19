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
        Schema::table('answers', function (Blueprint $table) {
            // Rename the column
            $table->renameColumn('is_validated', 'validation');
        });
    }

    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            // Revert the column name change
            $table->renameColumn('validation', 'is_validated');
        });
    }
};
