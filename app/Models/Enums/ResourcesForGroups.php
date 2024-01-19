<?php

namespace App\Models\Enums;

use App\Models\Enums\ResourcesForGroups;
use App\Models\Tenant\Questionnaire;
use App\Models\Tenant\Target;
use App\Models\Tenant\Task;
use App\Models\Traits\EnumToArray;

enum ResourcesForGroups: string
{
    use EnumToArray;

    case TARGET = 'target';
    case QUESTIONNAIRE = 'questionnaire';
    case TASK = 'task';

    public function label(): string
    {
        return match ($this) {
            ResourcesForGroups::TARGET => (new Target())->getMorphClass(),
            ResourcesForGroups::QUESTIONNAIRE => (new Questionnaire())->getMorphClass(),
            ResourcesForGroups::TASK => (new Task())->getMorphClass(),
        };
    }

    public static function toArray(): array
    {
        return self::casesArray();
    }
}
