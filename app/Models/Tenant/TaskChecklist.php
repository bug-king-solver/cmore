<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'completed_by_user_id',
        'name',
        'completed',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'date',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
