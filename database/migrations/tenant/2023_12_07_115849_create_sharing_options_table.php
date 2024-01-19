<?php

namespace Database\Migrations\Tenant;

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
        Schema::create('sharing_options', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->nullable();
            $table->boolean('enabled')->default(false);
            $table->string('logo')->nullable();
            $table->string('name');
            $table->string('commercial_name')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sharing_options');
    }
};
