<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum ChartTypesEnum: string
{
    use EnumToArray;

        // ['bar', 'line', 'pie', 'doughnut', 'radar', 'polarArea', 'bubble', 'scatter', 'horizontalBar'];
    case BAR = 'bar';
    case LINE = 'line';
    case PIE = 'pie';
    case DOUGHNUT = 'doughnut';
    case RADAR = 'radar';
    case POLAR_AREA = 'polarArea';
    case BUBBLE = 'bubble';
    case SCATTER = 'scatter';
    case HORIZONTAL_BAR = 'horizontalBar';

    /**
     * Get the label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::BAR => 'Bar',
            self::LINE => 'Line',
            self::PIE => 'Pie',
            self::DOUGHNUT => 'Doughnut',
            self::RADAR => 'Radar',
            self::POLAR_AREA => 'Polar Area',
            self::BUBBLE => 'Bubble',
            self::SCATTER => 'Scatter',
            self::HORIZONTAL_BAR => 'Horizontal Bar',
        };
    }

    /**
     * Return an array of the enum values.
     */
    public static function toArray(): array
    {
        return self::casesArray();
    }
}
