<?php

namespace App\Enums\Sources;

use App\Models\Traits\EnumToArray;

enum SourceType: int
{
    use EnumToArray;

    case FRAMEWORK = 1;
    case INDEX = 2;

    public function label(): string
    {
        return match ($this) {
            SourceType::FRAMEWORK => __('Framework'),
            SourceType::INDEX => __('Index'),
        };
    }

    public function isFramework()
    {
        return $this === static::FRAMEWORK;
    }

    public function isIndex()
    {
        return $this === static::INDEX;
    }
}
