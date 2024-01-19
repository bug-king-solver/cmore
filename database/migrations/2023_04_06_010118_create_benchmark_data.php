<?php

namespace Database\Migrations;

use App\Models\BenchmarkReport;
use App\Models\Indicator;
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
        Schema::create('benchmark_data', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('note')->nullable();
            $table->foreignId('reporter_id')->nullable()->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('validator_id')->nullable()->references('id')->on('admins')->nullOnDelete();
            $table->foreignIdFor(BenchmarkReport::class, 'benchmark_report_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Indicator::class, 'indicator_id')
                ->nullable()->constrained()->nullOnDelete();
            $table->string('value')->nullable();
            $table->decimal('value_usd')->nullable();
            $table->decimal('value_eur')->nullable();
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
        Schema::dropIfExists('benchmark_data');
    }
};
