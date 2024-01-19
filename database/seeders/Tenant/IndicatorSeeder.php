<?php

namespace Database\Seeders\Tenant;

class IndicatorSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'indicators.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\Indicator::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'category_id' => 'category_id',
        'unit_qty' => 'unit_qty',
        'unit_default' => 'unit_default',
        'calc' => 'calc',
        'is_financial' => 'is_financial',
        'has_benchmarking' => 'has_benchmarking',
        'name' => 'name',
        'data' => 'data',
        'description' => 'description',
    ];

    /**
     * Default values when the column is empty
     */
    protected $default = [
        'is_financial' => false,
        'has_benchmarking' => false,
    ];
}
