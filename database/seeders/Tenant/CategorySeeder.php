<?php

namespace Database\Seeders\Tenant;

class CategorySeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'categories.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\Category::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'note' => 'note',
        'order' => 'order',
        'parent_id' => 'parent_id',
        'name' => 'name',
        'description' => 'description',
        'extra' => 'extra',
        'model_type' => 'model_type',
        'model_id' => 'model_id',
    ];

    /**
     * Default values when the column is empty
     */
    protected $default = [
        'order' => 0,
    ];
}
