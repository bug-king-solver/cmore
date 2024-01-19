<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Filters\vendor\ReferenceManyFilter;
use App\Models\Tenant\Source;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class ReferenceFilter extends ReferenceManyFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'sources.id';
    protected string $relation = 'indicator.sources';
    protected string $condition = 'reference';

    public function title(): string
    {
        return __('Framework');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Source::query()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
