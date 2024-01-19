<?php

use App\Http\Controllers\Central as Controllers;
use App\Http\Controllers\Central\HomeController;
use App\Http\Controllers\Central\PaymentController;
use App\Http\Controllers\Tenant\Api\TenantController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/v1'], function () {
    Route::get('tenants/{token}/{feature?}', [TenantController::class, 'index']);

    Route::get('/validate-token', function () {
        if (auth()->check()) {
            return response()->json(['message' => 'Token válido'], 200);
        } else {
            return response()->json(['message' => 'Token inválido'], 401);
        }
    })->name('api.validate.interval-user')
        ->middleware(Laravel\Nova\Http\Middleware\Authenticate::class);
});

Route::post('/payment-update', [PaymentController::class, 'index'])
    ->name('central.payment.update');

Route::get('/', [HomeController::class, 'index'])
    ->name('central.landing');

Route::get('/packages', [HomeController::class, 'packages'])
    ->name('central.packages');

Route::get('/our-partners', [HomeController::class, 'ourPartners'])
    ->name('central.our-partners');

Route::get('/become-partner', [HomeController::class, 'becomePartner'])
    ->name('central.become-partner');

Route::get('/about-us', [HomeController::class, 'aboutUs'])
    ->name('central.about-us');

Route::get('/faqs', [HomeController::class, 'faqs'])
    ->name('central.faqs');

Route::get('/support-request', [HomeController::class, 'supportRequest'])
    ->name('central.support-request');

Route::get('/contact-us', [HomeController::class, 'contactUs'])
    ->name('central.contact-us');

Route::get('/solution', [HomeController::class, 'solutions'])
    ->name('central.solution');


Route::get('/register', [Controllers\RegisterTenantController::class, 'show'])
    ->name('central.tenants.register')
    ->middleware(config('app.self_registration') ? null : 'auth:admin');

Route::post('/register/submit', [Controllers\RegisterTenantController::class, 'submit'])
    ->name('central.tenants.register.submit');

Route::get('/login', [Controllers\LoginTenantController::class, 'show'])
    ->name('central.tenants.login');

Route::post('/login/submit', [Controllers\LoginTenantController::class, 'submit'])
    ->name('central.tenants.login.submit');

Route::get('/locale/{locale}', fn ($locale = null) => setUserLocale($locale))
    ->name('central.locale');


Route::get('/asset/view/{disk?}/{path?}', [Controllers\HomeController::class, 'asset'])
    ->where('path', '(.*)')
    ->name('central.asset');

Route::get('/asset/download/{disk?}/{path?}', [Controllers\HomeController::class, 'download'])
    ->where('path', '(.*)')
    ->name('central.asset.download');
