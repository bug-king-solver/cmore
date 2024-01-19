<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Company;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class CompanyFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'company_id';

    public function title(): string
    {
        return __('Company');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Company::list()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
