<?php

namespace App\Models\Tenant\Filters\Company;

use App\Models\Enums\CompanySize;
use App\Models\Tenant\Company;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class CompanySizeFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'data->size';

    public function title(): string
    {
        return __('Company size');
    }

    public function component(): string
    {
        return 'select';
    }

    public function apply(Builder $query): Builder
    {
        $this->values = collect($this->values)->map(function ($item) {
            return CompanySize::fromValue($item) ?? null;
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
        $sizes = array_combine(array_values(CompanySize::casesArray()), array_values(CompanySize::casesArray()));
        $sizes = array_merge(['Not defined' => __('Not defined')], $sizes);
        return  $sizes;
    }
}
