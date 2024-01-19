<?php

namespace App\Models\Tenant\Filters\Target;

use Lacodix\LaravelModelFilter\Enums\FilterMode;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\Indicator;

class IndicatorFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'indicator_id';

    public function title(): string
    {
        return __('Indicators');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Indicator::query()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
