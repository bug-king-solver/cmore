<?php

namespace Database\Migrations\Tenant;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (!Schema::hasColumn('data', 'validator_status')) {
            Schema::table('data', function (Blueprint $table) {
                $table->boolean('validator_status')->default(0)->after('origin');
            });
        }

        if (!Schema::hasColumn('data', 'validator_requested')) {
            Schema::table('data', function (Blueprint $table) {
                $table->string('validator_requested')->nullable()->after('validator_status');
            });
        }

        //check if validator_status exists
        if (Schema::hasColumn('data', 'validator_status')) {
            DB::table('data')->update(['validator_status' => 0]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data', function (Blueprint $table) {
            if (Schema::hasColumn('data', 'validator_status')) {
                $table->removeColumn('validator_status');
            }
        });

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
