<?php

namespace App\Models\Tenant\Filters\GarBtarAssets;

use App\Models\Enums\Regulamentation\EntityTypeEnum;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use App\Models\Tenant\GarBtar\BankAssets;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class NaceFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'data->' . BankAssets::NACE_CODE;

    public function title(): string
    {
        return __('Nace Activity Code');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        $listOptions = BusinessSector::query()
            ->where('business_sector_type_id', '=', 3)
            ->orderBy('id')
            ->pluck('name')
            ->toArray();
        $options = [];
        foreach ($listOptions as $option) {
            $options[$option] = explode(' - ', $option)[0];
        }
        return $options;
    }
}
