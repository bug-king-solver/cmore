<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    protected $maxAttempts = 2; // Default is 5

    protected $decayMinutes = 5; // Default is 1

    /** @override */
    public function showLoginForm()
    {
        $ssoTenant = tenant()->getSaml2();
        $redirectTo = $ssoTenant->relay_state_url ?? url('/');

        return tenant() && $ssoTenant
            ? redirect(route('tenant.saml.login', ['uuid' => $ssoTenant->uuid, 'returnTo' => $redirectTo ]))
            : tenantView('tenant.auth.login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $validations = [
            'username' => 'required|string',
            'password' => 'required|string',
        ];

        if (tenant()->requireToAcceptTermsAndConditions() || tenant()->isUserRequiredToAcceptTermsConditions) {
            $validations['terms'] = 'required';
        }

        $request->validate($validations);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->isSystemUserInTenant($request)) {
            return redirect()->back()->withErrors(
                [
                    'username' => __('Your access through username/password is restricted. Please use impersonate to Login.'),
                ]
            );
        }

        if ($this->attemptLogin($request)) {
            session()->put('locale', $request->user()->locale);

            $user = $request->user();

            // Check if is required to accept the terms and conditions again
            // This is done because accounts are created by C-MORE Support Team
            if ($user->isOwner()) {
                tenant()->acceptTermsAndConditions();
            }

            // Check if the user has an expired password
            if ($user->hasExpiredPassword) {
                $this->logLoginExpiredPassword($user);

                redirect()->back()->withErrors(
                    [
                        'password' => 'Your password has expired! Reset your password or contact your administrator.',
                    ]
                );
            }

            $this->logLogin($user);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logLogin($user)
    {
        activity()
            ->performedOn($user)
            ->event('authentication')
            ->log('login');
    }

    protected function loggedOut(Request $request)
    {
        if (tenant() && tenant()->getSaml2()) {
            return redirect()->route('tenant.logoutpage');
        }
    }

    protected function isSystemUserInTenant(Request $request)
    {
        $user = $this->guard()->getProvider()->retrieveByCredentials($request->only('username'));

        if ($user && $user->isSystemUser) {
            return true;
        }

        return false;
    }

    public function logLoginExpiredPassword($user)
    {
        activity()
            ->performedOn($user)
            ->event('authentication')
            ->log('expired password');
    }
}
