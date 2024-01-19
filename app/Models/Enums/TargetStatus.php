<?php

namespace App\Models\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self not_started()
 * @method static self ongoing()
 * @method static self completed()
 */
class TargetStatus extends Enum
{
    protected static function values(): array
    {
        return [
            'not_started' => 'not-started',
            'ongoing' => 'ongoing',
            'completed' => 'completed',
        ];
    }

    protected static function labels(): array
    {
        return [
            'not_started' => __('Not started'),
            'ongoing' => __('Ongoing'),
            'completed' => __('Completed'),
        ];
    }
}
