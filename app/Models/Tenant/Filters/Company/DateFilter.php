<?php

namespace App\Models\Tenant\Filters\Company;

use Lacodix\LaravelModelFilter\Enums\FilterMode;
use Lacodix\LaravelModelFilter\Filters\DateFilter as Date;

class DateFilter extends Date
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'created_at';

    public function title(): string
    {
        return __('Created at');
    }

    public function component(): string
    {
        return 'date';
    }
}
