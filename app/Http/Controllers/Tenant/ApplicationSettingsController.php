<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    public function show()
    {
        return tenantView('tenant.settings.application');
    }

    public function storeConfiguration(Request $request)
    {
        $validated = $this->validate($request, [
            'company' => 'required|string|max:255',
        ]);

        tenant()->update($validated);

        return redirect()->back()->with('success', 'Configuration updated.');
    }

    public function logo(Request $request)
    {
        $validated = $this->validate($request, [
            'logo' => ['required_without:loginLogo', 'image', 'max:1024'],
            'loginLogo' => ['required_without:logo', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $validated['logo']->store('logo', 'public');
            tenant()->update(['logo' => $logoPath]);
        }

        if ($request->hasFile('loginLogo')) {
            $loginLogoPath = $validated['loginLogo']->store('loginLogo', 'public');
            tenant()->update(['login_logo' => $loginLogoPath]);
        }

        return redirect()->back()->with('success', 'Logos updated.');
    }
}
