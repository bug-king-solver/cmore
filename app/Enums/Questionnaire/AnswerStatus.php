<?php

namespace App\Enums\Questionnaire;

use App\Models\Traits\EnumToArray;

enum AnswerStatus: string
{
    use EnumToArray;
    case ANSWERED = 'answered';
    case NOT_ANSWERED = 'not_answered';
    case VALIDATED = 'validated';
    case NOT_VALIDATED = 'not_validated';

    public function label(): string
    {
        return match ($this) {
            AnswerStatus::ANSWERED => __('Answered'),
            AnswerStatus::NOT_ANSWERED => __('Not Answered'),
            AnswerStatus::VALIDATED => __('Validated'),
            AnswerStatus::NOT_VALIDATED => __('Not Validated'),
           
        };
    }
}
