<?php

namespace App\Models\Tenant;

use App\Models\Traits\QueryBuilderScopes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Attachment extends Model
{
    use HasFactory;
    use LogsActivity;
    use QueryBuilderScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['created_by_user_id', 'file', 'name', 'size'];

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public static function fileExists(string $name): bool
    {
        return self::where('name', $name)->count() ? true : false;
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
