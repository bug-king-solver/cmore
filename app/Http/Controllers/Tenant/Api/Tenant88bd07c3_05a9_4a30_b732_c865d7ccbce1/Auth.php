<?php

namespace App\Http\Controllers\Tenant\Api\Tenant88bd07c3_05a9_4a30_b732_c865d7ccbce1;

use App\Http\Controllers\Controller;
use App\Models\User;

/**
 * Simple Single Sign On for Turismo de Portugal
 */
class Auth extends Controller
{
    protected $secret = 'A-H_AbrGhVU-WaF43A_TyRwUDv6Ybage';

    public function __construct()
    {
        $this->authenticate();
    }

    protected function authenticate()
    {
        $secret = request()->get('secret');

        abort_if($this->secret !== $secret, 403);
    }

    public function generateToken()
    {
        $name = request()->get('name');
        $username = request()->get('username');
        $email = request()->get('email');

        abort_if(! $name || ! $username || ! $email, 403);

        $user = User::getByUsername($username);

        if (! $user) {
            $user = User::create([
                'type' => tenant()->users_type_default,
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => null,
                'locale' => 'pt-PT',
            ]);
        }

        // The url will be available only for 60 seconds
        return response()->json(['url' => tenant()->impersonationUrl($user->id)]);
    }
}
