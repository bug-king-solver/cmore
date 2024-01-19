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
        Schema::table('business_sectors', function (Blueprint $table) {
            // $table->foreignId('business_sector_type_id')->constrained('business_sector_types')->after('id');
            $table->integer('business_sector_type_id')->nullable()->after('id')->default(null);
            $table->json('data')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_sectors', function (Blueprint $table) {
            // $table->dropForeign(['business_sector_type_id']);
            $table->dropColumn('business_sector_type_id');
            $table->dropColumn('data');
        });
    }
};
