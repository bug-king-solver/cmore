<?php

namespace App\Models\Traits;

trait HasCustomColumns
{
    /**
     * Add custom columns to fillable columns
     */
    public function parseCustomColumns()
    {
        $customColumns = tenant()->features[$this->feature]['custom_columns'] ?? [];
        $customColumns = array_pluck($customColumns, 'id');

        $this->mergeFillable($customColumns);
    }
}
