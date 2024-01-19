<?php

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
        Schema::table('invoicing_documents', function (Blueprint $table) {
            $table
                ->enum('status', ['C', 'D', 'A'])
                ->default(null)
                ->nullable(true)
                ->comment('"C" for Close, "D" for Draft, "A" for Canceled')
                ->after('document_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoicing_documents', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};