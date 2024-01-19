<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Filters\vendor\FilterRelationTrait;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\Tag;

class TagsFilter extends SelectInFilter
{
    use FilterRelationTrait;

    protected string $field = 'tag_id';

    protected string $relation = 'tags';

    public function title(): string
    {
        return __('Tags');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return Tag::query()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
