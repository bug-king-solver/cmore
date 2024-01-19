<?php

namespace App\Models\Enums;

use Closure;
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self articles()
 * @method static self books()
 * @method static self compact()
 * @method static self gri()
 * @method static self internal()
 * @method static self attachments()
 * @method static self pri()
 * @method static self sasb()
 * @method static self unsdg()
 * @method static self unglobal()
 */
class DocumentTypeEnum extends Enum
{
    protected static function labels(): Closure
    {
        return function (string $name): string {
            return mb_strtoupper($name);
        };
    }
}
