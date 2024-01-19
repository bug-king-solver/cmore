<?php

use App\Models\Enums\Companies\Relation;
use App\Models\Enums\Companies\Type;
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
        // Default type for all active tenants
        $type = Type::INTERNAL->value;

        DB::statement("UPDATE companies SET type = '{$type}'");

        // We need to have schema::table 2 times, due to the error changing a set() to a enum()
        Schema::table('companies', function (Blueprint $table) use ($type) {
            $table->dropColumn('type');
        });

        Schema::table('companies', function (Blueprint $table) use ($type) {
            $table->enum('type', Type::keys())->default($type)->after('business_sector_id');
            $table->set('relation', Relation::keys())->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Default type for all active tenants
        $type = Type::INTERNAL->value;

        Schema::table('companies', function (Blueprint $table) use ($type) {
            $table->dropColumn('type');
        });

        Schema::table('companies', function (Blueprint $table) use ($type) {
            $table->set('type', ['internal', 'customer', 'supplier'])->after('business_sector_id')->default($type);
            $table->dropColumn('relation');
        });
    }
};
