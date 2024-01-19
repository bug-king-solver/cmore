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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('token')->nullable();
            $table->string('url')->nullable();
            $table->enum('status', ['invalid', 'in_progress', 'approved', 'not_approved', 'expired', 'cancelled']);
            $table->json('payment_data');
            $table->json('response');
            $table->softDeletes();
            $table->timestamps();
            $table->unique('token');
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
        Schema::dropIfExists('payments');
    }
};
