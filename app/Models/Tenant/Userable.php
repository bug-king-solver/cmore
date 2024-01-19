<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Userable extends Model
{
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assigner(): BelongsTo
    {
        return $this->morphTo();
    }
}
