<?php

namespace App\Http\Livewire\Traits;

use App\Models\Enums\ChartRange;
use App\Models\Tenant\Groups;
use App\Models\Tenant\Target;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use ReflectionClass;

trait TargetQueryTrait
{
    public $ongoingTasks;

    public $notStartedTasks;

    public $completedTasks;

    public $overdueTasks;

    public array $weeks;

    public DateTime $ongoingDate;

    public DateTime $completedDate;

    public DateTime $overdueDate;

    public array $ongoingInterval;

    public array $completedInterval;

    public array $overdueInterval;

    public array $filterIntervalList;

    public function bootTargetQueryTrait()
    {
        $this->listeners += [
            'changeOngoingInterval' => 'changeOngoingInterval',
            'changeCompletedInterval' => 'changeCompletedInterval',
            'changeOverdueInterval' => 'changeOverdueInterval',
        ];
    }

    public function mountTargetQueryTrait()
    {
        $this->setFilterIntervalList();

        $defaultInterval = 7;

        $title = array_filter($this->filterIntervalList, function ($item) use ($defaultInterval) {
            if ($item['value'] == $defaultInterval) {
                return $item['title'];
            }
        });

        $title = reset($title);
        $defaultTitle = $title['title'];

        $defaultintervalArray = [
            'interval' => $defaultInterval,
            'title' => $defaultTitle,
        ];

        $this->ongoingInterval = $defaultintervalArray;
        $this->completedInterval = $defaultintervalArray;
        $this->overdueInterval = $defaultintervalArray;
    }

    /**
     * Renders the target query trait by setting the date ranges and retrieving data for ongoing, completed, and overdue tasks.
     * @return void
     * @throws Exception If an error occurs while rendering the target query trait.
     */
    public function renderingTargetQueryTrait()
    {
        $this->ongoingDate = carbon()->now()->sub($this->ongoingInterval['interval'], 'days');
        $this->completedDate = carbon()->now()->sub($this->completedInterval['interval'], 'days');
        $this->overdueDate = carbon()->now()->sub($this->overdueInterval['interval'], 'days');

        $targetsFromGroup = null;
        if ($this->parentGroupId) {
            $targetsFromGroup = Groups::allTargetsFromSubGroups($this->parentGroupId);
        }

        $this->ongoingTasks = $this->setOngoingBoardData($targetsFromGroup);
        $this->completedTasks = $this->setCompletedBoardData($targetsFromGroup);
        $this->overdueTasks = $this->setOverdueBoardData($targetsFromGroup);
    }

    /**
     * Sets the title of the date range interval for the time period to analyze.
     * @param int $interval The interval to use (7, 30, or 365).
     * @return string The title of the date range interval.
     * @throws Exception If an error occurs while setting the title of the date range interval.
     */
    public function setIntervalTitle($interval)
    {
        $title = array_filter($this->filterIntervalList, function ($item) use ($interval) {
            if ($item['value'] == $interval) {
                return $item['title'];
            }
        });
        $title = reset($title);

        return $title['title'] ?? '';
    }

    public function setFilterIntervalList()
    {
        $this->filterIntervalList = ChartRange::getFiltersActive();
    }

    /**
     * Set the date range interval for the time period to analyze.
     * @param DateTime $startDate The start date for the time period.
     * @param int $interval The interval to use (7, 365, or other).
     * @return array The date range interval as an array of start and end dates.
     * @throws Exception If an error occurs while setting the date range interval.
     */
    public function setDateRangeInterval(DateTime $startDate, int $interval): Exception|array
    {
        try {
            // Set the start and end dates for the time period to analyze
            $endDate = carbon()->today()->endOfDay();
            $dateRangeInterval = [];
            // Set the date range interval based on the specified interval
            switch ($interval) {
                case ChartRange::LAST_7_DAYS->value:
                    // Loop through the days in the time period
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                        // Add the start and end of the day to the date range interval
                        $dateRangeInterval[] = [
                            'start' => $date->copy()->startOfDay(),
                            'end' => $date->copy()->endOfDay(),
                        ];
                    }
                    break;
                case ChartRange::LAST_ONE_YEAR->value:
                    // Loop through the months in the time period
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
                        // Add the start and end of the month to the date range interval
                        $dateRangeInterval[] = [
                            'start' => $date->copy()->startOfMonth(),
                            'end' => $date->copy()->endOfMonth(),
                        ];
                    }
                    break;
                default:
                    // Loop through the weeks in the time period
                    $startDate = $startDate->addDay(ChartRange::LAST_14_DAYS->value);
                    $endDate = $endDate->addDay(ChartRange::LAST_7_DAYS->value);
                    for ($date = $startDate->copy(); $date->lte($endDate); $date->addWeek()) {
                        // Add the start and end of the week to the date range interval
                        $dateRangeInterval[] = [
                            'start' => $date->copy()->startOfWeek(),
                            'end' => $date->copy()->endOfWeek(),
                        ];
                    }
                    break;
            }
            $this->dateRangeInterval = $dateRangeInterval;

