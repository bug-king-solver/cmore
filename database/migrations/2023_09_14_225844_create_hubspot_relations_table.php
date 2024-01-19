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
        Schema::create('hubspot_relations', function (Blueprint $table) {
            $table->string('deal_id')->nullable(true);
            $table->string('company_id')->nullable(true);
            $table->string('hubspot_relations_type');
            $table->string('hubspot_relations_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hubspot_relations');
    }
};
