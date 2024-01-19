<?php

namespace App\Models\Tenant\Filters\vendor;

use Illuminate\Database\Eloquent\Builder;

trait FilterRelationTrait
{
    public function apply(Builder $query): Builder
    {
        return $query->whereHas($this->relation, function (Builder $query) {
            $query->whereIn($this->field, $this->values);
        });
    }
}
