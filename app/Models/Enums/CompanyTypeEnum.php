<?php

namespace App\Models\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self internal()
 * @method static self customer()
 * @method static self supplier()
 */
class CompanyTypeEnum extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $name): string {
            return ucfirst($name);
        };
    }
}
