<?php

namespace App\Enums\Questionnaire;

use App\Models\Traits\EnumToArray;

enum ValidationStatus:string {
    use EnumToArray;
     
    const NO_ACTION_YET = null;
    const VALIDATED = 1;
    const UNDER_REVIEW = 2;

    public function label(): string
    {
        return match($this) {
            self::NO_ACTION_YET => __('No Action Yet'),
            self::VALIDATED => __('Validated'),
            self::UNDER_REVIEW => __('Under Review'),
            default => '',
           
        };
    }
}
