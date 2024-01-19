<?php

namespace App\Models\Traits;

use App\Models\Tenant\Task;
use App\Models\Tenant\Taskable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTasks
{
    /**
     * Polimorphic relationship.
     */
    public function tasks()
    {
        return $this->morphToMany(Task::class, 'taskables');
    }

    /**
     * Polimorphic relationship.
     */
    public function taskables(): MorphMany
    {
        return $this->morphMany(Taskable::class, 'taskable', 'id');
    }
}
