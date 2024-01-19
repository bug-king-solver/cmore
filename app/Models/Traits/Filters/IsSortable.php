<?php

namespace App\Models\Traits\Filters;

use Lacodix\LaravelModelFilter\Traits\IsSortable as Sortable;

trait IsSortable
{
    use Sortable;

    /**
     * Return the sortable fields
     */
    public function getSortable(): array
    {
        return $this->sortable ?? [];
    }
}
