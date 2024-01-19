<?php

namespace App\Models\Enums\Users;

use App\Models\Traits\EnumToArray;

/**
 * Internal user: The user belongs to the organization that owns the tenant
 * External user: The user doesn't belongs to the organization that owns the tenant (eg. clients and suppliers)
 */
enum Type: string
{
    use EnumToArray;

    case INTERNAL = 'internal';
    case EXTERNAL = 'external';

    /**
     * Get the label for the user type
     */
    public function label(): string
    {
        return match ($this) {
            self::INTERNAL => __('Internal'),
            self::EXTERNAL => __('External'),
        };
    }

    /**
     * Check if the user type is internal
     */
    public function isInternal()
    {
        return $this === static::INTERNAL;
    }

    /**
     * Check if the user type is internal
     */
    public function isNotInternal()
    {
        return ! $this->isInternal();
    }

    /**
     * Check if the user type is external
     */
    public function isExternal()
    {
        return $this === static::EXTERNAL;
    }

    /**
     * Check if the user type is external
     */
    public function isNotExternal()
    {
        return ! $this->isExternal();
    }
}
