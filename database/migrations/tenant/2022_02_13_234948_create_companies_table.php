<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\BusinessSector;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BusinessSector::class, 'business_sector_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->char('country', 3)->nullable();
            $table->char('vat_country', 3)->nullable();
            $table->string('vat_number')->nullable();
            $table->date('founded_at')->nullable();
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
