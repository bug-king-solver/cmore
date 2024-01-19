<?php

namespace App\Models\Tenant;

use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    public function user()
    {
        return User::find($this->custom_properties['created_by']);
    }

    public function attachables()
    {
        return $this->hasMany(Attachable::class);
    }

    /**
     * Get all of the answers that are assigned this attachment.
     */
    public function answers()
    {
        return $this->morphedByMany(Answer::class, 'attachable');
    }

    public function attachmentator()
    {
        return $this->morphTo();
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list()
    {
        return self::OnlyOwnData()
            ->orderBy('name');
    }

    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'userables');
    }
}
