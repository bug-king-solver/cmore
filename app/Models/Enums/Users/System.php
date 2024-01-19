<?php

namespace App\Models\Enums\Users;

use App\Models\Traits\EnumToArray;

enum System: int
{
    use EnumToArray;

    case NO = 0;
    case YES = 1;

    /**
     * Get the label for the user type
     */
    public function label(): string
    {
        return match ($this) {
            self::NO => __('App user'),
            self::YES => __('System user'),
        };
    }

    /**
     * Check if is a system user
     */
    public function isSystemUser(): bool
    {
        return $this === static::YES;
    }

    /**
     * Check if is not a system user
     */
    public function isNotSystemUser(): bool
    {
        return ! $this->isSystemUser();
    }

    /**
     * Check if the is an app user
     */
    public function isAppUser(): bool
    {
        return $this === static::NO;
    }

    /**
     * Check if the user type is internal
     */
    public function isNotAppUser(): bool
    {
        return ! $this->isAppUser();
    }
}
