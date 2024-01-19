<?php

namespace App\Models;

use App\Models\Enums\Users\System;
use App\Models\Enums\Users\Type;
use App\Models\Tenant\Api\ApiTokens;
use App\Models\Tenant\Company;
use App\Models\Tenant\Concerns\InteractsWithAttachments;
use App\Models\Tenant\Concerns\Interfaces\CanAttach;
use App\Models\Tenant\Filters\DateBetweenFilter;
use App\Models\Tenant\Filters\TagsFilter;
use App\Models\Tenant\Filters\User\IsEnabledFilter;
use App\Models\Tenant\Filters\User\RolesFilter;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Target;
use App\Models\Tenant\Task;
use App\Models\Tenant\Userable;
use App\Models\Traits\Catalog\ProductItem;
use App\Models\Traits\Filters\IsSortable;
use App\Models\Traits\HasCustomColumns;
use App\Models\Traits\HasTags;
use App\Models\Traits\QueryBuilderScopes;
use App\Notifications\TwoFANotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Sanctum\HasApiTokens;
use PragmaRX\Recovery\Recovery;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Comments\Models\Concerns\InteractsWithComments;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class User extends Authenticatable implements MustVerifyEmail, CanComment, CanAttach, HasMedia
{
    use InteractsWithComments;
    use InteractsWithAttachments;
    use InteractsWithMedia;
    use HasFactory;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use QueryBuilderScopes;
    use HasDataColumn;
    use HasCustomColumns;
    use HasApiTokens;
    use HasTags;
    use HasFilters;
    use IsSearchable;
    use ProductItem;
    use IsSortable;

    protected $feature = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'system',
        'enabled',
        'type',
        'name',
        'username',
        'email',
        'password',
        'password_force_change',
        'password_expires_at',
        'locale',
        'email_verified_at',
        'photo',
        'created_by_user_id',
        'two_factor_code',
        'two_factor_expires_at',
        'phone',
        'google2fa_secret',
        'use_2fa',
        'send_2fa_to_email',
        'send_2fa_to_phone',
        'recovery_codes',
        'data',
    ];

    protected array $filters = [
        RolesFilter::class,
        IsEnabledFilter::class,
        DateBetweenFilter::class,
        TagsFilter::class,
    ];

    protected array $searchable = [
        'name', 'email',
    ];

    protected array $sortable = [
        'id' => 'Id',
        'name' => 'Name',
        'enabled' => 'Enabled',
        'created_at' => 'Created at'
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'system',
            'enabled',
            'type',
            'username',
            'name',
            'email',
            'password',
            'password_force_change',
            'password_expires_at',
            'locale',
            'email_verified_at',
            'photo',
            'created_by_user_id',
            'created_at',
            'updated_at',
            'two_factor_code',
            'two_factor_expires_at',
            'phone',
            'google2fa_secret',
            'use_2fa',
            'send_2fa_to_email',
            'send_2fa_to_phone',
            'recovery_codes',
            'data'
        ];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'email_verified_at' => 'datetime',
        'force_change_password' => 'boolean',
        'last_login' => 'datetime',
        'created_by_user_id' => 'integer',
        'use_2fa' => 'boolean',
        'send_2fa_to_email' => 'boolean',
        'send_2fa_to_phone' => 'boolean',
        'recovery_codes' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->parseCustomColumns();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'created_at' => $this->created_at,
        ];
    }

    public static function booted()
    {
        // Set the username equal to the e-mail
        static::creating(function (self $user) {
            if (tenant() && tenant()->is_email_the_authentication_username) {
                $user->useEmailAsUsername();
            }
        });

        static::created(function ($model) {
            // Don't charge system users
            if ($model->is_not_system_user && ($model->getProduct()->is_payable ?? false)) {
                tenant()->forceWithdrawFloat($model->getPriceProduct(), $model->getMetaProduct());
            }
        });

        static::updating(function (self $user) {
            if ($user->isOwner()) {
                // We update the tenant's email when the admin user's email is updated
                // so that the tenant can find his account even after email change.
                Tenant::where('email', $user->getOriginal('email'))
                    ->update($user->only(['email']));
            }

            if (tenant() && tenant()->is_email_the_authentication_username) {
                $user->useEmailAsUsername();
            }
        });
    }

    /**
     * Determine if the tenant has the monitoring feature
     */
    public function hasExpiredPassword(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['password_expires_at'] && $attributes['password_expiration'] < now(),
        );
    }

    public function useEmailAsUsername()
    {
        $this->username = $this->email;
    }

    /**
     * Determine if is an internal user
     */
    public function isInternalUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->type ? Type::from($this->type)->isInternal() : null,
        );
    }

    /**
     * Determine if is not an internal user
     */
    public function isNotInternalUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->type ? Type::from($this->type)->isNotInternal() : null,
        );
    }

    /**
     * Determine if is an external user
     */
    public function isExternalUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->type ? Type::from($this->type)->isExternal() : null,
        );
    }

    /**
     * Determine if is not an external user
     */
    public function isNotExternalUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Type::from($this->type)->isNotExternal(),
        );
    }

    /**
     * Determine if the user is a app user (is not a system user)
     */
    public function isAppUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => System::from($this->system)->isAppUser(),
        );
    }

    /**
     * Determine if the user is a app user (is system user)
     */
    public function isNotAppUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => System::from($this->system)->isNotAppUser(),
        );
    }

    /**
     * Determine if the user is a system user
     */
    public function isSystemUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => System::from($this->system)->isSystemUser(),
        );
    }

    /**
     * Determine if the user is not a system user
     */
    public function isNotSystemUser(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => System::from($this->system)->isNotSystemUser(),
        );
    }

    /**
     * Get the user's last login.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function lastLogin(): Attribute
    {
        return Attribute::make(
            get: function () {
                $lastLogin = $this->activities()
                    ->where('event', 'authentication')
                    ->where('description', 'login')
                    ->orderBy('id', 'desc')
                    ->first();

                return $lastLogin
                    ? $lastLogin->created_at
                    : null;
            }
        );
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setPasswordExpiration()
    {
        $this->password_expires_at = tenant()->hasPasswordExpiration
            ? now()->addDays(tenant()->passwordExpirationDays)
            : null;

        return $this->password_expires_at;
    }

    /**
     * Userables from questionnaire
     * @return MorphedByMany
     */
    public function questionnaires()
    {
        return $this->morphedByMany(Questionnaire::class, 'userable');
    }

    public function submittedQuestionnaires()
    {
        return $this->questionnaires()->whereNotNull('submitted_at');
    }

    public function readyQuestionnaires()
    {
        return $this->questionnaires()->where('is_ready', true);
    }

    /**
     * Userables from company
     * @return MorphedByMany
     */
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'userable');
    }

    public function users()
    {
        return $this->hasMany(self::class, 'created_by_user_id');
    }

    /**
     * Userables from target
     * @return MorphedByMany
     */
    public function targets()
    {
        return $this->morphedByMany(Target::class, 'userable');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logExcept(['password'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Determine if the tenant has the monitoring feature
     */
    public function hasMonitoringFeature(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['features']['monitoring']['enabled'] ?? false,
        );
    }

    /**
     * Is this user the "organization" owner.
     *
     * @return bool
     */
    public function isOwner()
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Is this user the "organization" owner.
     *
     * @return bool
     */
    public static function getOwners()
    {
        // We assume the superadmin is the first user in the DB.
        // Feel free to change this logic.
        return self::where('id', 1)->get('id');
    }

    public static function getByUsername(string $username)
    {
        return self::where('username', $username)->first();
    }

    public function getAvatarAttribute()
    {
        return $this->photo
            ? tenantPrivateAsset($this->photo, 'attachments')
            : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($this->name);
    }

    public function isEnabled()
    {
        return  $this->enabled;
    }

    /**
     * Get a list of the users available for the current user.
     * Hide the current user if he is not an admin.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function list($user = null)
    {
        return self::OnlyOwnData()
            ->availableUsers($user)
            ->with('roles');
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        if (tenant()->hasUserEmailVerificationEnabled()) {
            $this->notify(new VerifyEmail());
        }
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

    /**
     * Get all of the deployments for the project.
     */
    public function apitokens(): MorphMany
    {
        return $this->morphMany(ApiTokens::class, 'tokenable');
    }

    public function userables(): HasMany
    {
        return $this->hasMany(Userable::class);
    }

    public function assignerAble(): HasMany
    {
        return $this->hasMany(Userable::class, 'assigner_id', 'id');
    }

    /**
     * Function to alter assinger_id from userables table
     * @param $userableId
     */
    public function assignerAbleUpdate($user_id)
    {
        /** loop in userables and change assinger id to this user id  */
        foreach ($this->assignerAble as $userable) {
            $userable->assigner_id = $user_id;
            $userable->update();
        }
    }

    public function impersonateUser($userId): string
    {
        $user = self::find($userId);
        auth()->guard('web')->login($user);

        return route('tenant.home');
    }

    public function generateCode()
    {
        $code = rand(100000, 999999);

        $this->two_factor_code = $code;
        $this->two_factor_expires_at = now()->addMinutes(1);
        $this->save();
        $channels = [];
        if ($this->isToSend2FAEmail()) {
            $channels[] = TwoFANotification::MAIL;
        }
        if ($this->phone != null && $this->isToSend2FAPhone()) {
            $channels[] = TwoFANotification::SMS;
        }
        if (! empty($channels)) {
            $this->notify(new TwoFANotification($code, $channels));
        }
    }

    /**
     * Route notifications for the Vonage channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForVonage($notification)
    {
        return $this->phone;
    }

    public function is2FAEnabled()
    {
        return $this->use_2fa;
    }

    public function isToSend2FAEmail()
    {
        return $this->send_2fa_to_email;
    }

    public function isToSend2FAPhone()
    {
        return $this->send_2fa_to_phone;
    }

    public function is2FAApplicationEnabled()
    {
        return $this->google2fa_secret;
    }

    public function has2FAActive()
    {
        return $this->is2FAEnabled()
            && ($this->isToSend2FAEmail() || $this->isToSend2FAPhone() || $this->is2FAApplicationEnabled());
    }

    public function getRecoveryTokens()
    {
        return $this->recovery_codes;
    }

    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setFAApplicationSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getFAApplicationSecretAttribute($value)
    {
        if (isset($value)) {
            return decrypt($value);
        }
    }

    public function remove2FAApplication()
    {
        $this->google2fa_secret = null;
        $this->use_2fa = true;
        $this->save();
        $this->generateCode();
    }

    public function generateRecoveryCodes()
    {
        $recovery = new Recovery();

        return $recovery
            ->uppercase()
            ->setCount(10)
            ->setBlocks(1)
            ->setChars(25);
    }

    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'userable')
        ->withTimestamps();
    }
}
