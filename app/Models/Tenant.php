<?php

namespace App\Models;

use App\Exceptions\NoPrimaryDomainException;
use App\Models\Crm\Deal;
use App\Models\Enums\Companies\Relation;
use App\Models\Enums\PasswordStrength;
use App\Models\Enums\ProductType;
use App\Models\Enums\User\UserType;
use App\Models\Enums\Users\Type;
use App\Models\Invoicing\Document;
use App\Models\Tenant\Saml2Tenants;
use App\Models\Traits\TennantAdvanceFeatures;
use App\Notifications\NewTenantNotification;
use Bavix\Wallet\Interfaces\Confirmable;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\CanConfirm;
use Bavix\Wallet\Traits\CanPay;
use Bavix\Wallet\Traits\CanPayFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

/**
 * @property-read string $plan_name The tenant's subscription plan name
 * @property-read bool $on_active_subscription Is the tenant actively subscribed (not on grace period)
 * @property-read bool $can_use_app Can the tenant use the application (is on trial or subscription)
 *
 * @property-read Domain[]|Collection $domains
 */
class Tenant extends BaseTenant implements TenantWithDatabase, Wallet, WalletFloat, Confirmable
{
    use HasFactory;
    use HasDatabase;
    use HasDomains;
    //use Billable;
    use Notifiable;
    use TennantAdvanceFeatures;
    use HasWalletFloat;
    use CanConfirm;

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'features' => 'array',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'type',
            'email',
            'stripe_id',
            'pm_type',
            'pm_last_four',
            'trial_ends_at',
        ];
    }


    public static function boot()
    {
        parent::boot();

        static::created(function ($tenant) {
            if ($tenant->features != '') {
                $features = parseStringToArray($tenant->features);
                DB::connection('central')->table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['data->features' => $features]);
            }
        });

        static::updated(function ($tenant) {
            if ($tenant->features != '') {
                $features = parseStringToArray($tenant->features);
                DB::connection('central')->table('tenants')
                    ->where('id', $tenant->id)
                    ->update(['data->features' => $features]);
            }
        });
    }

    /**
     * Check if is a event (each user can see only what he created)
     * @return Attribute
     */
    public function isEnabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !$this->is_disabled,
        );
    }

    /**
     * Check if is a event (each user can see only what he created)
     * @return Attribute
     */
    public function isDisabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['disabled'] ?? false,
        );
    }

    /**
     * Determine if the tenant has the dynamic dashboard feature
     * @return Attribute
     */
    public function hasDynamicDashboardFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['dynamic-dashboard']['enabled'] ?? false,
        );
    }

    /**
     * Determine if the tenant has the monitoring feature
     * @return Attribute
     */
    public function hasMonitoringFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['monitoring']['enabled'] ?? false,
        );
    }

    /**
     * Determine if the tenant has the targets feature
     * @return Attribute
     */
    public function hasTargetsFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['targets']['enabled'] ?? false,
        );
    }

    /**
     * Determine if the tenant has the tasks feature
     * @return Attribute
     */
    public function hasTasksFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['tasks']['enabled'] ?? false,
        );
    }

    /**
     * Determine if the tenant has the benchmarking feature
     * @return Attribute
     */
    public function hasBenchmarkingFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['benchmarking']['enabled'] ?? false,
        );
    }

    /**
     * Determine if the tenant has the compliance feature
     * @return Attribute
     */
    public function hasComplianceFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['compliance']['enabled'] ?? false,
        );
    }

    /**
     * Determine if the tenant has Dashboard link in the menu
     * @return Attribute
     */
    public function menuHasDashboard(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['menu']['dashboard']['enabled'] ?? true,
        );
    }

    /**
     * Determine if the user can see only own data
     * @return Attribute
     */
    public function seeOnlyOwnData(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['users'][auth()->user()->type ?? false]['permissions']['only_owned_data']
                ?? $this['features']['users']['permissions']['only_owned_data']
                ?? false,
        );
    }

    /**
     * Retrieve user type configuration
     * @return Attribute
     */
    public function usersType(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['users']['type'] ?? [],
        );
    }

    /**
     * Retrieve user type » self managed
     * @return Attribute
     */
    public function usersTypeSelfManaged(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->users_type['self_managed'] ?? false,
        );
    }

    /**
     * Retrieve user type » default
     * @return Attribute
     */
    public function usersTypeDefault(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->users_type['default'] ?? Type::INTERNAL->value,
        );
    }

    /**
     * Retrieve user type » self managed
     * @return Attribute
     */
    public function usersTypeAvailableInMenu(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->users_type['available_in_menu'] ?? false,
        );
    }


    /**
     * Retrieve company type configuration
     * @return Attribute
     */
    public function companiesType(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['companies']['type'] ?? [],
        );
    }

    /**
     * Retrieve company type » self managed
     * @return Attribute
     */
    public function companiesTypeSelfManaged(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->companies_type['self_managed'] ?? false,
        );
    }

    /**
     * Retrieve company type » default
     * @return Attribute
     */
    public function companiesTypeDefault(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->companies_type['default'] ?? Type::INTERNAL->value,
        );
    }

    /**
     * Retrieve user type » self managed
     * @return Attribute
     */
    public function companiesTypeAvailableInMenu(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->companies_type['available_in_menu'] ?? false,
        );
    }

    /**
     * Retrieve company relation configuration
     * @return Attribute
     */
    public function companiesRelation(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['companies']['relation'] ?? [],
        );
    }

    /**
     * Retrieve user type » self managed
     * @return Attribute
     */
    public function companiesRelationSelfManaged(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->companies_relation['self_managed'] ?? false,
        );
    }

    /**
     * Retrieve company relation » default
     * @return Attribute
     */
    public function companiesRelationDefault(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->companies_relation['default'] ?? null,
        );
    }

    /**
     * Retrieve user type » self managed
     * @return Attribute
     */
    public function companiesRelationAvailableInMenu(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->companies_relation['available_in_menu'] ?? false,
        );
    }

    /**
     * Get product type
     * @return Attribute
     */
    public function getProductType(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['product']['type'] ?? ProductType::MATURITY->value,
        );
    }

    /**
     * Get product type
     * @return Attribute
     */
    public function isProductTypeMaturity(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->getProductType === ProductType::PRO->value,
        );
    }

    /**
     * Get product type
     * @return Attribute
     */
    public function isProductTypePro(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->getProductType === ProductType::PRO->value,
        );
    }

    /**
     * @return Attribute
     */
    public function isEmailTheAuthenticationUsername(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['authentication']['username']['is_email'] ?? true,
        );
    }

    /**
     * Default questionnaire to be created for all companies
     * @return Attribute
     */
    public function defaultCompanyQuestionnaires(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['companies']['created']['create_questionnaires'] ?? [],
        );
    }

    /**
     * Check if is a poc (tenant has all features)
     * @return Attribute
     */
    public function defaultCustomerQuestionnaires(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['companies']['customers']['created']['create_questionnaires'] ?? [],
        );
    }

    /**
     * Check if is a poc (tenant has all features)
     * @return Attribute
     */
    public function defaultSupplierQuestionnaires(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['companies']['suppliers']['created']['create_questionnaires'] ?? [],
        );
    }

    /**
     * Get tenant currency Code
     * @return Attribute
     */
    public function getDefaultCurrency(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['currency']['default'] ?? 'EUR',
        );
    }

    /**
     * Determine if the user can see only own data
     * @return Attribute
     */
    public function bucketName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => env('AWS_BUCKET', '') . '-tenant' . tenant('id'),
        );
    }

    /**
     * Check if is a poc (tenant has all features)
     * @return Attribute
     */
    public function showTopbar(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['layout']['topbar']['enabled'] ?? false,
        );
    }

    public function primaryDomain()
    {
        return $this->hasOne(Domain::class)->where('is_primary', true);
    }

    public function fallbackDomain()
    {
        return $this->hasOne(Domain::class)->where('is_fallback', true);
    }

    /**
     * A tenant can have multiple payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * A tenant can have multiple invoicing documents
     */
    public function invoicing_documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * A tenant can have multiple deals
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function route($route, $parameters = [], $absolute = true)
    {
        if (!$this->primaryDomain) {
            throw new NoPrimaryDomainException();
        }

        $domain = $this->primaryDomain->domain;

        $parts = explode('.', $domain);
        if (count($parts) === 1) { // If subdomain
            $domain = Domain::domainFromSubdomain($domain);
        }

        return tenant_route($domain, $route, $parameters, $absolute);
    }

    public function impersonationUrl($userId): string
    {
        $token = tenancy()->impersonate($this, $userId, $this->route('tenant.home'), 'web')->token;

        return $this->route('tenant.impersonate', ['token' => $token]);
    }

    /**
     * Get the tenant's subscription plan name.
     *
     * @return string
     */
    public function getPlanNameAttribute(): string
    {
        return config('saas.plans')[$this->subscription('default')->stripe_price];
    }

    /**
     * Check if is a poc (tenant has all features)
     * @return Attribute
     */
    public function passwordStrength(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => PasswordStrength::from($this->features['authentication']['password']['strength'] ?? PasswordStrength::MODERATE->value),
        );
    }

    /**
     * Check if is a poc (tenant has all features)
     * @return Attribute
     */
    public function authenticationAllowedIps(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->features['authentication']['allowed_ips'] ?? [],
        );
    }

    /**
     * Get layout config
     * @return Attribute
     */
    public function layout(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->features['layout'] ?? [],
        );
    }

    /**
     * Get layout custom directory
     * @return Attribute
     */
    public function layoutDir(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->features['layout']['dir'] ?? null,
        );
    }


    /**
     * Get the tenant's views folder.
     * @return Attribute
     */
    public function views(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $instance = config('app.instance');
                $dir = $this->layoutDir ?? (config('tenancy.views.prefix_base') . $this->getTenantKey());
                return "instances.{$instance}.{$dir}.";
            }
        );
    }

    /**
     * Is the tenant actively subscribed (not on grace period).
     *
     * @return string
     */
    public function getOnActiveSubscriptionAttribute(): bool
    {
        return true;
    }

    /**
     * Can the tenant use the application (is on trial or subscription).
     *
     * @return bool
     */
    public function getCanUseAppAttribute(): bool
    {
        return $this->is_enabled;
    }

    public function hasSelfRegistrationEnabled()
    {
        return tenant('self_registration') ?? false;
    }

    public function hasUserManualActivationEnabled()
    {
        return tenant('user_manual_activation') ?? true;
    }

    public function hasUserEmailVerificationEnabled()
    {
        return tenant('user_email_verification') ?? true;
    }

    public function requireToAcceptTermsAndConditions()
    {
        return tenant('require_to_accept_terms_and_conditions') ?? false;
    }

    public function acceptTermsAndConditions()
    {
        $this->update(['require_to_accept_terms_and_conditions' => false]);
    }

    /**
     *  @return Attribute
     */
    public function hasPasswordExpiration(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['authentication']['password']['password_expiration']['enabled'] ?? false,
        );
    }

    /**
     *  @return Attribute
     */
    public function passwordExpirationDays(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['authentication']['password']['password_expiration']['days'] ?? 90,
        );
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification): ?string
    {
        return env('LOG_SLACK_WEBHOOK_URL');
    }

    public function getSaml2()
    {
        return Saml2Tenants::where('is_default', '=', 1)->first();
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return tenant()->notifications_config['to'];
    }


    /**
     *  @return Attribute
     */
    public function hasReputationFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['reputation']['enabled'] ?? false,
        );
    }

    /**
     * Get homepage content
     *  @return Attribute
     */
    public function getHomepageContent(): Attribute
    {
        $currentLocale = app()->getLocale();

        switch ($currentLocale) {
            case 'pt-PT':
                $heading = 'Traduzimos Sustentabilidade em Negócio';
                $content = '<p>Somos humanos, todos nós.</p><p>É quase absurdo afirmar tal facto.</p><p>Nós, às vezes, esquecemos isso?</p><p>Estamos a criar a melhor versão do nosso mundo?</p><p>Estamos a lutar pela versão mais correta da economia?</p><p class="text-esg28">Na C-MORE abraçamos as perguntas. Nós não assumimos.</p><p class="text-esg28">Estamos lado a lado com cada um de vós.</p>';
                break;
            case 'pt-BR':
                $heading = 'Traduzimos Sustentabilidade em Negócio';
                $content = '<p>Todos nós somos humanos.</p><p>É quase absurdo afirmar tal fato.</p><p>Nós, às vezes, esquecemos isso?</p><p>Estamos criando a melhor versão do nosso mundo?</p><p>Estamos nos esforçando pela versão mais precisa da economia?</p><p class="text-esg28">Na C-MORE abraçamos perguntas. Nós não as assumimos.</p>';
                break;
            case 'es':
                $heading = 'Traducimos la Sostenibilidad en Negocio';
                $content = '<p>Somos humanos, todos nosotros.</p><p>Es casi absurdo afirmar tal hecho.</p><p>¿Lo olvidamos a veces?</p><p>¿Estamos creando la mejor versión de nuestro mundo?</p><p>¿Nos esforzamos por la versión correcta de la economía?</p><p class="text-esg28">En C-MORE aceptamos las preguntas. No lo asumimos.</p>';
                break;
            case 'fr':
                $heading = 'Nous traduisons la durabilité en Affaire';
                $content = '<p>Nous sommes humains, tous autant que nous sommes.</p><p>Il est presque absurde d’affirmer un tel fait.</p><p>Nous l’oublions parfois.</p><p>Créons-nous la meilleure version de notre monde?</p><p>Cherchons-nous à obtenir la bonne version de l’économie?</p><p class="text-esg28">Chez C-MORE, nous répondons aux questions. Nous ne supposons pas.</p>';
                break;
            default:
                $heading = 'We translate Sustainability into Business';
                $content = '<p>We are humans, all of us.</p><p>It is almost absurd stating such a fact.</p><p>Do we, sometimes, forget that?</p><p>Are we creating the best version of our world?</p><p>Are we striving for the most accurate version of the economy?</p><p class="text-esg28">At C-MORE we embrace questions. We do not assume.</p>';
                break;
        }

        $title = $this['features']['homepage']['title'][$currentLocale] ?? __('OUR MANIFESTO');
        $heading = $this['features']['homepage']['heading'][$currentLocale] ?? $heading;
        $content = $this['features']['homepage']['content'][$currentLocale] ?? $content;

        if ($this['manifesto'] ?? false) {
            $title = $this['section_title'] ?? $title;
            $heading = $this['main_title'] ?? $heading;
            $content = $this['description'] ?? $content;
        }

        return Attribute::make(
            get: fn ($value, $attributes) => [
                'title' => $title,
                'heading' => $heading,
                'content' => $content,
            ],
        );
    }

    /**
     * Check if two factor authentication is enabled
     * @return Attribute
     */
    public function has2faEnabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['authentication']['2fa']['enabled'] ?? false,
        );
    }

    /**
     * Check if sharing is enabled
     * @return Attribute
     */
    public function hasSharingEnabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['sharing']['enabled'] ?? false,
        );
    }

    /**
     * Get default 2fa methods
     * @return Attribute
     */
    public function default2faMethods(): Attribute
    {
        return Attribute::make(
            get: fn () =>  ['application', 'backupcodes', 'email', 'sms'],
        );
    }

    /**
     * Get enabled 2fa methods
     * @return Attribute
     */
    public function enabled2faMethods(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['authentication']['2fa']['methods'] ?? $this->default2faMethods,
        );
    }

    /**
     * Check if code for two authentication is generate by application
     * @return Attribute
     */
    public function has2faApplicationEnabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => in_array('application', $this->enabled2faMethods),
        );
    }

    /**
     * Check if two factor authentication using recovery codes is enabled
     * @return Attribute
     */
    public function has2faBackupsCodeEnabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => in_array('backupcodes', $this->enabled2faMethods),
        );
    }

    /**
     * Check if code for two authentication is send to email
     *  @return Attribute
     */
    public function has2faEmailEnabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => in_array('email', $this->enabled2faMethods),
        );
    }

    /**
     * Check if code for two authentication is send via sms
     * @return Attribute
     */
    public function has2faSMSEnabled(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => in_array('sms', $this->enabled2faMethods),
        );
    }

    /**
     * Check if has tags feature
     * @return Attribute
     */
    public function hasTagsFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['tags']['enabled'] ?? false,
        );
    }

    /**
     * Check if the tenant can create api tokens
     * @return Attribute
     */
    public function hasApiTokenFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['api-token']['enabled'] ?? false,
        );
    }

    /**
     * Check if wallet is enabled
     * @return Attribute
     */
    public function hasCatalogFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['catalog']['enabled'] ?? false,
        );
    }

    /**
     * Check if wallet is enabled
     * @return Attribute
     */
    public function hasWalletFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['wallet']['enabled'] ?? false,
        );
    }

    /**
     * Check if wallet credit is enabled
     * @return Attribute
     */
    public function hasWalletCreditFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['wallet']['credit']['enabled'] ?? false,
        );
    }

    /**
     * Get wallet credit limit
     * @return Attribute
     */
    public function walletLimitCredit(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['wallet']['credit']['limit'] ?? 0,
        );
    }

    /**
     * Check if the tenant has insufficient funds
     * @return Attribute
     */
    public function hasInsufficientFunds(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->has_wallet_feature && $this->balanceFloat < $this->walletLimitCredit,
        );
    }


    /**
     * Determine if the tenant has the Gar/Btar feature
     * @return Attribute
     */
    public function hasGarBtarFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['garbtar']['enabled'] ?? false,
        );
    }


    /**
     * Determine if the tenant has the reporting feature
     * @return Attribute
     */
    public function hasReportingFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['reporting']['enabled'] ?? false,
        );
    }

    /**
     * Default questionnaire to be created for all companies
     * @return Attribute
     */
    public function hasCreatingfeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['companies']['creating'] ?? [],
        );
    }

    /**
     * Check if wallet is enabled
     * @return Attribute
     */
    public function isEsgEnvironment(): Attribute
    {
        return Attribute::make(
            get: function () {
                return config('app.instance') === 'esg-maturity'
                    ? true
                    : false;
            }
        );
    }

    /**
     * Check if wallet is enabled
     * @return Attribute
     */
    public function backofficeUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $domain = Domain::domainFromSubdomain($this->primaryDomain->domain);
                return '//' . $domain . '/bo';
            }
        );
    }

    /**
     * Retrieve company type » default
     * @return Attribute
     */
    public function termsConditionsUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->features['terms_conditions'] ?? null;
            }
        );
    }

    /**
     * Retrieve company type » default
     * @return Attribute
     */
    public function getTermsConditionsUrl($lang = "en")
    {
        $url = isset($this->termsConditionsUrl[$lang])
            ? centralPrivateAssetDownload($this->termsConditionsUrl[$lang])
            : url('storage/documentation/terms-and-conditions_' . $lang . '.pdf');

        return $url;
    }

    /**
     * Retrieve company type » default
     * @return Attribute
     */
    public function privacyPolicyUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->features['privacy_policy'] ?? null;
            }
        );
    }

    /**
     * Retrieve company type » default
     * @return Attribute
     */
    public function getPrivacyPolicyUrl($lang = "en")
    {
        $url = isset($this->privacyPolicyUrl[$lang])
            ? centralPrivateAssetDownload($this->privacyPolicyUrl[$lang])
            : url('storage/documentation/privacy-policy_' . $lang . '.pdf');

        return $url;
    }

    /**
     * Retrieve company type » default
     * @return Attribute
     */
    public function tenantPublicCentralStorage(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return "tenant{$this->id}";
            }
        );
    }

    /**
     * Check if wallet is enabled
     * @return Attribute
     */
    public function isUserRequiredToAcceptTermsConditions(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['users']['require_to_accept_terms_and_conditions'] ?? false,
        );
    }

    /**
     * Determine if the tenant has the reporting feature
     * @return Attribute
     */
    public function hasReportingPeriodFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this['features']['reporting_periods']['self_managed'] ?? false,
        );
    }
}
