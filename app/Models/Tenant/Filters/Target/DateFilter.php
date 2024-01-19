<?php

namespace App\Models\Tenant\Filters\Target;

use Lacodix\LaravelModelFilter\Enums\FilterMode;
use Lacodix\LaravelModelFilter\Filters\DateFilter as Date;

class DateFilter extends Date
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'due_date';

    public function title(): string
    {
        return __('Due Date');
    }

    public function component(): string
    {
        return 'date';
    }
}
