<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Company;
use App\Models\Tenant\Concerns\HasAttachments;
use App\Models\Tenant\Concerns\Interfaces\Userable;
use App\Models\Tenant\Indicator;
use App\Models\Traits\HasGroups;
use App\Models\Traits\HasTags;
use App\Models\Traits\HasTasks;
use App\Models\Traits\HasUsers;
use App\Models\Traits\QueryBuilderScopes;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use App\Models\Tenant\Filters\Target\DateFilter;
use App\Models\Tenant\Filters\TagsFilter;
use App\Models\Tenant\Filters\Target\IndicatorFilter;
class Target extends Model implements HasMedia, Userable
{
    use HasFactory;
    use HasComments;
    use HasAttachments;
    use HasUsers;
    use HasTags;
    use HasTasks;
    use HasGroups;
    use LogsActivity;
    use QueryBuilderScopes;
    use InteractsWithMedia;
    use HasFilters;
    use IsSearchable;

    protected $casts = [
        'due_date' => 'date',
        'start_date' => 'date',
        'completed_at' => 'date',
        'started_at' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by_user_id',
        'company_id',
        'user_id',
        'indicator_id',
        'our_reference',
        'title',
        'description',
        'goal',
        'start_date',
        'due_date',
        'status',
        'completed_at',
        'started_at',
        'created_at',
        'updated_at',
    ];

    protected array $filters = [
        DateFilter::class,
        TagsFilter::class,
        IndicatorFilter::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->parseDispatchesEvents();
    }

    protected static function booted()
    {
        static::updated((function ($target) {
            if ($target->status == 'completed' && $target->completed_at == null) {
                $target->completed_at = now();
                if ($target->started_at == null) {
                    $target->started_at = now();
                }
                $target->save();
            } elseif ($target->status == 'ongoing' && $target->started_at == null) {
                $target->started_at = now();
                $target->save();
            }
        }));
    }

    /**
     * Get the company that owns the target.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user that owns the target.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the indicator that owns the target.
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'not-started':
                $label = 'Not started';
                break;
            case 'ongoing':
                $label = 'Ongoing';
                break;
            case 'completed':
                $label = 'Completed';
                break;
            default:
                $label = '';
                break;
        }

        return $label;
    }

    /*
    * This string will be used in notifications on what a new comment
    * was made.
    */
    public function commentableName(): string
    {
        return $this->title;
    }

    /*
    * This URL will be used in notifications to let the user know
    * where the comment itself can be read.
    */
    public function commentUrl(): string
    {
        return route('tenant.targets.show', ['target' => $this->id]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get a list of the companies to use with paginate
     *
     * @return Collection
     */
    public static function list($user = null)
    {
        return self::OnlyOwnData($user)
            ->orderBy('title')
            ->with('company', 'user');
    }

    /**
     * Create the assigned user message
     */
    public function assignedUserMessage($assigner)
    {
        $params = [
            'target' => $this->id,
        ];

        // URL for the specific target
        $actionUrl = tenant()->route('tenant.targets.show', $params);

        return [
            'userName' => $assigner->name,
            'target' => $this->title,
            'company' => $this->company->name,
            'message' => __(':userName assigned you the target ":target" for ":company".'),
            'action' => $actionUrl,
        ];
    }

    /**
     * Create the assigned user message
     */
    public function updatedUserMessage()
    {
        $params = [
            'target' => $this->id,
        ];

        // URL for the specific target
        $actionUrl = route('tenant.targets.show', $params);

        return [
            'target' => $this->title,
            'company' => $this->company->name,
            'message' => __('The target ":target" for ":company" has been updated.'),
            'action' => $actionUrl,
        ];
    }


    public function calcProgress(null|Collection $tasks): int
    {
        if ($tasks->count() == 0) {
            switch ($this->status) {
                case 'completed':
                    return 100;
                case 'ongoing':
                    return 50;
                default:
                    return 0;
            }
        }

        $total = $tasks->count();
        $completed = $tasks->filter(function ($task) {
            return $task->completed_at !== null;
        })->count();

        return $total > 0 ? round($completed / $total * 100) : 0;
    }

    public function scopeOngoing($query, $startDate)
    {
        $endDate = carbon()->now()->endOfDay();

        return $query->whereBetween('started_at', [
            $startDate->format('Y-m-d 00:00:00'),
            $endDate->format('Y-m-d 23:59:59'),
        ])->whereNull('completed_at');
    }

    /** not-started */
    public function scopeNotStarted($query)
    {
        return $query->where('status', 'not-started');
    }

    public function scopeCompleted($query, $startDate)
    {
        $endDate = carbon()->now()->endOfDay();

        return $query->whereBetween('completed_at', [
            $startDate->format('Y-m-d 00:00:00'),
            $endDate->format('Y-m-d 23:59:59'),
        ])->whereNotNull('completed_at')
            ->where('status', 'completed');
    }

    public function scopeOverdue($query, $startDate)
    {
        $endDate = carbon()->now()
            ->sub(1, 'day')
            ->endOfDay();

        return $query->whereBetween('due_date', [
            $startDate->format('Y-m-d 00:00:00'),
            $endDate->format('Y-m-d 23:59:59'),
        ])->whereNull('completed_at')
        ->where('status', '!=', 'not-started');
    }
}
