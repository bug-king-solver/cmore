<?php

use App\Http\Controllers\Central\BackofficeActionsController;
use App\Http\Controllers\Tenant as Controllers;
use App\Http\Controllers\Tenant\Auth\TwoFAController;
use App\Http\Livewire as LivewireControllers;
use App\Http\Livewire\SourceReport\Create;
use App\Http\Livewire\SourceReport\Index;
use App\Http\Livewire\SourceReport\SourceReport;
use App\Http\Middleware\Check2FA;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\ForceChangePassword;
use App\Http\Middleware\InsufficientFunds;
use App\Http\Middleware\PreventAccessFromNotAuthorizedIp;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Features\UserImpersonation;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], static function () {
    Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
        ->middleware([
            'web',
            InitializeTenancyByDomain::class, // Use tenancy initialization middleware of your choice
        ])->name('sanctum.csrf-cookie');
});

Route::group([
    'middleware' => array_merge([
        InitializeTenancyByDomainOrSubdomain::class,
        PreventAccessFromNotAuthorizedIp::class,
        ResolveTenant::class
    ], config('saml2.routesMiddleware')),
    'as' => 'tenant.',
], function () {
    Route::get('/saml/{uuid}/logout', array(
        'as' => 'saml.logout',
        'uses' => 'Slides\Saml2\Http\Controllers\Saml2Controller@logout',
    ));

    Route::get('/saml/{uuid}/login', array(
        'as' => 'saml.login',
        'uses' => 'Slides\Saml2\Http\Controllers\Saml2Controller@login',
    ));

    Route::get('/saml/{uuid}/metadata', array(
        'as' => 'saml.metadata',
        'uses' => 'Slides\Saml2\Http\Controllers\Saml2Controller@metadata',
    ));

    Route::post('/saml/{uuid}/acs', array(
        'as' => 'saml.acs',
        'uses' => 'Slides\Saml2\Http\Controllers\Saml2Controller@acs',
    ));

    Route::get('/saml/{uuid}/sls', array(
        'as' => 'saml.sls',
        'uses' => 'Slides\Saml2\Http\Controllers\Saml2Controller@sls',
    ));
});

