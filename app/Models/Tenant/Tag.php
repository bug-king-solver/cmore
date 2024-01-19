<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Filters\TagsColor;
use App\Models\Tenant\Filters\TagsStatus;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Target;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use App\Models\Traits\Filters\IsSortable;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;
    use IsSearchable;
    use HasFilters;
    use IsSortable;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    protected array $filters = [
        TagsColor::class,
        TagsStatus::class,
    ];

    protected array $searchable = [
        'name', 'slug',
    ];

    protected array $sortable = [
        'id' => 'Id',
        'name' => 'Name',
        'created_at' => 'Created at'
    ];

    /**
     * Scope a query to only include active tags.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function list()
    {
        return new Tag();
    }

    /**
     * Boot the model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = slugable($model->name);
        });

        static::updating(function ($model) {
            $model->slug = slugable($model->name);
        });
    }

    /**
     * Get the assigner that owns the tag.
     */
    public function assigner()
    {
        return $this->morphTo();
    }

    /**
     * Get all of the questionnaires that are assigned this tag.
     */
    public function questionnaires()
    {
        return $this->morphedByMany(Questionnaire::class, 'taggable');
    }

    public function targets()
    {
        return $this->morphedByMany(Target::class, 'taggable');
    }

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'taggable');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'taggable');
    }
}
