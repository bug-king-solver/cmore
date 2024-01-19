<?php

namespace App\Models\Tenant\Filters\Company;

use App\Models\Tenant\Company;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class ParentCompanyFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'parent_id';

    public function title(): string
    {
        return __('Parent Company');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Company::query()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
