<?php

namespace App\Models\Tenant\Filters\GarBtarAssets;

use App\Models\Enums\Regulamentation\EntityTypeEnum;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\GarBtar\BankAssets;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class EntityTypeFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'data->' . BankAssets::ENTITY_TYPE;

    public function title(): string
    {
        return __('Entity Type');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return array_flip(EntityTypeEnum::toArray());
    }
}
