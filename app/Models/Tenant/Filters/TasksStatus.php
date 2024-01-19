<?php

namespace App\Models\Tenant\Filters;

use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Filters\SelectFilter;

class TasksStatus extends SelectFilter
{
    protected string $field = 'completed';

    public function title(): string
    {
        return __('Status');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return [
            __('Completed') => 'completed',
            __('Ongoing') => 'ongoing',
        ];
    }

    public function apply(Builder $query): Builder
    {
        return count($this->values[$this->field]) == 2
            ? $query
            : match ($this->values[$this->field][0]) {
                'completed' => $query->where($this->field, 1),
                'ongoing' => $query->where($this->field, 0),
                default => $query,
            };
    }
}
