<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Tenant::all()->runForEach(function ($tenant) {
            if (! $tenant->wallet->id) {
                echo ("\nCreate wallet for: " . $tenant->id . "\n");
                $wallet = new Wallet();
                $wallet->holder_type = 'App\Models\Tenant';
                $wallet->holder_id = $tenant->id;
                $wallet->name = 'Default Wallet';
                $wallet->slug = 'default';
                $wallet->meta = [];
                $wallet->save();
            }
        });
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('wallets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
