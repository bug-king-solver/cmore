<?php

namespace Database\Seeders\Tenant;

class InitiativeSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'initiatives.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\Initiative::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'parent_id' => 'parent_id',
        'sdg_id' => 'sdg_id',
        'category_id' => 'category_id',
        'order' => 'order',
        'impact' => 'impact',
        'name' => 'name',
    ];

    /**
     * Default values when the column is empty
     */
    protected $default = [
        'order' => 0,
        'impact' => 0,
    ];
}
