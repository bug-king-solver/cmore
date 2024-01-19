<?php

namespace App\Models\Tenant\Filters\vendor;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Lacodix\LaravelModelFilter\Filters\Filter;

class ReferenceManyFilter extends Filter
{
    protected string $field;
    protected string $relation;
    protected string $condition;


    public function apply(Builder $query): Builder
    {
        if (is_array($this->values)) {
            return $query->whereHas($this->relation, function ($query) {
                $query->whereIn($this->field, $this->values)
                ->whereNotNull($this->condition);
            });
        } else {
            return $query->where($this->field, $this->values);
        }
    }
}
