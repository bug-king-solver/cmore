<?php

namespace App\Models\Enums\User;

use App\Models\Traits\EnumToArray;

enum UserType: string
{
    use EnumToArray;

    case NOT_SYSTEM = '0';
    case SYSTEM = '1';
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';

    /**
     * Get the label for the user type
     */
    public function label(): string
    {
        return match ($this) {
            UserType::NOT_SYSTEM => __('Not System'),
            UserType::SYSTEM => __('System'),
            UserType::INTERNAL => __('Internal'),
            UserType::EXTERNAL => __('External'),
        };
    }

    /**
     * Check if the user type is internal
     */
    public function isSystem()
    {
        return $this === static::SYSTEM;
    }

    /**
     * Check if the user type is not internal
     */
    public function isNotSystem()
    {
        return $this === static::NOT_SYSTEM;
    }
}
