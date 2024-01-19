<?php

namespace App\Models\Tenant\Concerns;

use App\Models\Tenant\Attachment;
use App\Support\AttachmentatorProperties;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait InteractsWithAttachments
{
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentator');
    }
}
