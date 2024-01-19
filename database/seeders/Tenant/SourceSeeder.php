<?php

namespace Database\Seeders\Tenant;

class SourceSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'sources.json';

    /**
     * False if table doesnt have enable column
     */
    protected $hasEnabledColumn = false;

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\Source::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'name' => 'name',
    ];
}