            return $dateRangeInterval;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function setParsedIntervalNames(array $result, int $interval)
    {
        foreach ($result as $key => $value) {
            switch ($interval) {
                case ChartRange::LAST_7_DAYS->value:
                    $resultKey = ChartRange::LAST_7_DAYS->chartMultilineLabel($key);
                    break;
                case ChartRange::LAST_30_DAYS->value:
                    $resultKey = ChartRange::LAST_30_DAYS->chartMultilineLabel($key);
                    break;
                case ChartRange::LAST_ONE_YEAR->value:
                    $resultKey = ChartRange::LAST_ONE_YEAR->chartMultilineLabel($key);
                    break;
            }
            $result[$resultKey] = ['total' => $value];
            unset($result[$key]);
        }

        return $result;
    }

    public function groupResultsByInterval($data, $column, $interval)
    {
        $result = [];
        try {
            if (count($this->dateRangeInterval) == 0 || $data->count() == 0 || $data == null) {
                return [];
            }

            $targetsByWeek = $data->groupBy(function ($model) use ($column) {
                foreach ($this->dateRangeInterval as $week) {
                    if ($model->$column != null && $model->$column->between($week['start'], $week['end'])) {
                        return $week['start']->format('Y-m-d');
                    }
                }
            });

            foreach ($this->dateRangeInterval as $week) {
                $data = $targetsByWeek->get($week['start']->format('Y-m-d'));
                $result[$week['start']->format('Y-m-d')] = $data ? $data->count() : 0;
            }

            $result = $this->setParsedIntervalNames($result, $interval);
        } catch (Exception | QueryException $ex) {
            throw $ex;
        }

        return $result;
    }

    /**
     * Returns an array with the start dates of each interval
     * defined in $this->dateRangeInterval as keys and initial values of 0.
     * @return array
     */
    public function getDefaultLabelsFromInterval(int $interval): array
    {
        $result = [];
        foreach ($this->dateRangeInterval as $week) {
            $result[$week['start']->format('Y-m-d')] = 0;
        }

        return $this->setParsedIntervalNames($result, $interval);
    }

    /**
     * Formats an array of data to be used in a Livewire component.
     * @param array $data The data to be formatted.
     * @param array $targetCreated The targets created.
     * @return array The formatted data.
     */
    public function formatArrayToLivewire(int $interval, array $data, array $targetCreated): array
    {
        $newData = [];
        $labels = count($data) > 0 ? array_keys($data) : array_keys($this->getDefaultLabelsFromInterval($interval));
        $totalTargets = array_sum(array_column($data, 'total')) ?? 0;
        $totalArrayInterval = array_column($data, 'total') ?? [];

        if (count($totalArrayInterval) == 0) {
            $totalArrayInterval = array_map(function ($label) {
                return 0;
            }, $labels);
        }

        $newData['data'] = $data;
        $newData['total'] = $totalTargets;
        $newData['totalColumns'] = $totalArrayInterval;
        $newData['labels'] = $labels;
        $newData['totaisCreated'] = array_column($targetCreated, 'total') ?? [];

        return $newData;
    }

    public function getTargets(Builder $targets, int $interval, string $columnFilter, null|array $targetIds)
    {
        // If we have target IDs then filter the query
        if (is_array($targetIds)) {
            $targets = $targets->whereIn('id', $targetIds);
        }

        // Group the results by interval
        return  $this->groupResultsByInterval(
            $targets->get(),
            $columnFilter,
            $interval
        );
    }

    /**
     * Sets the ongoing board data for the given target IDs.
     * @param array $targetIds The IDs of the targets to retrieve ongoing data for.
     * @return array The formatted ongoing targets as an array.
     * @throws Exception If an error occurs while setting the ongoing board data.
     */
    public function setOngoingBoardData(null|array $targetIds)
    {
        $interval = $this->ongoingInterval['interval'];
        // Set the date range and interval
        $this->setDateRangeInterval($this->ongoingDate, $interval);

        // Fetch the targets and group them by interval
        $targets = $this->getTargets(Target::ongoing($this->ongoingDate), $interval, 'started_at', $targetIds);

        // Format the results for the Livewire chart
        return $this->formatArrayToLivewire($interval, $targets, []);
    }

