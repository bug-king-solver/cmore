<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Question;
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
            $table->json('name');
            $table->json('description');
            // https://en.wikipedia.org/wiki/International_System_of_Units
            $table->enum('measure', ['time', 'length', 'mass', 'volume', 'power'])->nullable();
            $table->enum('chart', ['report', 'bar', 'pie'])->nullable();
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
        Schema::dropIfExists('indicators');
    }
};
