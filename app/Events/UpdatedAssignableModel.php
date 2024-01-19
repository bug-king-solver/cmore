<?php

namespace App\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class UpdatedAssignableModel
{
    use SerializesModels;

    /**
     * The target after update.
     */
    public Model $model;

    /**
     * The user who made the change.
     */
    public Authenticatable $userFiredEvent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
