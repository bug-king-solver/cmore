<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum Risk: int
{
    use EnumToArray;

    case UNKNOWN = 0;
    case VERY_LOW = 1;
    case LOW = 2;
    case MEDIUM = 3;
    case HIGH = 4;
    case VERY_HIGH = 5;

    /**
     * Get the label of the enum
     */
    public function label(): string
    {
        return match ($this) {
            self::UNKNOWN => __('Unknown'),
            self::VERY_LOW => __('Very Low'),
            self::LOW => __('Low'),
            self::MEDIUM => __('Medium'),
            self::HIGH => __('High'),
            self::VERY_HIGH => __('Very High'),
        };
    }

    /**
     * Get the color of the enum
     */
    public function slug(): string
    {
        return match ($this) {
            self::UNKNOWN => 'no-data',
            self::VERY_LOW => 'vlo',
            self::LOW => 'low',
            self::MEDIUM => 'med',
            self::HIGH => 'hig',
            self::VERY_HIGH => 'vhi',
        };
    }

    /**
     * Mount an array for use as select in forms
     */
    public static function formList()
    {
        return [
            self::UNKNOWN->slug() => self::UNKNOWN->label(),
            self::VERY_LOW->slug() => self::VERY_LOW->label(),
            self::LOW->slug() => self::LOW->label(),
            self::MEDIUM->slug() => self::MEDIUM->label(),
            self::HIGH->slug() => self::HIGH->label(),
            self::VERY_HIGH->slug() => self::VERY_HIGH->label(),
        ];
    }

    /**
     * Get the color of the enum
     */
    public function color(): string
    {
        return match ($this) {
            self::UNKNOWN => '#9E7F669F',
            self::VERY_LOW => '#FED985',
            self::LOW => '#FEAA6D',
            self::MEDIUM => '#F46C58',
            self::HIGH => '#E4405C',
            self::VERY_HIGH => '#DB0026',
        };
    }

    /**
     * Get the formatted color of the enum
     */
    public function formattedColor(): string
    {
        $color = $this->color();
        return "bg-[{$color}]";
    }

    /**
     * Get the group label of the enum
     */
    public static function fromSlug($slug)
    {
        return match (strtolower($slug)) {
            'no-data' => self::UNKNOWN, // 'No Data
            'vlo' => self::VERY_LOW,
            'low' => self::LOW,
            'med' => self::MEDIUM,
            'hig' => self::HIGH,
            'vhi' => self::VERY_HIGH,
        };
    }
}
