<?php

namespace App\Enums\Compliance\DocumentAnalysis;

use App\Enums\Interfaces\Color;
use App\Enums\Interfaces\Label;
use App\Models\Traits\EnumToArray;

enum ResultComplianceLevel: string implements Label, Color
{
    use EnumToArray;

    case WAITING = 'waiting';
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    /**
     * Get the label for each level
     */
    public function label(): string
    {
        return match ($this) {
            static::WAITING => __('Waiting Analysis'),
            static::LOW => __('Low'),
            static::MEDIUM => __('Medium'),
            static::HIGH => __('High'),
        };
    }

    /**
     * Get the color for each level
     */
    public function color(): string
    {
        return match ($this) {
            static::WAITING => 'bg-esg35',
            static::LOW => 'bg-esg36',
            static::MEDIUM => 'bg-esg34',
            static::HIGH => 'bg-esg30',
        };
    }
}
