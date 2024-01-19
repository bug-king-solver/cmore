<?php

use App\Models\Crm\Company;
use App\Models\Crm\Contact;
use App\Models\Crm\Deal;
use App\Models\Invoicing\Document;
use App\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Csp\Scheme;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hubspot_relations');

        Schema::create('tenants_relations', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id', 255)->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->morphs('relatable');
        });


        Schema::create('crm_companies_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class);
            $table->morphs('relatable');
        });

        Schema::table('crm_deals', function (Blueprint $table) {
            $table->string('tenant_id', 255)->nullable()->after('id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Company::class)->nullable()->after('tenant_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignIdFor(Deal::class)->nullable()->after('tenant_id');
            $table->foreignIdFor(Document::class, 'invoicing_document_id')->nullable()->after('deal_id');
        });

        Schema::table('invoicing_documents', function (Blueprint $table) {
            $table->foreignIdFor(Deal::class)->nullable()->after('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants_relations');
        Schema::dropIfExists('crm_companies_relations');
        
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('deal_id');
            $table->dropColumn('invoicing_document_id');
        });
        
        Schema::table('invoicing_documents', function (Blueprint $table) {
            $table->dropColumn('deal_id');
        });
    }
};
