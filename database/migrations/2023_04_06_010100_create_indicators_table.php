<?php

namespace Database\Migrations;

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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('note')->nullable();
            $table->json('name');
            $table->json('description');
            $table->boolean('has_benchmarking')->default(false);
            $table->enum(
                'unit_qty',
                ['decimal', 'area', 'energy', 'lenght', 'mass', 'power', 'time', 'volume']
            )->nullable();
            $table->string('unit_default', 10)->nullable();
            $table->text('calc')->nullable();
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
        Schema::dropIfExists('indicators');
    }
};