    /**
     * Sets the completed board data for the given target IDs.
     * @param array $targetIds The IDs of the targets to retrieve completed data for.
     * @return array The formatted completed targets as an array.
     * @throws Exception If an error occurs while setting the completed board data.
     */
    public function setCompletedBoardData(null|array $targetIds)
    {
        $interval = $this->completedInterval['interval'];
        // Set the date range and interval
        $this->setDateRangeInterval($this->completedDate, $interval);

        // Fetch the targets and group them by interval
        $targets = $this->getTargets(Target::completed($this->completedDate), $interval, 'completed_at', $targetIds);

        // Format the results for the Livewire chart
        return $this->formatArrayToLivewire($interval, $targets, []);
    }

    /**
     * Sets the overdue board data for the given target IDs.
     * @param array $targetIds The IDs of the targets to retrieve overdue data for.
     * @return array The formatted overdue targets as an array.
     * @throws Exception If an error occurs while setting the overdue board data.
     */
    public function setOverdueBoardData(null|array $targetIds): array
    {
        $interval = $this->overdueInterval['interval'];

        // Set the date range and interval
        $this->setDateRangeInterval($this->overdueDate, $interval);

        // Fetch the targets and group them by interval
        $targets = $this->getTargets(Target::overdue($this->overdueDate), $interval, 'due_date', $targetIds);

        // Format the results for the Livewire chart
        return $this->formatArrayToLivewire($interval, $targets, []);
    }

    /**
     * Changes the ongoing interval.
     *
     * This method updates the ongoing interval with a new value and its corresponding title.
     * The new value is passed as a parameter and the method calls setIntervalTitle() to set the title.
     * @param string $value The new interval value.
     * @return void
     */
    public function changeOngoingInterval($value)
    {
        $this->ongoingInterval = [
            'interval' => $value,
            'title' => $this->setIntervalTitle($value),
        ];
    }

    /**
     * Changes the completed interval.
     * @description This method updates the completed interval with a new value and its corresponding title.
     * The new value is passed as a parameter and the method calls setIntervalTitle() to set the title.
     * @param string $value The new interval value.
     * @return void
     */
    public function changeCompletedInterval($value)
    {
        $this->completedInterval = [
            'interval' => $value,
            'title' => $this->setIntervalTitle($value),
        ];
    }

    /**
     * Changes the overdue interval.
     * @param string $value The new interval value.
     * @return void
     */
    public function changeOverdueInterval($value)
    {
        $this->overdueInterval = [
            'interval' => $value,
            'title' => $this->setIntervalTitle($value),
        ];
    }

    public function getTargetDataChart($targetIds)
    {
        $targets = Target::whereIn('id', $targetIds)->get();

        $today = carbon()->now()->subDay(1)->endOfDay();
        $almostToday = carbon()->now()->addDay(7)->endOfDay();
        $overdue = 0;

        $total = $targets->count();

        $overdue = $targets->map(function ($target) use ($today) {
            if ($target->due_date < $today && $target->completed_at == null && $target->status != 'not-started') {
                return 1;
            }

            return 0;
        })->sum();

        $almostOverdue = $targets->map(function ($target) use ($today, $almostToday) {
            if ($target->due_date >= $today && $target->due_date < $almostToday && $target->completed_at == null && $target->status != 'not-started') {
                return 1;
            }

            return 0;
        })->sum();

        $notStarted = $targets->map(function ($target) use ($today, $almostToday) {
            if ($target->started_at == null) {
                if ($target->due_date >= $today && $target->due_date < $almostToday && $target->completed_at == null) {
                    return 0;
                }
                if ($target->due_date < $today && $target->completed_at == null) {
                    return 0;
                }

                return 1;
            }

            return 0;
        })->sum();

        $ongoing = $targets->map(function ($target) use ($today, $almostToday) {
            if ($target->status == 'ongoing') {
                if ($target->due_date >= $today && $target->due_date < $almostToday && $target->completed_at == null) {
                    return 0;
                } elseif ($target->due_date < $today && $target->completed_at == null) {
                    return 0;
                }

                return 1;
            }

            return 0;
        })->sum();

        $completed = $targets->map(function ($target) {
            return $target->completed_at != null ? 1 : 0;
        })->sum();

        //todo maybe, implemente not started ?  $ongoing = $total - ($overdue + $almostOverdue + $completed + $notStarted);
        $totalOngoing = $overdue + $almostOverdue + $ongoing;

        return [
            'total' => $total,
            'overdue' => $overdue,
            'almostOverdue' => $almostOverdue,
            'notStarted' => $notStarted,
            'ongoing' => $ongoing,
            'completed' => $completed,
            'notCompleted' => ($total - $completed),
            'progressOngoing' => calculatePercentage($ongoing, $totalOngoing),
            'progressCompleted' => calculatePercentage($completed, $total),
        ];
    }
}
