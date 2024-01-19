<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Groups;
use App\Models\Tenant\Target;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Groupable extends Model
{
    use HasFactory;

    protected $fillable = [
        'groups_id',
        'groupable_id',
        'groupable_type',
    ];

    public function groupable(): MorphTo
    {
        return $this->morphTo('groupable', 'groupable_type', 'groupable_id', 'id');
    }

    public function groupableParentGroup()
    {
        return $this->belongsTo(Groups::class, 'groups_id');
    }
}
