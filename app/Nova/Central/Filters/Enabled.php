<?php
namespace App\Nova\Central\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Enabled extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('enabled', $value); // Assuming 'enabled' is the attribute in your model
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Enabled' => '1',
            'Disabled' => '0',
        ];
    }
}
