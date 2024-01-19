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
        // check fif table exists, if dont, create
        if (!Schema::hasTable('company_addresses')) {
            Schema::create('company_addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Company::class, 'company_id')->constrained()->onDelete('cascade');
                $table->string('name')->nullable();
                $table->boolean('headquarters');
                $table->string('country');
                $table->string('region');
                $table->string('city');
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // check all columns from company address table , if not exists , create
        if (!Schema::hasColumn('company_addresses', 'name')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('company_addresses', 'headquarters')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->boolean('headquarters')->default(false)->after('name');
            });
        }

        if (!Schema::hasColumn('company_addresses', 'country')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('country')->nullable()->after('headquarters');
            });
        }

        if (!Schema::hasColumn('company_addresses', 'region')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('region')->nullable()->after('country');
            });
        }

        if (!Schema::hasColumn('company_addresses', 'city')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('city')->nullable()->after('region');
            });
        }

        if (!Schema::hasColumn('company_addresses', 'latitude')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('latitude')->nullable()->after('city');
            });
        }

        if (!Schema::hasColumn('company_addresses', 'longitude')) {
            Schema::table('company_addresses', function (Blueprint $table) {
                $table->string('longitude')->nullable()->after('latitude');
            });
        }

        if (!Schema::hasColumn('company_addresses', 'deleted_at')) {
            Schema::table('company_addresses', function (Blueprint $table) {
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
        Schema::dropIfExists('company_addresses');
    }
};
