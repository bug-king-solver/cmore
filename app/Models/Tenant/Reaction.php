<?php

namespace App\Models\Tenant;

use Spatie\Comments\Models\Reaction as Model;

/**
 * @property int $id
 * @property string $reaction
 * @property int $comment_id
 * @property int $commentator_id
 * @property int $commentator_type
 */
class Reaction extends Model
{
    //boot creating

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $allowedReactions = config("comments.allowed_reactions") ?? [];

            if (!in_array($model->reaction, $allowedReactions)) {
                return false;
            }
        });
    }
}
