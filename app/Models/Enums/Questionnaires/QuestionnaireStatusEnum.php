<?php

namespace App\Models\Enums\Questionnaires;

use App\Models\Traits\EnumToArray;

enum QuestionnaireStatusEnum: string
{
    use \App\Models\Traits\EnumToArray;

    case ONGOING = 'ongoing';
    case SUBMITTED = 'submitted';


    /**
     * Get the label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::ONGOING => __('Ongoing'),
            self::SUBMITTED => __('Submitted'),
        };
    }

    /**
     * Get the label for the enum value.
     */
    public function labelPlural(): string
    {
        return match ($this) {
            self::ONGOING => __('Ongoing'),
            self::SUBMITTED => __('Submitted'),
        };
    }

    /**
     * Return an array of the enum values.
     * @return mixed[]
     */
    public static function toArray(): array
    {
        return self::casesArray();
    }
}
