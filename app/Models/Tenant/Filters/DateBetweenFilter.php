<?php

namespace App\Models\Tenant\Filters;

use Lacodix\LaravelModelFilter\Enums\FilterMode;
use Lacodix\LaravelModelFilter\Filters\DateFilter as Date;

class DateBetweenFilter extends Date
{
    public FilterMode $mode = FilterMode::BETWEEN;

    protected string $field = 'created_at';

    public function title(): string
    {
        return __('Created between');
    }

    public function component(): string
    {
        return 'date-between';
    }
}
