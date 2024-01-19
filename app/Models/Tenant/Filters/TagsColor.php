<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Tag;
use App\Models\Tenant\Filters\vendor\SelectInFilter;

class TagsColor extends SelectInFilter
{
    protected string $field = 'color';

    public function title(): string
    {
        return __('Color');
    }

    public function component(): string
    {
        return 'select-color';
    }

    public function options(): array
    {
        return Tag::list()->withTrashed()->pluck('color', 'color')->toArray();
    }
}
