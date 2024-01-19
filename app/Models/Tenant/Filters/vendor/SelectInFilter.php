<?php

namespace App\Models\Tenant\Filters\vendor;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Lacodix\LaravelModelFilter\Filters\Filter;

class SelectInFilter extends Filter
{
    protected string $field;

    protected array $values;

    public function apply(Builder $query): Builder
    {
        return $query->whereIn($this->field, $this->values);
    }
}
