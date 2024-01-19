<?php

use App\Models\Enums\SanctumAbilities;
use App\Models\Tenant\Api\ApiTokens;
use App\Models\User;
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
        if (!Schema::hasColumn('users', 'system')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('system')->default(false)->after('id');
            });
        }

        $cmoreInternalUsers = config('app.cmore_internal_users');
        foreach ($cmoreInternalUsers as $u) {
            USer::withoutEvents(function () use ($u) {
                $user = User::updateOrCreate(
                    [
                        'email' => $u['email'],
                    ],
                    [
                        'system' => true,
                        'name' => ucfirst($u['name']),
                        'username' => $u['email'],
                        'password' => bcrypt(config('app.cmore_internal_users_password')),
                        'enabled' => true,
                        'type' => 'internal',
                        'email_verified_at' => now(),
                        'locale' => 'pt_PT',
                    ]
                );
                if ($user) {
                    $token = hash('sha256', $u['token']);
                    $tokenExists = ApiTokens::where('token', $token)->first();
                    if (!$tokenExists) {
                        ApiTokens::withoutEvents(function () use ($u, $token, $user) {
                            ApiTokens::create([
                                'name' => $u['name'] . ' | Token',
                                'token' => $token,
                                'abilities' => json_encode(SanctumAbilities::keys()),
                                'tokenable_id' => $user->id,
                                'tokenable_type' => "App\Models\User",
                            ]);
                        });
                    }
                }
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('system');
        });
    }
};
