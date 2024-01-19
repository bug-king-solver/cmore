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
        if (! Schema::hasColumn('questions', 'allow_not_reportable')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->boolean('allow_not_reportable')->after('allow_not_applicable')->default(false);
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
        if (Schema::hasColumn('questions', 'allow_not_reportable')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('allow_not_reportable');
            });
        }
    }
};
