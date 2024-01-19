<?php

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
        Schema::table('reputation_analysis_info', function (Blueprint $table) {
            $table->foreignId('created_by_user_id')->after('company_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable()->after('created_by_user_id');
            $table->json('alternative_names')->nullable()->change();
            $table->json('handles')->nullable()->change();
            $table->json('search_terms')->nullable()->change();
            $table->json('competitors')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reputation_analysis_info', function (Blueprint $table) {
            $table->dropColumn('created_by_user_id');
            $table->dropColumn('name');
        });
    }
};
