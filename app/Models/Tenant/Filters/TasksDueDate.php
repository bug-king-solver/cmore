<?php

namespace App\Models\Tenant\Filters;

use Lacodix\LaravelModelFilter\Enums\FilterMode;
use Lacodix\LaravelModelFilter\Filters\DateFilter as Date;

class TasksDueDate extends Date
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'due_date';

    public function title(): string
    {
        return __('Due date');
    }

    public function component(): string
    {
        return 'date';
    }
}
