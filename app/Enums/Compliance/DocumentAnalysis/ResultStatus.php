<?php

namespace App\Enums\Compliance\DocumentAnalysis;

use App\Enums\Interfaces\Color;
use App\Enums\Interfaces\Label;

enum ResultStatus: string implements Label, Color
{
    case IN_QUEUE = 'in_queue';
    case PROCESSING = 'processing';
    case COMPLETE = 'complete';

    /**
     * Get the label for each status
     */
    public function label(): string
    {
        return match ($this) {
            static::IN_QUEUE => __('In Queue'),
            static::PROCESSING => __('Processing'),
            static::COMPLETE => __('Complete'),
        };
    }

    /**
     * Get the color for each level
     */
    public function color(): string
    {
        return match ($this) {
            static::IN_QUEUE => 'bg-esg30',
            static::PROCESSING => 'bg-esg31',
            static::COMPLETE => 'bg-esg32',
        };
    }
}
