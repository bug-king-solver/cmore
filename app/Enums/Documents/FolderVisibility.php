<?php

namespace App\Enums\Documents;

enum FolderVisibility: string
{
    case Internal = 'internal';
    case External = 'external';

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
