<?php

namespace App\Events;

use Illuminate\Auth\Events\Registered as EventsRegistered;

class Registered extends EventsRegistered
{
    public ?array $extra;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct($user, $extra = null)
    {
        $this->user = $user;
        $this->extra = $extra;
    }
}
