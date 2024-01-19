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
        Schema::create('crm_companies', function (Blueprint $table) {
            $table->id();
            $table->string('id_hubspot')->index();
            $table->string('name');
            $table->boolean('enabled')->default(true);
            $table->string('description')->nullable();
            $table->string('about_us')->nullable();
            $table->string('phone')->nullable();
            $table->string('type')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('googleplus')->nullable();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('crm_companies');
    }
};
