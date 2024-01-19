<?php

namespace App\Models\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self binary()
 * @method static self checkbox()
 * @method static self checkbox_obs()
 * @method static self text()
 * @method static self text_long()
 * @method static self integer()
 * @method static self decimal()
 * @method static self matrix()
 * @method static self countries_multi()
 * @method static self sdgs_multi()
 * @method static self currency()
 */
class AnswerType extends Enum
{
    protected static function values(): array
    {
        return [
            'checkbox_obs' => 'checkbox-obs',
            'text_long' => 'text-long',
            'countries_multi' => 'countries-multi',
            'sdgs_multi' => 'sdgs-multi',
            'currency' => 'currency',
        ];
    }
}
