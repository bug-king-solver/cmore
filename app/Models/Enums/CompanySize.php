<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum CompanySize: int
{
    use EnumToArray;

    case MICRO = 1;
    case SMALL = 2;
    case MEDIUM = 3;
    case LARGE = 4;

    /**
     * Get the label of the enum
     */
    public function label(): string
    {
        return match ($this) {
            CompanySize::MICRO => __('Micro'),
            CompanySize::SMALL => __('Small'),
            CompanySize::MEDIUM => __('Medium'),
            CompanySize::LARGE => __('Large'),
        };
    }

    /**
     * Get the group label of the enum
     */
    public function groupLabel(): string
    {
        return match ($this) {
            CompanySize::MICRO, CompanySize::SMALL, CompanySize::MEDIUM => __('SME'),
            CompanySize::LARGE => __('Large'),
        };
    }
}
