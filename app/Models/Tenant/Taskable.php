<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Taskable extends Model
{
    /**
     * Polimorphic relationship.
     */
    public function taskables()
    {
        return $this->morphTo();
    }
}
