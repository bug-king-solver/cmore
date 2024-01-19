<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Indicator;
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

        if (!Schema::hasTable('auditors')) {
            Schema::create('auditors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->boolean('status')->default(true);
                $table->string('type');
                $table->string('frequency');
                $table->timestamps();
            });
        }

        // check all columns in indicators table
        if (!Schema::hasColumn('auditors', 'name')) {
            Schema::table('auditors', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }

        // check all columns in indicators table
        if (!Schema::hasColumn('auditors', 'status')) {
            Schema::table('auditors', function (Blueprint $table) {
                $table->boolean('status')->default(true);
            });
        }

        if (!Schema::hasColumn('auditors', 'type')) {
            Schema::table('auditors', function (Blueprint $table) {
                $table->string('type')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('auditors', 'frequency')) {
            Schema::table('auditors', function (Blueprint $table) {
                $table->string('frequency')->nullable()->after('type');
            });
        }

        // check if have the indicator_id column in auditors table
        if (!Schema::hasColumn('auditors', 'indicator_id')) {
            Schema::table('auditors', function (Blueprint $table) {
                $table->foreignId('indicator_id')->nullable()->after('id')
                    ->constrained('indicators')
                    ->onDelete('cascade');
            });
        }

        // check if have the indicator_id column in auditors table
        if (!Schema::hasColumn('auditors', 'company_id')) {
            Schema::table('auditors', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()
                    ->after('id')->constrained('companies')
                    ->onDelete('cascade');
            });
        }

        if (!hasForeignKey('auditors', 'company_id')) {
            // create only the foreign key
            Schema::table('auditors', function (Blueprint $table) {
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
        Schema::dropIfExists('auditors');
    }
};
