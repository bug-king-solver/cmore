<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Rules\PasswordStrength;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserSettingsController extends Controller
{
    public function show()
    {
        return view('tenant.settings.user', [
            'user' => auth()->user(),
        ]);
    }

    public function personal(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('tenant.users')->ignore(auth()->user()),
                function ($attribute, $value, $fail) {
                    if (
                        auth()->user()->isOwner() &&
                        Tenant::where('email', $value)
                        ->where('id', '!=', tenant('id'))
                        ->exists()
                    ) {
                        $fail("The $attribute is occupied by another tenant.");
                    }
                },
            ],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        /** @var User $user */
        $user = auth()->user();

        $user->update($validated);

        return redirect()->back()->with('success', 'Personal information updated.');
    }

    public function photo(Request $request)
    {
        $validated = $this->validate($request, [
            'photo' => ['required', 'image', 'max:1024'],
        ]);

        $photoPath = $validated['photo']->store('users', 'attachments');

        /** @var User $user */
        $user = auth()->user();

        $user->update(['photo' => $photoPath]);

        return redirect()->back()->with('success', 'Photo updated.');
    }

    public function password(Request $request)
    {
        $validated = $this->validate($request, [
            'password' => 'required|current_password',
            'new_password' => ['required', 'string', new PasswordStrength(), 'confirmed'],
        ]);

        /** @var User $user */
        $user = auth()->user();
        $user->password = Hash::make($validated['new_password']);
        $user->password_force_change = false;
        $user->setPasswordExpiration();
        $user->save();

        return redirect()->back()->with('success', 'Password updated.');
    }

    public function setUp2FA(Request $request)
    {
        $validated = $this->validate($request, [
            'check2fa' => ['boolean'],
            'email2fa' => ['boolean'],
            'phone2fa' => ['boolean'],
        ]);

        /** @var User $user */
        $user = auth()->user();

        $data_to_update = [
            'use_2fa' => $validated['check2fa'] ?? false,
            'send_2fa_to_email' => $validated['email2fa'] ?? false,
            'send_2fa_to_phone' => $validated['phone2fa'] ?? false,
        ];

        if ($data_to_update['use_2fa'] && empty($user->getRecoveryTokens())) {
            $data_to_update['recovery_codes'] = $user->generateRecoveryCodes()->toJson();
        } elseif ((isset($data_to_update['use_2fa']) && ! $data_to_update['use_2fa']) && (isset($data_to_update['send_2fa_to_phone']) && ! $data_to_update['send_2fa_to_phone']) && ($user->is2FAApplicationEnabled() ?? true)) {
            $data_to_update['recovery_codes'] = [];
        }

        $user->update($data_to_update);

        return redirect()->back()->with('success', '2FA settings updated');
    }
}
