<?php

namespace App\Models\Traits;

use App\Models\Tenant\InternalTag;
use App\Models\Tenant\InternalTaggable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasInternalTags
{
    /**
     * List of tags assigned to the model
     */
    public function internalTags(): MorphToMany
    {
        return $this->morphToMany(InternalTag::class, 'taggable', 'internal_taggables', 'taggable_id')
            ->withTimestamps();
    }

    /**
     * Set the tags assigned to the model
     */
    public function assignInternalTags(array $tags): void
    {
        $this->internalTags()->sync($tags);
    }
}
