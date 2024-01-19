<?php

namespace App\Models\Tenant\Filters;

use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Filters\SelectFilter;

class InternalTagsStatus extends SelectFilter
{
    /**
     * @var string
     */
    protected string $field = 'deleted_at';

    /**
     * @return string
     */
    public function title(): string
    {
        return __('Status');
    }

    /**
     * @return string
     */
    public function component(): string
    {
        return 'select';
    }

    /**
     * @return string[]
     */
    public function options(): array
    {
        return [
            __('Active') => 'active',
            __('Deleted') => 'deleted',
        ];
    }

    /**
     * @param Builder $query
     * @return Builder
     */
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
