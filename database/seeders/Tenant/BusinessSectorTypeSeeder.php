<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\BusinessSectorType;

class BusinessSectorTypeSeeder extends DataSeeder
{
    /**
     * File path to retrieve the data
     */
    protected $file = 'business_sector_types.json';

    /**
     * Related model
     */
    protected $model = \App\Models\Tenant\BusinessSectorType::class;

    /**
     * Keys are our columns, values are the portal columns
     */
    protected $columns = [
        'id' => 'id',
        'note' => 'note',
        'name' => 'name',
        'name_secondary' => 'name_secondary',
        'data' => 'data',
    ];

    /**
     * Method to run as seeder callback
     */
    public function seederCallback(): void
    {
        // By default all the new questionnaire types must be disabled.
        if ($this->idsCreated) {
            BusinessSectorType::whereIn('id', $this->idsCreated ?? [])->update(['enabled' => false]);
        }
    }
}
