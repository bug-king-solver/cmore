<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Filters\InternalTagsColor;
use App\Models\Tenant\Filters\InternalTagsStatus;
use App\Models\Tenant\Questionnaire;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;

class InternalTag extends Model
{
    use HasFactory;
    use SoftDeletes;
    use IsSearchable;
    use HasFilters;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * @var array|string[]
     */
    protected $filters = [
        InternalTagsColor::class,
        InternalTagsStatus::class,
    ];

    /**
     * @var array|string[]
     */
    protected $searchable = [
        'name', 'slug',
    ];

    /**
     * @return void
     */
    public static function boot(): void
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
     * @return mixed
     */
    public static function list(): mixed
    {
        return self::orderBy('name');
    }

    /**
     * @return mixed
     */
    public function assigner(): mixed
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function questionnaires(): mixed
    {
        return $this->morphedByMany(Questionnaire::class, 'taggable');
    }

    /**
     * @return mixed
     */
    public function companies(): mixed
    {
        return $this->morphedByMany(Company::class, 'taggable');
    }

    /**
     * @return mixed
     */
    public function indicators(): mixed
    {
        return $this->morphedByMany(Indicator::class, 'taggable');
    }

    /**
     * @return mixed
     */
    public function tasks(): mixed
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    /**
     * @return mixed
     */
    public function questions(): mixed
    {
        return $this->morphedByMany(Question::class, 'taggable', 'internal_taggables');
    }
}
