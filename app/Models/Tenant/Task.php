<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Filters\TasksDueDate;
use App\Models\Tenant\Filters\TasksStatus;
use App\Models\Tenant\Filters\TasksWeight;
use App\Models\Traits\HasInternalTags;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Target;
use App\Models\Tenant\Company;
use App\Models\Tenant\TaskChecklist;
use App\Models\Tenant\Taskable;
use App\Models\Traits\HasTags;
use App\Models\Traits\HasUsers;
use App\Models\Traits\QueryBuilderScopes;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Comments\Models\Concerns\HasComments;
use App\Models\Traits\Filters\IsSortable;

class Task extends Model
{
    use HasFactory;
    use HasComments;
    use HasTags;
    use HasInternalTags;
    use HasUsers;
    use QueryBuilderScopes;
    use LogsActivity;
    use SoftDeletes;
    use IsSearchable;
    use HasFilters;
    use IsSortable;

    protected $fillable = [
        'created_by_user_id',
        'name',
        'description',
        'due_date',
        'weight',
        'alert_on_complete',
        'completed',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed' => 'boolean',
    ];

    protected array $filters = [
        TasksDueDate::class,
        TasksWeight::class,
        TasksStatus::class,
    ];

    protected array $searchable = [
        'name', 'description',
    ];

    protected array $sortable = [
        'id' => 'Id',
        'name' => 'Name',
        'weight' => 'Weight',
        'due_date' => 'Due Date',
        'created_at' => 'Created at'
    ];

    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->parseDispatchesEvents();
    }

    // public function dueDate(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $value,
    //     );
    // }

    /*
    * This string will be used in notifications on what a new comment
    * was made.
    */
    public function commentableName(): string
    {
        return $this->name;
    }

    /*
    * This URL will be used in notifications to let the user know
    * where the comment itself can be read.
    */
    public function commentUrl(): string
    {
        return route('users.tasks.show', ['task' => $this->id]);
    }

    /**
     * Get the options for logging changes to the model.
     */
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
        return self::OnlyOwnData($user);
    }

    /**
     * Get the list of the taskables where type is Target
     */
    public static function listTargets($user = null)
    {
        return self::OnlyOwnData($user)->whereHas('targets');
    }

    /**
     * Task polimorphic relationship
     */
    public function taskables(): HasOne
    {
        return $this->hasOne(Taskable::class)->with('taskables');
    }

    /**
     * Polimorphic relationship with Questionnaire
     */
    public function questionnaires()
    {
        return $this->morphedByMany(Questionnaire::class, 'taskables');
    }

    /**
     * Polimorphic relationship with Target
     */
    public function targets()
    {
        return $this->morphedByMany(Target::class, 'taskables');
    }

    /**
     * Polimorphic relationship with Company
     */
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'taskables');
    }

    /**
     * Has many relationship with TaskChecklist
     */
    public function checklist(): HasMany
    {
        return $this->hasMany(TaskChecklist::class);
    }

    /**
     * Belongs to relationship with User
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    /**
     * Check if the user is the owner of the task
     */
    public function isOwner($user)
    {
        return $this->created_by_user_id == $user->id;
    }

    /**
     * Create the assigned user message
     */
    public function assignedUserMessage($assigner)
    {
        $params = [
            'task' => $this->id,
        ];

        // URL for the specific target
        $actionUrl = tenant()->route('tenant.users.tasks.show', $params);

        return [
            'userName' => $assigner->name,
            'task' => $this->name,
            'company' => '', // TODO::$this->company->name,
            'message' => __(':userName assigned you the task ":task" for ":company".'),
            'action' => $actionUrl,
        ];
    }

    /**
     * Create the assigned user message
     */
    public function updatedUserMessage()
    {
        $params = [
            'task' => $this->id,
        ];

        // URL for the specific target
        $actionUrl = route('tenant.users.tasks.show', $params);

        return [
            'task' => $this->title,
            'company' => '', // TODO::$this->company->name,
            'message' => __('The task ":task" for ":company" has been updated.'),
            'action' => $actionUrl,
        ];
    }
}
