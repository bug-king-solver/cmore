<?php

namespace Database\Migrations\Tenant;

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
        Schema::table('answers', function (Blueprint $table) {
            $table->foreignId('assigned_to_user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign('answers_assigned_to_user_id_foreign');
            $table->dropColumn('assigned_to_user_id');
            $table->dropColumn('assigned_at');
        });
    }
};
