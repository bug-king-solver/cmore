<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant\Company;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('headquarters_eu')->default(false)->after('country');
        });
        $this->updateHeadquartersEu();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('headquarters_eu');
        });
    }

    public function updateHeadquartersEu()
    {
        Company::whereIn('country', getEuCountries())->update(['headquarters_eu' => true]);
    }
};
