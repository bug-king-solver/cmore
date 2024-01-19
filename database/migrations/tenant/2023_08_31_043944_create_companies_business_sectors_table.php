<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant\Company;
use App\Models\Tenant\BusinessSector;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_business_sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class, 'company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignIdFor(BusinessSector::class, 'business_sector_id')
                ->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('companies_business_sectors');
    }
};
