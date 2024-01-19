<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Session;

class TwoFAController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        if (! Session::has('user_2fa')) {
            $find = User::where('id', auth()->user()->id)
                        ->first();
            $view = 'email';
            if ($find->google2fa_secret) {
                $view = 'application';
            }

            return view('tenant.auth.2fa.' . $view . '.2fa');
        }

        return back();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        if (auth()->user()->is2FAApplicationEnabled()) {
            return redirect('home');
        }
        $data = $this->validate($request, [
            'code' => 'required',
        ]);

        $find = User::where('id', auth()->user()->id)
                        ->where('two_factor_code', $data['code'])
                        ->where('two_factor_expires_at', '>=', now())
                        ->first();

        if (! is_null($find)) {
            Session::put('user_2fa', auth()->user()->id);

            return redirect()->route('tenant.home');
        }

        return back()->withErrors(['code' => __('Code expired or invalid.')]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resend()
    {
        if (! Session::has('user_2fa') && ! auth()->user()->is2FAApplicationEnabled()) {
            auth()->user()->generateCode();

            $message = __('We send the code to your email');
            if (auth()->user()->isToSend2FAEmail() && auth()->user()->isToSend2FAPhone()) {
                $message = __('Use the code we sent to your email and phone');
            } elseif (auth()->user()->isToSend2FAPhone()) {
                $message = __('Use the code we sent to your phone');
            }

            return back()->with('success', $message);
        }

        return back();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove2FAApplication()
    {
        if (! Session::has('user_2fa') && auth()->user()->is2FAApplicationEnabled()) {
            auth()->user()->remove2FAApplication();

            return redirect()->route('tenant.2fa.index');
        }

        return back();
    }

    /**
     * @return response()
     */
    public function recover()
    {
        return view('tenant.auth.2fa.recovery');
    }

    /**
     * @return response()
     */
    public function validateCode(Request $request)
    {
        $data = $this->validate($request, [
            'token' => 'required',
        ]);

        $user = User::where('id', auth()->user()->id)->first();
        $tokens = $user->getRecoveryTokens();

        if (in_array($data['token'], $tokens)) {
            // Remove the used token
            $key = array_search($data['token'], $tokens);
            unset($tokens[$key]);

            $user->recovery_codes = array_values($tokens);
            $user->save();

            Session::put('user_2fa', auth()->user()->id);

            return redirect()->route('tenant.home');
        } else {
            return back()->withErrors(['code' => __('Invalid token.')]);
        }
    }
}
