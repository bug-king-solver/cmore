<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

/**
 * The ChartRange enum represents the available chart ranges.
 */
enum ChartRange: int
{
    use EnumToArray;

    case LAST_7_DAYS = 7;
    case LAST_14_DAYS = 14;
    case LAST_30_DAYS = 30;
    case LAST_ONE_YEAR = 365;

    /**
     * Returns the label for the ChartRange object.
     *
     * @return string The label for the ChartRange object.
     */
    public function label(): string
    {
        return match ($this) {
            self::LAST_7_DAYS => __('Last :number days', ['number' => self::LAST_7_DAYS->value]),
            self::LAST_14_DAYS => __('Last :number days', ['number' => self::LAST_14_DAYS->value]),
            self::LAST_30_DAYS => __('Last :number days', ['number' => self::LAST_30_DAYS->value]),
            self::LAST_ONE_YEAR => __('Last Year'),
        };
    }

    /**
     * Returns an array representation of the ChartRange object.
     *
     * @return array An array representation of the ChartRange object.
     */
    public static function toArray(): array
    {
        return self::casesArray();
    }

    /**
     * Returns an array of active filters for the chart range.
     * @return array An array of active filters, each containing a 'value' and 'title' key.
     */
    public static function getFiltersActive(): array
    {
        return [
            [
                'value' => self::LAST_7_DAYS->value,
                'title' => self::LAST_7_DAYS->filterMultilineLabel(),
            ],
            [
                'value' => self::LAST_30_DAYS->value,
                'title' => self::LAST_30_DAYS->filterMultilineLabel(),
            ],
            [
                'value' => self::LAST_ONE_YEAR->value,
                'title' => self::LAST_ONE_YEAR->filterMultilineLabel(),
            ],
        ];
    }

    /**
     * Returns a string with the filter label for the chart range.
     *
     * @return string The filter label for the chart range.
     */
    public function filterMultilineLabel(): string
    {
        return match ($this) {
            self::LAST_7_DAYS => __('Last :number days', ['number' => 7]),
            self::LAST_14_DAYS => __('Last :number days', ['number' => 14]),
            self::LAST_30_DAYS => __('Last :number weeks', ['number' => 4]),
            self::LAST_ONE_YEAR => __('Last :number months', ['number' => 12]),
        };
    }

    /**
     * Returns a string with the chart label for the given key, based on the chart range.
     *
     * @param string $key The key to be used to generate the chart label.
     * @return string The chart label for the given key.
     */
    public function chartMultilineLabel(string $key): string
    {
        return match ($this) {
            self::LAST_7_DAYS => carbon()->parse($key)->format('D'),
            self::LAST_14_DAYS => carbon()->parse($key)->format('D'),
            self::LAST_30_DAYS => __('Week') . ' ' . carbon()->parse($key)->weekOfYear,
            self::LAST_ONE_YEAR => carbon()->parse($key)->format('M Y'),
        };
    }
}
