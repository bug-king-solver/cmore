<?php

namespace App\Models\Tenant\Api;

use App\Events\Api\ApiTokenGeneratedEvent;
use App\Models\Enums\SanctumAbilities;
use App\Models\Traits\QueryBuilderScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class ApiTokens extends Model
{
    use QueryBuilderScopes;
    use Notifiable;

    protected $table = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'tokenable_type',
        'tokenable_id',
        'token',
        'abilities',
        'last_used_at',
        'created_at',
        'updated_at',
    ];

    protected $cast = [
        'name' => 'string',
        'abilities' => 'array',
        'last_used_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        /**
         * Generate a random token for the model and store it in the randomToken
         */
        $randomToken = Str::random(40);

        static::creating(function ($model) use ($randomToken) {
            /**
             * Hash the random token and store it in the token column
             */
            $model->token = hash('sha256', $randomToken);
        });

        static::created(function ($model) use ($randomToken) {
            /**
             * Fire the ApiTokenGeneratedEvent
             */
            event(new ApiTokenGeneratedEvent($model, $randomToken));
        });
    }

    public function tokenable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'tokenable_type', 'tokenable_id', 'id');
    }

    public function routeNotificationForMail($notification): array
    {
        return [
            $this->tokenable->email => $this->tokenable->name,
        ];
    }

    public function getAbilities(): array
    {
        return SanctumAbilities::casesArray();
    }
}
