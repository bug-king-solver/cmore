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
        Schema::table('crm_companies', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'enabled',
                'description',
                'about_us',
                'phone',
                'type',
                'linkedin',
                'twitter',
                'facebook',
                'googleplus',
            ]);
        });

        Schema::table('crm_contacts', function (Blueprint $table) {
            $table->dropColumn([
                'enabled',
                'firstname',
                'lastname',
                'email',
                'work_email',
                'phone',
                'gender',
                'company_name',
            ]);
        });

        Schema::table('crm_deals', function (Blueprint $table) {
            $table->dropColumn([
                'enabled',
                'description',
                'amount',
                'name',
                'type',
                'priority',
                'stage',
            ]);
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
