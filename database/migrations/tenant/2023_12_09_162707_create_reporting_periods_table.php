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
        Schema::create('reporting_periods', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('year, semester, quarter')->index();
            $table->integer('typeOrder')
                ->comment('For semester, will be 1 and 2 . For quarter, will be from 1 to 4. For month , will be from 1 to 12')
                ->index();
            $table->integer('year')->comment('The year of the reporting period')
                ->index();
            $table->integer('id_year')->index()->nullable();
            $table->integer('id_semester')->index()->nullable();
            $table->integer('id_quarter')->index()->nullable();
            $table->string('name');
            $table->string('custom_name')->comment('Custom name for the reporting period')->nullable();
            $table->boolean('enabled_questionnaires_filter')->default(false);
            $table->boolean('enabled_questionnaires_reporting')->default(false);
            $table->boolean('enabled_monitoring_filter')->default(false);
            $table->boolean('enabled_monitoring_reporting')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reporting_periods');
    }
};
