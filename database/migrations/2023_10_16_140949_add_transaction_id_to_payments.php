<?php

use App\Models\Transaction;
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
        Schema::table('invoicing_documents', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->references('id')->on('invoicing_documents')->nullOnDelete();
            $table->foreignIdFor(Transaction::class)->nullable()->after('deal_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignIdFor(Transaction::class)->nullable()->after('deal_id');
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
            $table->dropColumn('parent_id');
            $table->dropColumn('transaction_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
};
