<?php

namespace App\Events;

use App\Models\Tenant;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class DiscoverMessage
{
    use SerializesModels;

    public $model;

    /**
     * The user who made the change.
     */
    public Authenticatable $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tenant $tenant, Authenticatable $user)
    {
        $this->tenant = $tenant;
        $this->user = $user;
    }
}
