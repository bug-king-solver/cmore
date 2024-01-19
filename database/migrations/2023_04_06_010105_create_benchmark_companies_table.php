<?php

namespace Database\Migrations;

use App\Models\BusinessSector;
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
        Schema::create('benchmark_companies', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('note')->nullable();
            $table->foreignIdFor(BusinessSector::class, 'business_sector_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('ticker')->nullable();
            $table->char('headquarters', 3)->nullable();
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
        Schema::dropIfExists('benchmark_companies');
    }
};
