<?php

namespace App\Models\Tenant\Filters\Company;

use App\Models\Enums\Companies\Type;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class CompanyTypeFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'type';

    public function title(): string
    {
        return __('Company type');
    }

    public function component(): string
    {
        return 'select';
    }

    public function apply(Builder $query): Builder
    {
        $this->values = collect($this->values)->map(function ($item) {
            return Type::from($item) ?? null;
        })->toArray();

        $hasNull  = in_array(null, $this->values);
        $values = array_filter($this->values);

        if ($hasNull) {
            $query->whereNull($this->field);
        }
        if (count($values) > 0 && !$hasNull) {
            $query->whereIn($this->field, $values);
        } else {
            $query->orWhereIn($this->field, $values);
        }
        return $query;
    }

    public function options(): array
    {
        $types = array_combine(array_values(Type::casesArray()), array_values(Type::keys()));
        $types = array_merge(['Not defined' => __('Not defined')], $types);
        return  $types;
    }
}
