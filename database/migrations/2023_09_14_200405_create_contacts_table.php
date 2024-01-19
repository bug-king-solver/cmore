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
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('id_hubspot')->index();
            $table->boolean('enabled')->default(true);
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('work_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
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
        Schema::dropIfExists('crm_contacts');
    }
};
