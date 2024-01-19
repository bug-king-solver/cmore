<?php

namespace App\Models\Tenant\Filters\Indicatores;

use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Filters\SelectFilter;

class TypeFilter extends SelectFilter
{
    protected string $field = 'calc';

    public function title(): string
    {
        return __('Type');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return [
            __('Simple') => 'simple',
            __('Compound') => 'compound',
        ];
    }

    public function apply(Builder $query): Builder
    {
        return count($this->values[$this->field]) == 2
            ? $query
            : match($this->values[$this->field][0]) {
                'simple' => $query->whereNull($this->field),
                'compound' => $query->whereNotNull($this->field),
                default => $query,
            };
    }
}
