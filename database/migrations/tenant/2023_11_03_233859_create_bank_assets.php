<?php

use App\Models\Tenant\Company;
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

        //check if the ID of the company is primary key
        if (!hasPrimaryKey('companies', 'id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->primary('id');
            });
        }

        // check fif table exists, if dont, create
        if (!Schema::hasTable('bank_assets')) {
            Schema::create('bank_assets', function (Blueprint $table) {
                $table->id();
                $table->json('data');
                $table->timestamps();
            });
        }

        // check if has the comapany_id column, if not, create
        if (!Schema::hasColumn('bank_assets', 'company_id')) {
            Schema::table('bank_assets', function (Blueprint $table) {
                $table->foreignIdFor(Company::class, 'company_id')->constrained()
                    ->onDelete('cascade')->after('id');
            });
        }

        if (!hasForeignKey('bank_assets', 'company_id')) {
            // create only the foreign key
            Schema::table('bank_assets', function (Blueprint $table) {
                $table->foreign('company_id')->references('id')->on('companies')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('bank_assets');
    }
};
