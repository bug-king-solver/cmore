<?php

namespace App\Models\Tenant\Filters\GarBtarAssets;

use App\Models\Enums\Regulamentation\AssetTypeEnum;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\GarBtar\BankAssets;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class AssetTypeFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'data->' . BankAssets::TYPE;

    public function title(): string
    {
        return __('Asset Type');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return array_flip(AssetTypeEnum::toArray());
    }
}
