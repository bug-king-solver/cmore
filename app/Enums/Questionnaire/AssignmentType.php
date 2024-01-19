<?php

namespace App\Enums\Questionnaire;

use App\Models\Traits\EnumToArray;

enum AssignmentType: string
{
    use EnumToArray;
     
    case CAN_VALIDATE = 'can_validate';
    case CAN_ANSWER = 'can_answer';

    public function label(): string
    {
        return match($this) {
            AssignmentType::CAN_VALIDATE => __('Can Validate'),
            AssignmentType::CAN_ANSWER => __('Can Answer'),
           
        };
    }
}
