<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\SharingOption;
use App\Models\Tenant\Company;
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
        Schema::create('sharing_options_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SharingOption::class, 'sharing_option_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Company::class, 'company_id')->nullable()->constrained('companies')->cascadeOnDelete();
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
        Schema::dropIfExists('sharing_options_companies');
    }
};
