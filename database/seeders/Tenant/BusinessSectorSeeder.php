<?php

namespace Database\Seeders\Tenant;

class BusinessSectorSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'business_sectors.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\BusinessSector::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'business_sector_type_id' => 'business_sector_type_id',
        'parent_id' => 'parent_id',
        'name' => 'name',
    ];
}
