<?php

namespace Database\Migrations\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Indicator;
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
        Schema::table('indicators', function (Blueprint $table) {
            $table->foreignIdFor(Category::class)->after('id')->nullable()->constrained()->nullOnDelete();
            $table->enum(
                'unit_qty',
                ['area', 'energy', 'lenght', 'mass', 'power', 'time', 'volume']
            )->nullable()->after('category_id');
            $table->string('unit_default', 10)->nullable()->after('unit_qty');
            $table->string('calc')->nullable()->after('unit_default');

            // remove
            $table->dropColumn('measure');
            $table->dropColumn('chart');
        });

        Schema::table('question_options', function (Blueprint $table) {
            $table->foreignIdFor(Indicator::class)->after('order')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('category_id');
            $table->dropColumn('unit_qty');
            $table->dropColumn('unit_default');
            $table->dropColumn('calc');
        });

        Schema::table('question_options', function (Blueprint $table) {
            $table->dropColumn('indicator_id');
        });
    }
};
