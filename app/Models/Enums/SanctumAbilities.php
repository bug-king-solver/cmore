<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum SanctumAbilities: string
{
    use EnumToArray;

    case WRITE = 'write';
    case READ = 'read';

    public function label(): string
    {
        return match ($this) {
            SanctumAbilities::WRITE => __('Write'),
            SanctumAbilities::READ => __('Read'),
        };
    }

    public static function toArray(): array
    {
        return self::casesArray();
    }
}
