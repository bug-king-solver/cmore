<?php

namespace App\Nova\Central;

use App\Models\Enums\Companies\Relation;
use App\Models\Enums\Companies\Type as CompanyTYpeEnum;
use App\Models\Enums\ProductType;
use App\Models\Enums\Users\Type;
use App\Models\Tenant\Scopes\EnabledScope;
use App\Nova\Central\Actions\AddTransaction;
use App\Nova\Central\Actions\Tenant\DocuSign\RequestSign;
use App\Nova\Central\Actions\Tenant\ExportDataToExcel;
use App\Nova\Central\Crm\Deal;
use App\Nova\Central\Invoicing\Document;
use App\Nova\Central\Transaction;
use App\Nova\CustomResource;
use App\Nova\Metrics\SubmittedQuestionnaires;
use App\Nova\Metrics\TotalQuestionnaires;
use App\Nova\Metrics\TotalUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasManyThrough;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Panel;
use Spatie\Activitylog\Models\Activity;

class Tenant extends CustomResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Tenant::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'email',
        'data->name',
        'data->company',
    ];

    /**
     * Modify the index query used to retrieve models for the resource.
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->withoutGlobalScope(EnabledScope::class);
        return parent::indexQuery($request, $query);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  NovaRequest $request
     * @return array<mixed>
     */
    public function fields(NovaRequest $request): array
    {
        $panel = new Panel('General Info', $this->fieldsForPanelInfo($request));
        $wallet = new Panel('Wallet', $this->fieldsForWallet($request));
        $users = new Panel('Users', $this->fieldsForUsers($request));
        $companies = new Panel('Companies', $this->fieldsForCompanies($request));
        $menus = new Panel('Menus', $this->fieldsForMenus($request));
        $termsAndConditions = new Panel('Terms and Conditions', $this->fieldsForTerms($request));
        $privacyPolicy = new Panel('Privacy Policy', $this->fieldsForPrivacy($request));

        $fields = [
            $panel->help("Set the tenant information. "),
        ];

        if ($this->isEsgEnvironment) {
            $fields[] = $wallet->help("Set the wallet information of the tenant. ");
            $fields[] = $users->help("Set the users permissions information of the tenant. ");
            $fields[] = $companies->help("Set the companies permissions information of the tenant. ");
            $fields[] = $menus->limit(3)->help("Set the menus of the tenant. ");
            $fields[] = new Panel('Relations', $this->fieldsForRelations($request));
        }

        $fields[] = $termsAndConditions->help("Change the tenant terms and conditions.");
        $fields[] = $privacyPolicy->help("Change the tenant privacy policy");

        return $fields;
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForPanelInfo(NovaRequest $request): array
    {
        return [

            Text::make('ID')
                ->sortable()
                ->help('Optional.')
                ->rules('nullable', 'max:254')
                ->creationRules('unique:tenants,id')
                ->updateRules('unique:tenants,id,{{resourceId}}')
                ->onlyOnDetail(),

            Boolean::make('Disabled')
                ->resolveUsing(function ($value) {
                    return $this->disabled;
                }),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:tenants,email')
                ->updateRules('unique:tenants,email,{{resourceId}}'),

            Text::make('Name')
                ->resolveUsing(function ($value) {
                    return $this->name;
                })
                ->rules('required', 'max:255'),

            Text::make('Company')
                ->resolveUsing(function ($value) {
                    return $this->company;
                })
                ->rules('required', 'max:255'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            DateTime::make('Trial until', 'trial_ends_at')->rules('required')
                ->default(Carbon::now()->addDays(config('saas.trial_days'))),

            Boolean::make('Ready')->readonly(),

            Boolean::make('Terms & Conditions', 'require_to_accept_terms_and_conditions')
                ->rules('nullable')
                ->default(fn () => true)
                ->trueValue(false)
                ->falseValue(true)
                ->exceptOnForms(),

            Boolean::make('Require to Terms & Conditions', 'require_to_accept_terms_and_conditions')
                ->rules('nullable')
                ->default(fn () => true)
                ->onlyOnForms(),

            Code::make('Features', 'features')->json()->rules('json', 'nullable'),

            Boolean::make('Only own data')
                ->rules('nullable')
                ->resolveUsing(fn () => $this->seeOnlyOwnData)
                ->exceptOnForms(),

            Currency::make('Wallet Balance', 'balanceFloat')
                ->rules('nullable')
                ->exceptOnForms()
                ->canSee(fn () => Nova::authorizedResources($request)->contains(Transaction::class) && $this->hasWalletFeature),

            Select::make('Product Type', 'features->product->type')
                ->resolveUsing(fn () => $this->features['product']['type'] ?? '')
                ->options(ProductType::casesArray())
                ->canSee(fn () => $this->isEsgEnvironment),

            Text::make('Custom Layout', 'features->layout->dir')
                ->resolveUsing(function ($value) {
                    return $this->features['layout']['dir'] ?? '';
                })
                ->help('Usually , set the {tenants.} before the folder name')
                ->hideFromIndex()
                ->rules('max:255'),

            Boolean::make('Sharing Data', 'features->sharing->enabled')
                ->resolveUsing(fn () => $this->features['sharing']['enabled'] ?? false),

            Text::make('Backoffice', function () {
                $url = $this->backofficeUrl ?? '';
                return "<a class='text-center inline-flex hover:text-primary-500' href='{$url}' target='_blank'><svg fill='#fff' width='24px' height='24px' viewBox='0 0 64 64' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' xml:space='preserve' xmlns:serif='http://www.serif.com/' style='fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;'> <rect id='Icons' x='-896' y='0' width='1280' height='800' style='fill:none;' /> <g id='Icons1' serif:id='Icons'> <g id='Strike'> </g> <g id='H1'> </g> <g id='H2'> </g> <g id='H3'> </g> <g id='list-ul'> </g> <g id='hamburger-1'> </g> <g id='hamburger-2'> </g> <g id='list-ol'> </g> <g id='list-task'> </g> <g id='trash'> </g> <g id='vertical-menu'> </g> <g id='horizontal-menu'> </g> <g id='sidebar-2'> </g> <g id='Pen'> </g> <g id='Pen1' serif:id='Pen'> </g> <g id='clock'> </g> <g id='external-link'> <path d='M36.026,20.058l-21.092,0c-1.65,0 -2.989,1.339 -2.989,2.989l0,25.964c0,1.65 1.339,2.989 2.989,2.989l26.024,0c1.65,0 2.989,-1.339 2.989,-2.989l0,-20.953l3.999,0l0,21.948c0,3.308 -2.686,5.994 -5.995,5.995l-28.01,0c-3.309,0 -5.995,-2.687 -5.995,-5.995l0,-27.954c0,-3.309 2.686,-5.995 5.995,-5.995l22.085,0l0,4.001Z' /> <path d='M55.925,25.32l-4.005,0l0,-10.481l-27.894,27.893l-2.832,-2.832l27.895,-27.895l-10.484,0l0,-4.005l17.318,0l0.002,0.001l0,17.319Z' /> </g> <g id='hr'> </g> <g id='info'> </g> <g id='warning'> </g> <g id='plus-circle'> </g> <g id='minus-circle'> </g> <g id='vue'> </g> <g id='cog'> </g> <g id='logo'> </g> <g id='radio-check'> </g> <g id='eye-slash'> </g> <g id='eye'> </g> <g id='toggle-off'> </g> <g id='shredder'> </g> <g id='spinner--loading--dots-' serif:id='spinner [loading, dots]'> </g> <g id='react'> </g> <g id='check-selected'> </g> <g id='turn-off'> </g> <g id='code-block'> </g> <g id='user'> </g> <g id='coffee-bean'> </g> <g id='coffee-beans'> <g id='coffee-bean1' serif:id='coffee-bean'> </g> </g> <g id='coffee-bean-filled'> </g> <g id='coffee-beans-filled'> <g id='coffee-bean2' serif:id='coffee-bean'> </g> </g> <g id='clipboard'> </g> <g id='clipboard-paste'> </g> <g id='clipboard-copy'> </g> <g id='Layer1'> </g> </g></svg></a>";
            })
                ->canSee(fn () => $this->isEsgEnvironment)
                ->asHtml(),

            Code::make('Logs')
                ->json()
                ->resolveUsing(
                    fn () => (new Activity())
                        ->setTable('tenant' . $this->id . '.' . config('activitylog.table_name'))
                        ->select('created_at', 'description')
                        ->where(function ($sql) {

                            $sql->where('event', 'seeding')
                                ->Orwhere('event', 'like', '%batch%')
                                ->orWhere('event', 'like', '%questionnaire%');
                        })
                        ->orderBy('id', 'desc')
                        ->limit(250)
                        ->get()
                        ->map(fn ($row) => "{$row['created_at']} {$row['description']}")
                        ->join("\n")
                )
                ->onlyOnDetail(),
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForWallet(NovaRequest $request): array
    {
        return [
            Boolean::make('Enable', 'features->wallet->enabled')
                ->resolveUsing(fn () => $this->features['wallet']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Credit', 'features->wallet->credit->enabled')
                ->resolveUsing(fn () => $this->features['wallet']['credit']['enabled'] ?? false)
                ->hideFromIndex(),
            Currency::make('Credit Limit', 'features->wallet->credit->limit')
                ->resolveUsing(fn () => $this->features['wallet']['credit']['limit'] ?? 0)
                ->hideFromIndex(),
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForUsers(NovaRequest $request): array
    {
        return  [

            Boolean::make('Required Accept Terms and Conditions', 'features->users->require_to_accept_terms_and_conditions')
                ->resolveUsing(fn () => $this->features['users']['require_to_accept_terms_and_conditions'] ?? false)
                ->hideFromIndex(),


            Boolean::make('See only own data', 'features->users->permissions->only_owned_data')
                ->resolveUsing(fn () => $this->features['users']['permissions']['only_owned_data'] ?? false)
                ->hideFromIndex(),
            Boolean::make('See only own data (internal)', 'features->users->internal->permissions->only_owned_data')
                ->resolveUsing(fn () => $this->features['users']['internal']['permissions']['only_owned_data'] ?? false)
                ->hideFromIndex(),
            Boolean::make('See only own data (external)', 'features->users->external->permissions->only_owned_data')
                ->resolveUsing(fn () => $this->features['users']['external']['permissions']['only_owned_data'] ?? false)
                ->hideFromIndex(),

            Select::make('Users Type', 'features->users->type->default')
                ->resolveUsing(fn () => $this->features['users']['type']['default'] ?? '')
                ->options(Type::casesArray()),

            Boolean::make('Users Type Self Managed', 'features->users->type->self_managed')
                ->resolveUsing(fn () => $this->features['users']['type']['self_managed'] ?? false)
                ->hideFromIndex(),

            Boolean::make('Users Type In Menu', 'features->users->type->available_in_menu')
                ->resolveUsing(fn () => $this->features['users']['type']['available_in_menu'] ?? false)
                ->hideFromIndex(),
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForCompanies(NovaRequest $request): array
    {
        return  [

            Select::make('Company Type', 'features->companies->type->default')
                ->resolveUsing(fn () => $this->features['companies']['type']['default'] ?? '')
                ->options(CompanyTypeEnum::casesArray()),
            Boolean::make('Company Type Self Managed', 'features->companies->type->self_managed')
                ->resolveUsing(fn () => $this->features['companies']['type']['self_managed'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Company Type In Menu', 'features->companies->type->available_in_menu')
                ->resolveUsing(fn () => $this->features['companies']['type']['available_in_menu'] ?? false)
                ->hideFromIndex(),

            Select::make('Company Relation Default', 'features->companies->relation->default')
                ->resolveUsing(fn () => $this->features['companies']['relation']['default'] ?? '')
                ->options(Relation::casesArray()),
            Boolean::make('Company Relation Self Managed', 'features->companies->relation->self_managed')
                ->resolveUsing(fn () => $this->features['companies']['relation']['self_managed'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Company Relation In Menu', 'features->companies->relation->available_in_menu')
                ->resolveUsing(fn () => $this->features['companies']['relation']['available_in_menu'] ?? false)
                ->hideFromIndex(),
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForMenus(NovaRequest $request): array
    {
        return  [
            Boolean::make('Reputational', 'features->reputation->enabled')
                ->resolveUsing(fn () => $this->features['reputation']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Reporting', 'features->reporting->enabled')
                ->resolveUsing(fn () => $this->features['reporting']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Benchmarking', 'features->benchmarking->enabled')
                ->resolveUsing(fn () => $this->features['benchmarking']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Compliance', 'features->compliance->enabled')
                ->resolveUsing(fn () => $this->features['compliance']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Targets', 'features->targets->enabled')
                ->resolveUsing(fn () => $this->features['targets']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Monitoring', 'features->monitoring->enabled')
                ->resolveUsing(fn () => $this->features['monitoring']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Dynamic Dashboard', 'features->dynamic-dashboard->enabled')
                ->resolveUsing(fn () => $this->features['dynamic-dashboard']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Api Tokens', 'features->api-token->enabled')
                ->resolveUsing(fn () => $this->features['api-token']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Tags', 'features->tags->enabled')
                ->resolveUsing(fn () => $this->features['tags']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Tasks', 'features->tasks->enabled')
                ->resolveUsing(fn () => $this->features['tasks']['enabled'] ?? false)
                ->hideFromIndex(),
            Boolean::make('Gar & BTar', 'features->garbtar->enabled')
                ->resolveUsing(fn () => $this->features['garbtar']['enabled'] ?? false)
                ->hideFromIndex(),

            Boolean::make('Catalog', 'features->catalog->enabled')
                ->resolveUsing(fn () => $this->features['catalog']['enabled'] ?? false)
                ->hideFromIndex(),

            Boolean::make('Reporting Periods', 'features->reporting_periods->self_managed')
                ->resolveUsing(fn () => $this->features['reporting_periods']['self_managed'] ?? false)
                ->hideFromIndex(),
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForTerms(NovaRequest $request): array
    {
        return [
            File::make('PT', 'features->terms_conditions->pt-PT')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['terms_conditions']['pt-PT'] ?? false),

            File::make('BR', 'features->terms_conditions->pt-BR')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['terms_conditions']['pt-BR'] ?? false),

            File::make('ES', 'features->terms_conditions->es')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['terms_conditions']['es'] ?? false),

            File::make('FR', 'features->terms_conditions->fr')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['terms_conditions']['fr'] ?? false),

            File::make('EN', 'features->terms_conditions->en')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['terms_conditions']['en'] ?? false),
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForPrivacy(NovaRequest $request): array
    {
        return [
            File::make('PT', 'features->privacy_policy->pt-PT')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['privacy_policy']['pt-PT'] ?? false),

            File::make('BR', 'features->privacy_policy->pt-BR')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['privacy_policy']['pt-BR'] ?? false),

            File::make('ES', 'features->privacy_policy->es')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['privacy_policy']['es'] ?? false),

            File::make('FR', 'features->privacy_policy->fr')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['privacy_policy']['fr'] ?? false),

            File::make('EN', 'features->privacy_policy->en')
                ->acceptedTypes('.pdf')
                ->disk('public')
                ->path("{$this->resource->tenantPublicCentralStorage}/docs")
                ->resolveUsing(fn () => $this->features['privacy_policy']['en'] ?? false),
        ];
    }

    /**
     * @param NovaRequest $request
     * @return array<mixed>
     */
    public function fieldsForRelations(NovaRequest $request): array
    {
        return [

            HasOne::make("Primary Domain", "primaryDomain", Domain::class),

            HasMany::make('Domains', 'domains', Domain::class)
                ->canSee(fn () => Nova::authorizedResources($request)->contains(Domain::class)),

            MorphMany::make('Wallet Transactions', 'transactions', Transaction::class)
                ->canSee(fn () => Nova::authorizedResources($request)->contains(Transaction::class) && $this->has_wallet_feature),

            HasMany::make('Payments', 'payments', Payment::class)
                ->canSee(fn () => Nova::authorizedResources($request)->contains(Payment::class)),

            HasMany::make('Invoicing Documents', 'invoicing_documents', Document::class)
                ->canSee(fn () => Nova::authorizedResources($request)->contains(Document::class)),

            HasMany::make('Deals', 'deals', Deal::class)
                ->canSee(fn () => Nova::authorizedResources($request)->contains(Deal::class)),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(NovaRequest $request): array
    {
        if (!$this->isEsgEnvironment) {
            return [];
        }

        $tenantBackOfficeEnabledMenus = explode(',', config('app.tenant_bo_menus'));
        $cards = [];

        $tenantId = $request->resourceId ?? $request->tenantId ?? null;

        if (in_array('User', $tenantBackOfficeEnabledMenus)) {
            $cards[] = (new TotalUsers())->withMeta(['tenantId' => $tenantId])->onlyOnDetail();
        }

        if (in_array('Questionnaire', $tenantBackOfficeEnabledMenus)) {
            $cards = array_merge($cards, [
                (new TotalQuestionnaires())->withMeta(['tenantId' => $tenantId])->onlyOnDetail(),
                (new SubmittedQuestionnaires())->withMeta(['tenantId' => $tenantId])->onlyOnDetail(),
            ]);
        }

        return $cards;
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new Filters\Enabled(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {

        $actions = [
            (new Actions\ImpersonateTenant())->showInline(),
            new Actions\Enabled(),
            Actions\Tenant\SeedTenantDatabaseAll::make('update'),
            Actions\Tenant\SeedTenantDatabaseAll::make('all'),
        ];

        if ($this->isEsgEnvironment) {
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'business-sectors');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'indicators');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'initiatives');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'internal-tags');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'products');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'sdgs');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'sources');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'questionnaires');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'taxonomy');
            $actions[] = Actions\Tenant\SeedTenantDatabaseAll::make('all', 'permissions');

            if (Nova::authorizedResources($request)->contains(Transaction::class)) {
                $actions[] = (new AddTransaction())
                    ->showInline()
                    ->confirmText(null)
                    ->confirmButtonText(__('Add transaction'));
            }

            $actions[] = (new RequestSign())->showInline();
            $actions[] = new ExportDataToExcel();
        }


        return $actions;
    }

    public static function authorizedToCreate(Request $request)
    {
        return true;
    }
}
