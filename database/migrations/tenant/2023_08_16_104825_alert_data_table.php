<?php

use App\Models\User;
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
        //check if user_id column exists in data table
        if (!Schema::hasColumn('data', 'user_id')) {
            Schema::table('data', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            });
        }
        if (!Schema::hasColumn('data', 'origin')) {
            Schema::table('data', function (Blueprint $table) {
                $table->string('origin')->nullable()->after('value');
            });
        }
        if (!Schema::hasColumn('data', 'validator_status')) {
            Schema::table('data', function (Blueprint $table) {
                $table->boolean('validator_status')->default(1)->after('origin');
            });
        }

        if (!Schema::hasColumn('data', 'validator_requested')) {
            Schema::table('data', function (Blueprint $table) {
                $table->string('validator_requested')->nullable()->after('validator_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('data', 'user_id')) {
            Schema::table('data', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('data', 'origin')) {
            Schema::table('data', function (Blueprint $table) {
                $table->dropColumn('origin');
            });
        }

        if (Schema::hasColumn('data', 'validator_status')) {
            Schema::table('data', function (Blueprint $table) {
                $table->dropColumn('validator_status');
            });
        }

        if (Schema::hasColumn('data', 'validator_requested')) {
            Schema::table('data', function (Blueprint $table) {
                $table->dropColumn('validator_requested');
            });
        }
    }
};
