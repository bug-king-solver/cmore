<?php

namespace App\Models\Tenant\Concerns;

use App\Models\Tenant\Attachable;
use App\Models\Tenant\Concerns\Interfaces\CanAttach;
use App\Models\Tenant\Media;
use App\Models\Traits\QueryBuilderScopes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasAttachments
{
    use QueryBuilderScopes;

    public function attachments(Authenticatable $user = null): MorphToMany
    {
        $attached = $this->morphToMany(Media::class, 'attachable')
            ->withPivot('attachmentator_type', 'attachmentator_id')
            ->using(Attachable::class);
        if ($user && ! is_null($user)) {
            $attached->wherePivot('attachmentator_id', '=', auth()->user()->id);
        }
        $attached->withTimestamps();

        return $attached;
        //->onlyOwnData($user)
    }

    public function attach(int | array $attachmentIds, CanAttach $attachmentator = null)
    {
        $attachmentIds = is_array($attachmentIds) ? $attachmentIds : [$attachmentIds];

        $attachmentator ??= auth()->user();

        $attachments = $this->attachments($attachmentator);

        // Prevent duplicated data
        $toAttach = array_diff($attachmentIds, $attachments->pluck('media_id')->toArray());

        $attachments->attach(
            $toAttach,
            [
                'attachmentator_id' => $attachmentator?->id ?? null,
                'attachmentator_type' => $attachmentator?->getMorphClass() ?? null,
            ]
        );
    }

    public function createAndAttach(
        string $filepath,
        string $name,
        int $size,
        CanAttach $attachmentator = null
    ): Media {
        $attachmentator ??= auth()->user();

        $attachment = Attachment::create([
            'created_by_user_id' => $attachmentator ? $attachmentator->id : null,
            'file' => $filepath,
            'name' => $name,
            'size' => $size,
        ]);

        $this->attach($attachment->id, $attachmentator);

        return $attachment;
    }

    public function detach(int $id): int
    {
        return $this->attachments()->detach($id);
    }
}
