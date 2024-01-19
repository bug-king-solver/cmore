<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\InternalTag;
use App\Models\Tenant\Filters\vendor\SelectInFilter;

class InternalTagsColor extends SelectInFilter
{
    /**
     * @var string
     */
    protected string $field = 'color';

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Color');
    }

    /**
     * @return string
     */
    public function component(): string
    {
        return 'select-color';
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return InternalTag::list()->withTrashed()->pluck('color', 'color')->toArray();
    }
}
