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
        Schema::table('benchmark_data', function (Blueprint $table) {
            $table->dropForeign('benchmark_data_reporter_id_foreign');
            $table->dropColumn('reporter_id');
            $table->dropForeign('benchmark_data_validator_id_foreign');
            $table->dropColumn('validator_id');
            $table->dropColumn('validated');
            $table->dropColumn('ready');
            $table->dropColumn('ready_at');
            $table->dropColumn('time_to_ready');
            $table->dropColumn('validated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benchmark_data', function (Blueprint $table) {
            //
        });
    }
};
