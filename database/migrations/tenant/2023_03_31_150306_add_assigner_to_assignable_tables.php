<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userables', function (Blueprint $table) {
            $table->renameColumn('userables_type', 'userable_type');
            $table->renameColumn('userables_id', 'userable_id');

            $table->renameIndex(
                'userables_userables_type_userables_id_index',
                'userables_userable_type_userable_id_index'
            );

            $table->morphs('assigner');
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->renameColumn('taggables_type', 'taggable_type');
            $table->renameColumn('taggables_id', 'taggable_id');

            $table->renameIndex(
                'taggables_taggables_type_taggables_id_index',
                'taggables_taggable_type_taggable_id_index'
            );

            $table->morphs('assigner');
        });

        DB::statement("UPDATE userables SET assigner_type = 'App\\\Models\\\User', assigner_id = 1");
        DB::statement("UPDATE taggables SET assigner_type = 'App\\\Models\\\User', assigner_id = 1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userables', function (Blueprint $table) {
            $table->renameColumn('userable_type', 'userables_type');
            $table->renameColumn('userable_id', 'userables_id');

            $table->renameIndex(
                'userables_userable_type_userable_id_index',
                'userables_userables_type_userables_id_index'
            );

            $table->dropMorphs('assigner');
        });

        Schema::table('taggables', function (Blueprint $table) {
            $table->renameColumn('taggable_type', 'taggables_type');
            $table->renameColumn('taggable_id', 'taggables_id');

            $table->renameIndex(
                'taggables_taggable_type_taggable_id_index',
                'taggables_taggables_type_taggables_id_index'
            );

            $table->dropMorphs('assigner');
        });
    }
};
