<?php

namespace Database\Migrations\Tenant;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Model\Users;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->foreignId('created_by_user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });

        Schema::table('targets', function ($table) {
            $table->foreignId('created_by_user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function ($table) {
            $table->dropForeign('companies_created_by_user_id_foreign');
            $table->dropColumn('created_by_user_id');
        });

        Schema::table('targets', function ($table) {
            $table->dropForeign('targets_created_by_user_id_foreign');
            $table->$table->dropColumn('created_by_user_id');
        });
    }
};
