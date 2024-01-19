<?php

namespace App\Models\Enums\Regulamentation;

use App\Models\Traits\EnumToArray;

enum EntityTypeEnum: string
{
    use EnumToArray;

    case CREDIT_INSTITUTIONS = '1';
    case INVESTMENT_COMPANIES = '2';
    case MANAGEMENT_COMPANIES = '3';
    case INSURANCE_COMPANIES = '4';
    case NONFINANCIAL_COMPANIES = '5';

    public function label(): string
    {
        return match ($this) {
            self::CREDIT_INSTITUTIONS => __('Credit institutions'),
            self::INVESTMENT_COMPANIES => __('Investment companies'),
            self::MANAGEMENT_COMPANIES => __('Management Companies'),
            self::INSURANCE_COMPANIES => __('Insurance companies'),
            self::NONFINANCIAL_COMPANIES => __('Non-financial companies'),
        };
    }

    /**
     * Return an array of the enum values.
     */
    public static function toArray(): array
    {
        return self::casesArray();
    }

    /**
     * Mount an array for use as select in forms
     */
    public static function formList()
    {
        return [
            [
                'id' => self::CREDIT_INSTITUTIONS,
                'title' => self::CREDIT_INSTITUTIONS->label()
            ],
            [
                'id' => self::INVESTMENT_COMPANIES,
                'title' => self::INVESTMENT_COMPANIES->label()
            ],
            [
                'id' => self::MANAGEMENT_COMPANIES,
                'title' => self::MANAGEMENT_COMPANIES->label()
            ],
            [
                'id' => self::INSURANCE_COMPANIES,
                'title' => self::INSURANCE_COMPANIES->label()
            ],
            [
                'id' => self::NONFINANCIAL_COMPANIES,
                'title' => self::NONFINANCIAL_COMPANIES->label()
            ],
        ];
    }

}
