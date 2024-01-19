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
        if (!Schema::hasTable('saml2_tenants')) {
            Schema::create('saml2_tenants', function (Blueprint $table) {
                $table->increments('id');
                $table->uuid('uuid');
                $table->string('key')->nullable();
                $table->string('idp_entity_id');
                $table->string('idp_login_url');
                $table->string('idp_logout_url');
                $table->text('idp_x509_cert');
                $table->json('metadata');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('saml2_tenants');
    }
};
