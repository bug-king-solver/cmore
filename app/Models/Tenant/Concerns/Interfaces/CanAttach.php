<?php

namespace App\Models\Tenant\Concerns\Interfaces;

use App\Support\AttachmentatorProperties;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CanAttach
{
    public function attachments(): MorphMany;

    public function getKey();

    public function getMorphClass();
}
