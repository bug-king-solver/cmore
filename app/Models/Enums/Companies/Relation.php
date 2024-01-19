<?php

namespace App\Models\Enums\Companies;

use App\Models\Traits\EnumToArray;

enum Relation: string
{
    use EnumToArray;

    case CLIENT = 'client';
    case SUPPLIER = 'supplier';

    /**
     * Get the label for the user type
     */
    public function label(): string
    {
        return match ($this) {
            self::CLIENT => __('Client'),
            self::SUPPLIER => __('Supplier'),
        };
    }

    /**
     * Get the label for the user type
     */
    public function labelPlural(): string
    {
        return match ($this) {
            self::CLIENT => __('Clients'),
            self::SUPPLIER => __('Suppliers'),
        };
    }

    /**
     * Check if the company is a client
     */
    public function isClient()
    {
        return $this === static::CLIENT;
    }

    /**
     * Check if the companye is not a client
     */
    public function isNotClient()
    {
        return ! $this->isClient();
    }

    /**
     * Check if the company is a supplier
     */
    public function isSupplier()
    {
        return $this === static::SUPPLIER;
    }

    /**
     * Check if the company is not a supplier
     */
    public function isNotSupplier()
    {
        return ! $this->isSupplier();
    }
}
