<?php

namespace App\Models\Tenant\Filters;

use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Filters\SelectFilter;

class TagsStatus extends SelectFilter
{
    protected string $field = 'deleted_at';

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
            __('Active') => 'active',
            __('Deleted') => 'deleted',
        ];
    }

    public function apply(Builder $query): Builder
    {
        return count($this->values[$this->field]) == 2
            ? $query
            : match($this->values[$this->field][0]) {
                'active' => $query->whereNull($this->field),
                'deleted' => $query->whereNotNull($this->field),
                default => $query,
            };
    }
}
