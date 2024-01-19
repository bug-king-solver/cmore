<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum PhysicalRisksRelevanceEnum: string
{
    use EnumToArray;

        // ['bar', 'line', 'pie', 'doughnut', 'radar', 'polarArea', 'bubble', 'scatter', 'horizontalBar'];
    case CRITICAL = 'critical';
    case HIGH_RELEVANT = 'high_relevant';
    case RELEVANT = 'relevant';
    case LOW_RELEVANT = 'low_relevant';

    /**
     * Get the label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::CRITICAL => __('Critical'),
            self::HIGH_RELEVANT => __('High Relevant'),
            self::RELEVANT => __('Relevant'),
            self::LOW_RELEVANT => __('Low Relevant'),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::CRITICAL => __("Maximum relevance, all or nearly all of the company's activity or value is dependent on the operations carried out in this geography."),
            self::HIGH_RELEVANT => __("While the company has activities that are not reliant on this geography, over half of its activity or value is dependent on the operations carried out in this geography."),
            self::RELEVANT => __("The operations taking place in this geography are impactful but do not have implications that surpass over half of the company's activity or value."),
            self::LOW_RELEVANT => __("The geography in question is relevant to the company's operations but represents less than a tenth of its activity or value."),
        };
    }

    /**
     * Return an array of the enum values.
     */
    public static function toArray(): array
    {
        return self::casesArray();
    }
}
