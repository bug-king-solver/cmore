<?php

namespace App\Models\Enums;

use App\Models\Enums\ResourcesForGroups;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Target;
use App\Models\Tenant\Task;
use App\Models\Traits\EnumToArray;

enum ReportingPeriodsTypeEnum: string
{
    use EnumToArray;

    case year = 'year';
    case semester = 'semester';
    case quarter = 'quarter';
    case month = 'month';

    /**
     * Get the label for the enum value.
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::year => __('Year'),
            self::semester => __('Semester'),
            self::quarter => __('Quarter'),
            self::month => __('Month'),
        };
    }

    /**
     * Return the duration value of each reporting period.
     * The duration , return the value in months.
     * @return int
     */
    public function duration(): int
    {
        return match ($this) {
            self::year => 12,
            self::semester => 6,
            self::quarter => 3,
            self::month => 1,
        };
    }

    /**
     * Get the starter month for the reporting period based on the order of the reporting period.
     * @param int $order
     * @return int
     */
    public function startMonth(int $order): int
    {
        if ($this == self::year && $order > 1) {
            throw new \Exception('One year cannot have more than 1 reporting period');
        }

        if ($this == self::semester && $order > 2) {
            throw new \Exception('One year only have 2 semesters');
        }

        if ($this == self::quarter && $order > 4) {
            throw new \Exception('One year only have 4 quarters');
        }

        return match ($this) {
            self::year => 1,
            self::semester => $order == 1 ? 1 : 7,
            self::quarter => ($order == 1) ? 1 : (($order == 2) ? 4 : (($order == 3) ? 7 : 10)),
            self::month => $order,
        };
    }

    /**
     * Get the starter month for the reporting period based on the order of the reporting period.
     * @param int $year - the year of the reporting period
     * @param int $order - the order of the reporting period
     * @return string
     */
    public function startDate(int $year, int $order): string
    {
        return carbon()->createFromDate($year, self::startMonth($order))
            ->startOfMonth()
            ->format('Y-m-d');
    }

    /**
     * Get the starter month for the reporting period based on the order of the reporting period.
     * @param int $year - the year of the reporting period
     * @param int $order - the order of the reporting period
     * @return string
     */
    public function endDate(int $year, int $order): string
    {
        return carbon()->createFromDate($year, self::startMonth($order))
            ->addMonths($this->duration() - 1)
            ->subDay()
            ->endOfMonth()
            ->format('Y-m-d');
    }
}
