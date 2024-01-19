<?php

namespace App\Models\Traits;

trait HasHiddenColumns
{
    /**
     * Add custom columns to fillable columns
     */
    public static function hiddenColumns()
    {
        return tenant()->features[(new self())->feature]['hidden_columns'] ?? [];
    }
}
