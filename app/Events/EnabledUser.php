<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class EnabledUser
{
    use SerializesModels;

    /**
     * The enabled user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
