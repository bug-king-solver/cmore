<?php

namespace Database\Migrations\Tenant;

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
        if (!Schema::hasTable('saml2_tenants')) {
            Schema::table('saml2_tenants', function (Blueprint $table) {
                $table->string('name_id_format')->default('persistent');
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
        if (Schema::hasTable('saml2_tenants')) {
            Schema::table('saml2_tenants', function (Blueprint $table) {
                $table->dropColumn('name_id_format');
            });
        }
    }
};
