<?php

namespace Database\Seeders\Tenant;

class InternalTagSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'internal_tags.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\InternalTag::class;

    protected $hasEnabledColumn = false;


    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'name' => 'name',
        'slug' => 'slug',
        'color' => 'color',
    ];

    /**
     * Default values when the column is empty
     */
    protected $default = [
        'order' => 0,
    ];
}
