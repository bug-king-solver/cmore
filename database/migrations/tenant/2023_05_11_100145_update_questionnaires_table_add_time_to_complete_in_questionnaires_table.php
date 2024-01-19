<?php

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
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->integer('time_to_complete')->nullable();
        });

        \Illuminate\Support\Facades\DB::table('questionnaires')->whereNotNull('submitted_at')->update([
            'time_to_complete' => \Illuminate\Support\Facades\DB::raw('UNIX_TIMESTAMP(submitted_at) - UNIX_TIMESTAMP(created_at)'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('time_to_complete');
        });
    }
};
