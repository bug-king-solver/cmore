<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum ProductType: string
{
    use EnumToArray;

    case MATURITY = 'maturity';
    case PRO = 'pro';

    public function label(): string
    {
        return match ($this) {
            ProductType::MATURITY => __('Maturity'),
            ProductType::PRO => __('PRO')
        };
    }
}
