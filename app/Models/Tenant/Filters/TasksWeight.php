<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Task;
use App\Models\Tenant\Filters\vendor\SelectInFilter;

class TasksWeight extends SelectInFilter
{
    protected string $field = 'weight';

    public function title(): string
    {
        return __('Weight');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Task::list()->pluck('weight', 'weight')->toArray();
    }
}
