<?php

namespace App\Events\Tasks;

use App\Models\Tenant\Task;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class UpdatedTaskEvent
{
    use SerializesModels;

    /**
     * The target after update.
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
    public function __construct(Task $task, array $changes, Authenticatable $userFiredEvent)
    {
        $this->task = $task;
        $this->changes = $changes;
        $this->userFiredEvent = $userFiredEvent;
    }
}
