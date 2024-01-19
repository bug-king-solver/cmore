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
        Schema::create('invoicing_documents', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('document_id')->nullable();
            $table->json('body_request');
            $table->json('response');
            $table->json('data')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique('document_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoicing_documents');
    }
};
