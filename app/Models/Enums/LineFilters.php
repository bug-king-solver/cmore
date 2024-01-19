<?php

namespace App\Models\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self global()
 * @method static self business_sector()
 */
class LineFilters extends Enum
{
    protected static function values(): array
    {
        return [
            'global' => 'global',
            'business_sector' => 'business_sector'
        ];
    }

    protected static function labels(): array
    {
        return [
            'global' => __('Global'),
            'business_sector' => __('Business Sector')
        ];
    }
}