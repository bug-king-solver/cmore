<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Nova\Central\Admin;
use App\Nova\Central\BenchmarkCompany;
use App\Nova\Central\BenchmarkData;
use App\Nova\Central\BenchmarkPeriod;
use App\Nova\Central\BenchmarkReport;
use App\Nova\Central\Charts;
use App\Nova\Central\Crm\Company;
use App\Nova\Central\Crm\Contact;
use App\Nova\Central\Crm\Deal;
use App\Nova\Central\Domain;
use App\Nova\Central\Indicator;
use App\Nova\Central\Invoicing\Document;
use App\Nova\Central\Payment;
use App\Nova\Central\SubscriptionCancelation;
use App\Nova\Central\Tenant as CentralTenant;
use App\Nova\Central\Transaction;
use App\Nova\Dashboards\Main;
use App\Nova\Tenant\Answer;
use App\Nova\Tenant\Api\ApiTokens;
use App\Nova\Tenant\BusinessSector;
use App\Nova\Tenant\BusinessSectorType;
use App\Nova\Tenant\Category;
use App\Nova\Tenant\Initiative;
use App\Nova\Tenant\Products;
use App\Nova\Tenant\Question;
use App\Nova\Tenant\QuestionOption;
use App\Nova\Tenant\QuestionOptions\Matrix;
use App\Nova\Tenant\QuestionOptions\Simple;
use App\Nova\Tenant\Questionnaire;
use App\Nova\Tenant\QuestionnaireType;
use App\Nova\Tenant\Saml2Tenants;
use App\Nova\Tenant\Sdg;
use App\Nova\Tenant\SharingOption;
use App\Nova\Tenant\Source;
use App\Nova\Tenant\User;
use App\Nova\Tools\ClearLaravelCache;
use App\Nova\Tools\NovaTwoFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::serving(function () {
            Tenant::creating(function (Tenant $tenant) {
                $tenant->ready = false;
            });

            Tenant::created(function (Tenant $tenant) {
                //$tenant->createAsStripeCustomer();
            });
        });

        Nova::mainMenu(function (Request $request) {

            if (tenancy()->initialized) {
                return [
                    MenuSection::dashboard(Main::class)->icon('chart-bar'),

                    MenuSection::make("Questionnaires", [
                        MenuItem::resource(Questionnaire::class)->name('Questionnaires')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Questionnaire::class)),
                        MenuItem::resource(QuestionnaireType::class)->name('Types')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(QuestionnaireType::class)),
                    ])->icon('collection')->collapsable(),

                    MenuSection::make("Quests Config", [
                        MenuItem::resource(Category::class)->name('Categories')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Category::class)),
                        MenuItem::resource(Question::class)->name('Questions')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Question::class)),
                        MenuItem::resource(Matrix::class)->name('Matrix Options')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Matrix::class)),
                        MenuItem::resource(QuestionOption::class)->name('Question Options')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(QuestionOption::class)),
                        MenuItem::resource(Sdg::class)->name('SDGs')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Sdg::class)),
                        MenuItem::resource(Simple::class)->name('Simple Options')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Simple::class)),
                    ])->icon('academic-cap')->collapsable(),

                    MenuSection::make("Quests Data", [
                        MenuItem::resource(Answer::class)->name('Answers')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Answer::class)),
                        MenuItem::resource(Initiative::class)->name('Initiatives')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Initiative::class)),
                        MenuItem::resource(Source::class)->name('Sources')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Source::class)),
                    ])->icon('database')->collapsable(),

                    MenuSection::make('Business', [
                        MenuItem::resource(BusinessSector::class)->name('Sectors')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(BusinessSector::class)),
                        MenuItem::resource(BusinessSectorType::class)->name('Sector Types')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(BusinessSectorType::class)),
                    ])->icon('document-text')->collapsable(),

                    MenuSection::make("Manage", [
                        MenuItem::resource(Company::class)->name('Companies')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Company::class)),
                        MenuItem::resource(Document::class)->name('Documents')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Document::class)),
                        MenuItem::resource(Products::class)->name('Products')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Products::class)),
                        MenuItem::resource(SharingOption::class)->name('Sharing Options')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(SharingOption::class)),
                    ])->icon('adjustments')->collapsable(),

                    MenuSection::make('Users', [
                        MenuItem::resource(ApiTokens::class)->name('API Tokens')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(ApiTokens::class)),
                        MenuItem::resource(Saml2Tenants::class)->name('SAML2')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Saml2Tenants::class)),
                        MenuItem::resource(User::class)->name('Users')
                            ->canSee(fn ($request) => Nova::authorizedResources($request)->contains(User::class)),
                    ])->icon('users')->collapsable(),

                    MenuSection::make("Settings", [
                        MenuItem::make('Clear Laravel Cache', '/backoffice/clear-cache'),
                    ])->icon('cog'),
                ];
            }

            return [

                MenuSection::dashboard(Main::class)->icon('chart-bar'),

                MenuSection::make('Clients', [
                    MenuItem::resource(Domain::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Domain::class)),
                    MenuItem::resource(CentralTenant::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(CentralTenant::class)),
                    MenuItem::resource(Transaction::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Transaction::class)),
                ])->icon('users')->collapsable(),

                MenuSection::make('Invoicing', [
                    MenuItem::resource(Document::class)->name('Documents')->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Document::class)),
                    MenuItem::resource(Payment::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Payment::class)),
                    MenuItem::resource(SubscriptionCancelation::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(SubscriptionCancelation::class)),
                ])->icon('cash')->collapsable(),

                MenuSection::make('CRM', [
                    MenuItem::resource(Contact::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Contact::class)),
                    MenuItem::resource(Company::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Company::class)),
                    MenuItem::resource(Deal::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Deal::class)),
                ])->icon('presentation-chart-line')->collapsable(),

                MenuSection::make('Data Entry', [
                    MenuItem::resource(BenchmarkPeriod::class)->name('Periods')->canSee(fn ($request) => Nova::authorizedResources($request)->contains(BenchmarkPeriod::class)),
                    MenuItem::resource(BenchmarkCompany::class)->name('Companies')->canSee(fn ($request) => Nova::authorizedResources($request)->contains(BenchmarkCompany::class)),
                    MenuItem::resource(BenchmarkReport::class)->name('Reports')->canSee(fn ($request) => Nova::authorizedResources($request)->contains(BenchmarkReport::class)),
                    MenuItem::resource(BenchmarkData::class)->name('Data')->canSee(fn ($request) => Nova::authorizedResources($request)->contains(BenchmarkData::class)),
                    MenuItem::resource(Indicator::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Indicator::class)),
                ])->icon('database')->collapsable(),

                MenuSection::make('Manage', [
                    MenuItem::resource(Charts::class)->name('Charts')->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Charts::class)),
                ])->icon('presentation-chart-bar')->collapsable(),

                MenuSection::make('Users', [
                    MenuItem::resource(Admin::class)->canSee(fn ($request) => Nova::authorizedResources($request)->contains(Admin::class)),
                ])->icon('user')->collapsable(),
            ];
        });

        Nova::withBreadcrumbs();

        Nova::footer(function ($request) {
            return Blade::render('<p class="text-center"> Powered by <a href="https://cmore-sustainability.com/" class="link-default">C-MORE</a> · v{!! $version !!}</p>', [
                'version' => 1.0,
                'year' => "2021",
            ]);
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes(['tenant', 'universal', 'nova'])
            ->withPasswordResetRoutes(['tenant', 'universal', 'nova'])
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user instanceof \App\Models\Admin;
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar
     *
     * @return array
     */

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        $dashboards = [];
        $dashboards[] = Main::make()->showRefreshButton();
        return $dashboards;
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        $tools = [];
        if (config('nova-two-factor.enabled')) {
            $tools[] = new NovaTwoFactor();
        }
        if (tenancy()->initialized) {
            $tools[] = new ClearLaravelCache();
        }

        return $tools;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Control which resources are available for the given request.
     */
    protected function resources()
    {
        if (tenancy()->initialized) {
            /* Tenant login */

            $tenantBackOfficeEnabledMenus = explode(',', config('app.tenant_bo_menus'));
            //remove empty values
            $tenantBackOfficeEnabledMenus = array_filter($tenantBackOfficeEnabledMenus);
            $tenantResourceClasses = !empty($tenantBackOfficeEnabledMenus) ? array_map(function ($class) {
                return 'App\Nova\Tenant\\' . trim($class);
            }, $tenantBackOfficeEnabledMenus) : [];
            Nova::resources($tenantResourceClasses);
        } else {
            $backOfficeEnabledMenus = explode(',', config('app.central_bo_menus'));
            //remove empty values
            $backOfficeEnabledMenus = array_filter($backOfficeEnabledMenus);
            $resourceClasses = !empty($backOfficeEnabledMenus) ? array_map(function ($class) {
                return 'App\Nova\Central\\' . trim($class);
            }, $backOfficeEnabledMenus) : [];

            Nova::resources($resourceClasses);
        }
    }
}
