<?php

namespace App\Models\Tenant\Filters\Company;

use App\Models\Enums\Companies\Relation;
use App\Models\Tenant\Filters\vendor\SelectInFilter;
use Illuminate\Database\Eloquent\Builder;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class CompanyRelationFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'relation';

    public function title(): string
    {
        return __('Company Relation');
    }

    public function component(): string
    {
        return 'select';
    }

    public function apply(Builder $query): Builder
    {
        foreach ($this->values as $i => $value) {
            $where = $i === 0 ? 'where' : 'orWhere';
            $query->$where('relation', 'like', '%' . strtolower($value) . '%');
        }

        return $query;
    }

    public function options(): array
    {
        $sizes = array_combine(array_values(Relation::casesArray()), array_values(Relation::keys()));
        $sizes = array_merge(['Not defined' => __('Not defined')], $sizes);
        return  $sizes;
    }
}
