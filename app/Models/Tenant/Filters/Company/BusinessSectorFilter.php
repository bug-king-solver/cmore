<?php

namespace App\Models\Tenant\Filters\Company;

use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class BusinessSectorFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'business_sector_id';

    public function title(): string
    {
        return __('Business Sector');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return BusinessSector::query()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
