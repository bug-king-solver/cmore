<?php

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
        Schema::table('questionnaire_physicalrisks', function (Blueprint $table) {
            $table->text('note')->nullable()->after('hazards');
            $table->enum('relevant', ['LOW', 'MIDDLE', 'HIGH'])->nullable()->after('note');
            $table->boolean('completed')->default(false)->after('relevant');
            $table->datetime('completed_at')->nullable()->after('completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
