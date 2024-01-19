<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
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
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class, 'company_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Indicator::class, 'indicator_id')->constrained()->cascadeOnDelete();
            $table->decimal('value', 17, 5);
            $table->dateTime('reported_at');
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
        Schema::dropIfExists('data');
    }
};