Route::group([
    'middleware' => ['tenant', PreventAccessFromNotAuthorizedIp::class, PreventAccessFromCentralDomains::class], // See the middleware group in Http Kernel
    'as' => 'tenant.',
], function () {

    Route::get('/locale/{locale}', fn ($locale = null) => setUserLocale($locale))->name('locale');

    Route::redirect('/', '/login');

    Route::get('/logout', [Controllers\HomeController::class, 'logoutPage'])
        ->name('logoutpage');

    Route::get('/impersonate/{token}', function ($token) {
        return UserImpersonation::makeResponse($token);
    })->name('impersonate');


    Route::get(
        'account/disabled',
        fn () => auth()->user() && auth()->user()->isEnabled() ? redirect('/home') : view('tenant.auth.disabled')
    )->name('auth.disabled');

    Route::get('/tenancy/downloads/{disk?}/{path?}', [Controllers\TenantAssetsController::class, 'download'])
        ->where('path', '(.*)')
        ->name('asset.download');

    Route::get('/tenancy/{disk?}/{path?}', [Controllers\TenantAssetsController::class, 'asset'])
        ->where('path', '(.*)')
        ->name('asset');

    $authMiddlewares = [
        'auth',
        'auth.verified:tenant.verification.notice',
        'auth.enabled',
        CheckSubscription::class,
        ForceChangePassword::class,
        InsufficientFunds::class,
    ];

    Route::middleware($authMiddlewares)->group(function () {
        Route::get('2fa', [TwoFAController::class, 'index'])->name('2fa.index');
        Route::post('2fa', [TwoFAController::class, 'store'])->name('2fa.post')->middleware('2fa');
        Route::get('2fa/reset', [TwoFAController::class, 'resend'])->name('2fa.resend');
        Route::post('2fa/remove', [TwoFAController::class, 'remove2FAApplication'])->name('2fa.remove');
        Route::get('2fa/recover', [TwoFAController::class, 'recover'])->name('2fa.recover');
        Route::post('2fa/recover', [TwoFAController::class, 'validateCode'])->name('2fa.recovery');
    });

    $authMiddlewares2FA = $authMiddlewares;
    $authMiddlewares2FA[] = Check2FA::class;

    Route::middleware($authMiddlewares2FA)->group(function () {
        Route::redirect('/home', '/dashboard');

        Route::get('/home', [Controllers\HomeController::class, 'index'])
            ->name('home');

        Route::get('/catalog', [
            LivewireControllers\Catalog::class,
            '__invoke'
        ])->name('catalog');

        Route::get('/logoutsso', [Controllers\HomeController::class, 'logout'])
            ->name('logoutsso');

        Route::get('/attachments/{ids?}', [Controllers\Attachment::class, 'index'])
            ->name('attachments');

        Route::get('/notifications', [LivewireControllers\Notifications\Index::class, '__invoke'])
            ->name('notifications.index');

        Route::get('/getQuestionnaire', [Controllers\QuestionnaireListController::class, 'index'])
            ->name('questionnairelist');

        Route::get('/settings/user', [Controllers\UserSettingsController::class, 'show'])
            ->name('settings.user');
        Route::post('/settings/user/personal', [Controllers\UserSettingsController::class, 'personal'])
            ->name('settings.user.personal');
        Route::post('/settings/user/photo', [Controllers\UserSettingsController::class, 'photo'])
            ->name('settings.user.photo');
        Route::post('/settings/user/password', [Controllers\UserSettingsController::class, 'password'])
            ->name('settings.user.password');
        Route::post('/settings/2fa-app', [Controllers\UserSettingsController::class, 'twoFAApplication'])
            ->name('settings.user.twoFAApplication');
        Route::post('/settings/2fa-app/reset', [Controllers\UserSettingsController::class, 'resetTwoFAApplication'])
            ->name('settings.user.resetTwoFAApplication');
        Route::post('/settings/2fa', [Controllers\UserSettingsController::class, 'setUp2FA'])
            ->name('settings.user.setUp2FA');

        Route::middleware(['can:dashboard.view'])->group(function () {
            Route::get('/dashboard/{questionnaire?}', [
                LivewireControllers\Dashboard\Index::class,
                '__invoke',
            ])->name('dashboard');

            Route::get('/dashboards/{questionnaire?}', [
                Controllers\DashboardController::class,
                'index',
            ])->name('dashboards');
        });

        Route::group(['prefix' => 'regulation', 'middleware' => ['can:data.view']], function () {

            Route::get('/ratios', [
                LivewireControllers\GarBtar\Index::class,
                '__invoke'
            ])->name('garbtar.index');

            Route::group(['prefix' => 'crr-indicators'], function () {

                Route::get('/panel', [
                    LivewireControllers\GarBtar\CrrIndicators\Panel::class,
                    '__invoke'
                ])->name('garbtar.crr.panel');

                Route::get('/alignment-metrics', [
                    LivewireControllers\GarBtar\CrrIndicators\Metric::class,
                    '__invoke'
                ])->name('garbtar.crr.metrics');

                Route::get('/physical-risks', [
                    LivewireControllers\GarBtar\CrrIndicators\PhysicalRisks::class,
                    '__invoke'
                ])->name('garbtar.crr.physical_risks');
            });

            Route::group([], function () {
                Route::get('/assets', [
                    LivewireControllers\GarBtarAssets\Index::class,
                    '__invoke'
                ])->name('garbtar.assets');

                Route::get('/assets/lists', [
                    LivewireControllers\GarBtarAssets\Asset::class,
                    '__invoke'
                ])->name('garbtar.asset');
            });

            Route::group(['prefix' => 'regulatory-tables'], function () {

                Route::get('/', [
                    LivewireControllers\GarBtarRegulatory\Index::class,
                    '__invoke'
                ])->name('garbtar.regulatory');

                Route::get('/summary-gar', [
                    LivewireControllers\GarBtarRegulatory\SummaryGAR::class,
                    '__invoke'
                ])->name('garbtar.regulatory.summarygar');

                Route::get('/garassetsmitigate', [
                    LivewireControllers\GarBtarRegulatory\GARAssetsMitigate::class,
                    '__invoke'
                ])->name('garbtar.regulatory.garassetsmitigate');

                Route::get('/garpercetagetable', [
                    LivewireControllers\GarBtarRegulatory\GARPercentageTable::class,
                    '__invoke'
                ])->name('garbtar.regulatory.garpercetagetable');

                Route::get('/btarassetsmitigate', [
                    LivewireControllers\GarBtarRegulatory\BTARAssetsMitigate::class,
                    '__invoke'
                ])->name('garbtar.regulatory.btarassetsmitigate');
            });
        });

        Route::group(['prefix' => 'dynamic-dashboard', 'middleware' => ['can:data.view', 'feature.enabled:dynamic-dashboard']], function () {
            Route::get(
                '/',
                [LivewireControllers\Report\DashboardList::class, '__invoke']
            )->name('dynamic-dashboard.index');

            Route::get(
                'single/{dashboard}',
                [LivewireControllers\Report\SingleDashboard::class, '__invoke']
            )->name('dynamic-dashboard');

            Route::get(
                'create',
                [LivewireControllers\Report\Create::class, '__invoke']
            )->name('dynamic-dashboard.create');

            Route::get(
                'edit/{dashboard}',
                [LivewireControllers\Report\Edit::class, '__invoke']
            )->name('dynamic-dashboard.edit');

            // Route::get(
            //     'delete/{dashboard}',
            //     [LivewireControllers\Report\Delete::class,'__invoke']
            // )->name('dynamic-dashboard.delete');

            //Dashboard templates
            Route::get(
                'template/{dashboardTemplate}/preview',
                [LivewireControllers\Report\Templates\SingleTemplate::class, '__invoke']
            )->name('dynamic-dashboard.template.preview');

            Route::get(
                'template/{dashboardTemplate}/store',
                [LivewireControllers\Report\TemplateCreateDashboard::class, '__invoke']
            )->name('dynamic-dashboard.template.store');
        });

        Route::middleware(['can:data.view', 'feature.enabled:monitoring'])->group(function () {
            Route::get('/data', [LivewireControllers\Data\Index::class, '__invoke'])
                ->name('data.index');

            Route::get('/data/panel', [LivewireControllers\Data\Panel::class, '__invoke'])
                ->name('data.panel');

            Route::get('/data/form/{company}/{indicator}/{data?}', [LivewireControllers\Data\Form::class, '__invoke'])
                ->name('data.form');

            Route::get('/data/indicator/show/{indicator}', [LivewireControllers\Data\Indicatores::class, '__invoke'])
                ->name('data.indicators.show');

            Route::get(
                '/data/indicator/log/show/{data}',
                [LivewireControllers\Data\Show::class, '__invoke']
            )->name('data.show');

            Route::get(
                '/data/validator/{indicator}/{company}',
                [LivewireControllers\Data\Validator::class, '__invoke']
            )->name('data.validator');
        });

        Route::middleware(['can:benchmarking.view', 'feature.enabled:benchmarking'])->group(function () {
            Route::get('/benchmarking', [LivewireControllers\Benchmarking\Index::class, '__invoke'])
                ->name('benchmarking.index');
        });

        Route::middleware(['can:targets.view', 'feature.enabled:targets'])->group(function () {
            Route::get('/targets', [LivewireControllers\Targets\Index::class, '__invoke'])
                ->name('targets.index');

            Route::get('/targets/groups/{groupId?}', [LivewireControllers\Targets\Index::class, '__invoke'])
                ->name('targets.groups');

            Route::get('/targets/form/{target?}', [LivewireControllers\Targets\Form::class, '__invoke'])
                ->name('targets.form');

            Route::get('/targets/{target}', [LivewireControllers\Targets\Show::class, '__invoke'])
                ->name('targets.show');
        });

        Route::middleware(['can:library.view'])->group(function () {
            Route::get('/library/list', [LivewireControllers\Documents\Index::class, '__invoke'])
                ->name('library.index');

            // Route::get('/library/folder/{documentFolder:slug}/', [
            //     LivewireControllers\Documents\Folder\Index::class,
            //     '__invoke'
            // ])->name('library.folder.show');

            Route::get('library/folder/{slug}', [
                LivewireControllers\Documents\Folder\Index::class,
                '__invoke',
            ])
                ->where('slug', '^[a-zA-Z0-9-_\/]+$')->name('library.folder.show');

            Route::get('/library/{documentHeading}/{documentType?}/', [
                LivewireControllers\Documents\Show::class,
                '__invoke',
            ])->name('library.show');
        });

        Route::prefix('questionnaires')
            ->middleware('user.hasAccess:questionnaire')
            ->group(function () {
                Route::middleware(['can:questionnaires.view'])->group(function () {
                    Route::get('/', [
                        LivewireControllers\Questionnaires\Index::class,
                        '__invoke',
                    ])->name('questionnaires.index');

                    Route::get('/form/{questionnaire?}', [
                        LivewireControllers\Questionnaires\Form::class,
                        '__invoke',
                    ])->name('questionnaires.form');

                    Route::get('/panel', [
                        LivewireControllers\Questionnaires\Panel::class,
                        '__invoke',
                    ])->name('questionnaires.panel');

                    Route::get('/welcome/{questionnaire}', [
                        LivewireControllers\Questionnaires\Welcome::class,
                        '__invoke',
                    ])->name('questionnaires.welcome');

                    Route::get('/export/{questionnaire}/pdf', [
                        LivewireControllers\Questionnaires\Export::class,
                        'pdf',
                    ])->name('questionnaires.export.pdf');

                    Route::get('/export/{questionnaire}/csv', [
                        LivewireControllers\Questionnaires\Export::class,
                        'csv',
                    ])->name('questionnaires.export.csv');
                });

                Route::middleware(['can:questionnaires.view'])->group(function () {
                    Route::prefix('taxonomy')->group(function () {

                        Route::get('report/{questionnaire}', [LivewireControllers\Questionnaires\Taxonomy\Report::class, '__invoke'])
                            ->name('taxonomy.report')
                            ->where('questionnaire', '[0-9]+');

                        Route::get('report/{questionnaire}/download', [LivewireControllers\Questionnaires\Taxonomy\DownloadReportTable::class, '__invoke'])
                            ->name('taxonomy.report.table')
                            ->where('questionnaire', '[0-9]+');

                        Route::get('substantial/{questionnaire}/activity/{code}', [LivewireControllers\Questionnaires\Taxonomy\Substantial::class, '__invoke'])
                            ->name('taxonomy.substantial')
                            ->where(['questionnaire', '[0-9]+', 'activity', '[0-9]+']);

                        Route::get('substantial/{questionnaire}/activity/{code}/objective/{objective?}', [LivewireControllers\Questionnaires\Taxonomy\SubstantialQuestionnaire::class, '__invoke'])
                            ->name('taxonomy.substantial.questionnaire')
                            ->where(['questionnaire', '[0-9]+', 'activity', '[0-9]+', 'objective', '[0-9]+']);

                    Route::get('dnsh/{questionnaire}/activity/{code}', [LivewireControllers\Questionnaires\Taxonomy\Nps::class, '__invoke'])
                        ->name('taxonomy.dnsh')
                        ->where(['questionnaire', '[0-9]+', 'activity', '[0-9]+']);

                    Route::get('dnsh/{questionnaire}/activity/{code}/objective/{objective?}', [LivewireControllers\Questionnaires\Taxonomy\NpsQuestionnaire::class, '__invoke'])
                        ->name('taxonomy.dnsh.questionnaire')
                        ->where(['questionnaire', '[0-9]+', 'activity', '[0-9]+', 'objective', '[0-9]+']);

                        Route::get('safeguards/{questionnaire}', [LivewireControllers\Questionnaires\Taxonomy\Safeguards::class, '__invoke'])
                            ->name('taxonomy.safeguards')
                            ->where(['questionnaire', '[0-9]+']);

                        Route::get('/{questionnaire}', [LivewireControllers\Questionnaires\Taxonomy\Show::class, '__invoke'])
                            ->name('taxonomy.show')
                            ->where('questionnaire', '[0-9]+');
                    });
                });

                Route::middleware(['can:questionnaires.view'])->group(function () {
                    Route::prefix('physicalrisks')->group(function () {
                        Route::get('{questionnaire}', [LivewireControllers\Questionnaires\Physicalrisks\Index::class, '__invoke'])
                            ->name('physicalrisks.index');
                        Route::get('{questionnaire}/report', [LivewireControllers\Questionnaires\Physicalrisks\Index::class, '__invoke'])
                            ->name('physicalrisks.index.report');
                    });

                    Route::prefix('co2calculator')->group(function () {
                        Route::get('{questionnaire}', [LivewireControllers\Questionnaires\Co2calculator\Index::class, '__invoke'])
                            ->name('co2calculator.index');
                    });

                    Route::prefix('double-materiality')->group(function () {
                        Route::get('{questionnaire}', [LivewireControllers\Questionnaires\DoubleMateriality\Index::class, '__invoke'])
                            ->name('double-materiality.index');
                    });
                });

                Route::middleware(['can:questionnaires.view'])->group(function () {
                    Route::get('/{questionnaire}/data', [
                        LivewireControllers\Questionnaires\Data::class,
                        '__invoke'
                    ])->name('questionnaires.data');

                    Route::get('/{questionnaire}/{category?}/{questionHighlighted?}/{assigner?}', [
                        LivewireControllers\Questionnaires\Show::class,
                        '__invoke',
                    ])->name('questionnaires.show');
                });
            });

        Route::prefix('reports')->group(function () {
            Route::get('/', [
                Index::class,
                '__invoke',
            ])->name('exports.index');

            Route::get('/{id}/{action}', [
                SourceReport::class,
                '__invoke',
            ])->name('exports.show')->where('action', 'edit|view');

            Route::get('/create', [
                Create::class,
                '__invoke',
            ])->name('exports.create');
        });

        Route::middleware(['can:companies.view', 'user.hasAccess:company'])->group(function () {
            Route::get('/companies', [
                LivewireControllers\Companies\Index::class,
                '__invoke',
            ])->name('companies.index');

            Route::get('/companies/list', [
                LivewireControllers\Companies\Lista::class,
                '__invoke',
            ])->name('companies.list');

            Route::get('/companies/welcome', [
                LivewireControllers\Companies\Welcome::class,
                '__invoke',
            ])->name('companies.welcome');

            Route::get('/companies/form/{company?}', [
                LivewireControllers\Companies\Form::class,
                '__invoke',
            ])->name('companies.form');

            Route::get('/companies/{company}', [
                LivewireControllers\Companies\Show::class,
                '__invoke',
            ])->name('companies.show');
        });

        Route::middleware(['can:companies.view', 'feature.enabled:api-token'])->group(function () {
            Route::get('/api-tokens', [
                LivewireControllers\Api\Tokens\Index::class,
                '__invoke',
            ])->name('api.tokens.index');
        });

        Route::middleware(['can:roles.view'])->group(function () {
            Route::get('/roles', [
                LivewireControllers\Roles\Index::class,
                '__invoke',
            ])->name('roles.index');

            Route::get('/roles/form/{role?}', [
                LivewireControllers\Roles\Form::class,
                '__invoke',
            ])->name('roles.form');

            Route::get('/roles/{role}', [
                LivewireControllers\Roles\Show::class,
                '__invoke',
            ])->name('roles.show');
        });

        Route::middleware(['can:users.view', 'user.hasAccess:user'])->group(function () {
            Route::get('/users', [LivewireControllers\Users\Index::class, '__invoke'])
                ->name('users.index');

            Route::get('/users/form/{user?}', [LivewireControllers\Users\Form::class, '__invoke'])
                ->name('users.form');

            Route::get('/users/{user}', [LivewireControllers\Users\Show::class, '__invoke'])
                ->name('users.show');
        });

        Route::middleware(['role:Super Admin'])->withoutMiddleware(InsufficientFunds::class)->group(function () {
            Route::get('/settings/audit-log', [
                LivewireControllers\ActivityLog\Index::class,
                '__invoke',
            ])
                ->name('settings.application');

            Route::get('/settings/application', [
                Controllers\ApplicationSettingsController::class,
                'show',
            ])
                ->name('settings.application');
            Route::post('/settings/application/configuration', [
                Controllers\ApplicationSettingsController::class,
                'storeConfiguration',
            ])->name('settings.application.configuration');

            Route::post('/settings/application/logo', [
                Controllers\ApplicationSettingsController::class,
                'logo',
            ])->name('settings.application.logo');

            Route::get('/settings/application/invoice/{id}/download', [
                Controllers\DownloadInvoiceController::class,
                '__invoke',
            ])->name('invoice.download');

            Route::get('/settings/questions/data/download', [
                Controllers\DownloadQuestionDataController::class,
                'index',
            ])->name('settings.question.download');

            Route::get('/wallet', [
                LivewireControllers\Wallet\Index::class, '__invoke'
            ])->name('wallet');
        });

        Route::middleware(['can:compliance.document_analysis.view', 'feature.enabled:compliance'])->group(function () {
            Route::get('/compliance/document_analysis', [
                LivewireControllers\Compliance\DocumentAnalysis\Index::class, '__invoke',
            ])->name('compliance.document_analysis.index');

            Route::get('/compliance/document_analysis/{result}', [
                LivewireControllers\Compliance\DocumentAnalysis\Show::class,
                '__invoke',
            ])->name('compliance.document_analysis.show');
        });

        Route::middleware(['can:reputation.view', 'feature.enabled:reputation'])->group(function () {
            Route::get('/reputation', [
                LivewireControllers\Compliance\Reputational\Index::class, '__invoke',
            ])->name('reputation.index');

            Route::get('/reputation/{analysisInfo}', [
                LivewireControllers\Compliance\Reputational\Index::class, '__invoke',
            ])->name('reputation.show');
        });

        Route::middleware(['can:compliance.legislation.view', 'feature.enabled:compliance'])->group(function () {
            Route::get('/compliance/legislation', [
                LivewireControllers\Compliance\Legislation\Index::class, '__invoke',
            ])->name('compliance.legislation.index');
        });

        Route::middleware(['can:tags.view', 'feature.enabled:tags'])->group(function () {
            Route::get('/tags', [LivewireControllers\Tags\Index::class, '__invoke'])
                ->name('tags.index');

            Route::get('/tags/{tag}', [
                LivewireControllers\Tags\Show::class, '__invoke',
            ])->name('tags.show');
        });

        Route::middleware(['feature.enabled:tasks'])->group(function () {
            Route::get('/user/tasks', [LivewireControllers\Tasks\Index::class, '__invoke'])
                ->name('users.tasks.index');

            Route::get('/users/tasks/form/{task?}', [LivewireControllers\Tasks\Form::class, '__invoke'])
                ->name('users.tasks.form');

            Route::get('/user/my-todo-list/{task}', [LivewireControllers\Tasks\Show::class, '__invoke'])
                ->name('users.tasks.show');
        });

        Route::group(['prefix' => 'reporting-periods', 'middleware' => []], function () {
            Route::get('/', [
                LivewireControllers\ReportingPeriods\Index::class,
                '__invoke',
            ])->name('reporting-periods.index');

            Route::get('/form/{reportingPeriod}', [
                LivewireControllers\ReportingPeriods\Form::class,
                '__invoke',
            ])->name('reporting-periods.form');
        });
    });

    Route::namespace('App\\Http\\Controllers\\Tenant')->group(function () {
        Auth::routes(['verify' => true]);
    });

    /**
     * Unauthenticated API
     *
     * TODO :: Refactor
     *
     * Note: fast solution for Turismo de Portugal
     */
    Route::group([
        'as' => 'tenant.88bd07c3-05a9-4a30-b732-c865d7ccbce1.api.',
    ], function () {
        Route::get('/api/auth', [
            Controllers\Api\Tenant88bd07c3_05a9_4a30_b732_c865d7ccbce1\Auth::class,
            'generateToken',
        ]);
    });


    Route::get('bo/backoffice/clear-cache', [BackofficeActionsController::class, 'clearCache'])
        ->name('clear-cache');
});
