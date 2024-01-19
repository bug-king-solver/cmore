<?php

namespace App\Models\Tenant\Filters;

use App\Models\Tenant\Filters\vendor\FilterRelationTrait;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\InternalTag;

class InternalTagsFilter extends SelectInFilter
{
    /**
     *
     */
    use FilterRelationTrait;

    /**
     * @var string
     */
    protected string $field = 'internal_tag_id';

    /**
     * @var string
     */
    protected string $relation = 'internalTags';

    /**
     * @return string
     */
    public function title(): string
    {
        return __('InternalTags');
    }

    /**
     * @return string
     */
    public function component(): string
    {
        return 'select';
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return InternalTag::query()
            ->orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
