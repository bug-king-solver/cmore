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
        Schema::table('document_analysis_domains', function (Blueprint $table) {
            $table->text('terms_prefixes')->nullable()->change();
            $table->text('terms_suffixes')->nullable()->change();
            $table->text('terms_both')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_analysis_domains', function (Blueprint $table) {
            $table->text('terms_prefixes')->nullable(false)->change();
            $table->text('terms_suffixes')->nullable(false)->change();
            $table->text('terms_both')->nullable(false)->change();
        });
    }
};
