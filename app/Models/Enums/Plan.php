<?php

namespace App\Models\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self screen()
 * @method static self assess()
 * @method static self discover()
 * @method static self plan()
 */
class Plan extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $name): string {
            return ucfirst($name);
        };
    }
}
