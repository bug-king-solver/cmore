<?php

namespace App\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class CreatedData
{
    use SerializesModels;

    public $data;

    public Authenticatable $user;

    public $senderuser;
    /**
     * The user who made the change.
     */

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $data, $senderuser)
    {
        $this->data = $data;
        $this->user = $user;
        $this->senderuser = $senderuser;
    }
}
