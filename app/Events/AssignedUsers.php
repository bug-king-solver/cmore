<?php

namespace App\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class AssignedUsers
{
    use SerializesModels;

    public $model;

    public $newlyAssignedUsers;

    /**
     * The user who made the change.
     */
    public Authenticatable $assigner;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model, $recentlyAssignedUsers, $assigner)
    {
        $this->model = $model;
        $this->newlyAssignedUsers = $recentlyAssignedUsers;
        $this->assigner = $assigner;
    }
}
