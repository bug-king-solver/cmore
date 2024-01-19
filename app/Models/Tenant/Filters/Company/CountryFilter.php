<?php

namespace App\Models\Tenant\Filters\Company;

use App\Models\Tenant\Filters\vendor\SelectInFilter;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class CountryFilter extends SelectInFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'country';

    public function title(): string
    {
        return __('Country');
    }

    public function component(): string
    {
        return 'select';
    }

    public function options(): array
    {
        return array_column(array_values(getCountriesForSelect()), 'id', 'title');
    }
}
