<?php

namespace App\Models\Traits;

use App\Models\Tenant\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    /**
     * List of tags assigned to the model
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /**
     * Set the tags assigned to the model
     */
    public function assignTags(array $tags, $assigner)
    {
        $this->tags()->syncWithPivotValues(
            $tags,
            [
                'assigner_id' => $assigner->getKey(),
                'assigner_type' => $assigner->getMorphClass(),
            ]
        );
    }
}
