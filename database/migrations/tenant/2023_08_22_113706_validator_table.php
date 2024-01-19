<?php

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
        if (!Schema::hasTable('validators')) {
            Schema::create('validators', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type');
                $table->string('frequency');
                $table->timestamps();
            });
        }

        // check all columns in indicators table
        if (!Schema::hasColumn('validators', 'name')) {
            Schema::table('validators', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('validators', 'type')) {
            Schema::table('validators', function (Blueprint $table) {
                $table->string('type')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('validators', 'frequency')) {
            Schema::table('validators', function (Blueprint $table) {
                $table->string('frequency')->nullable()->after('type');
            });
        }

        // check if have the indicator_id column in validators table
        if (!Schema::hasColumn('validators', 'indicator_id')) {
            Schema::table('validators', function (Blueprint $table) {
                $table->foreignId('indicator_id')->nullable()->after('id')
                    ->constrained('indicators')
                    ->onDelete('cascade');
            });
        }

        // check if have the indicator_id column in validators table
        if (!Schema::hasColumn('validators', 'company_id')) {
            Schema::table('validators', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()
                    ->after('id')->constrained('companies')
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
        Schema::dropIfExists("validators");
    }
};
