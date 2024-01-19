<?php

namespace Database\Migrations;

use App\Models\BenchmarkCompany;
use App\Models\BenchmarkPeriod;
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
        Schema::create('benchmark_reports', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('note')->nullable();
            $table->foreignId('reporter_id')->nullable()->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('validator_id')->nullable()->references('id')->on('admins')->nullOnDelete();
            $table->foreignIdFor(BenchmarkPeriod::class, 'benchmark_period_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(BenchmarkCompany::class, 'benchmark_company_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->char('currency', 3);
            $table->date('validated_at')->nullable();
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
        Schema::dropIfExists('benchmark_reports');
    }
};
