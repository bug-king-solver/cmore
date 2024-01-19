<?php

use App\Models\Tenant\GarBtar\BankSimulation;
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
        Schema::create('bank_simulation_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BankSimulation::class, 'bank_simulation_id')->constrained('bank_simulations')->onDelete('cascade');
            $table->json('data');
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
        Schema::dropIfExists('bank_simulation_assets');
    }
};
