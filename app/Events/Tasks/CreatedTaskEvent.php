<?php

namespace App\Events\Tasks;

use App\Models\Tenant\Task;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class CreatedTaskEvent
{
    use SerializesModels;

    /**
     * The target
     */
    public Task $task;

    public array $changes;

    /**
     * The user who made the change.
     */
    public Authenticatable $userFiredEvent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task, Authenticatable $userFiredEvent)
    {
        $this->task = $task;
        $this->changes = [];
        $this->userFiredEvent = $userFiredEvent;
    }
}
