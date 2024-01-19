<?php

namespace App\Models\Enums\Taxonomy;

use App\Models\Traits\EnumToArray;

enum ShortNameForObjectives: string
{
    use EnumToArray;

    case MITIGACAO_DAS_ALTERACOES_CLIMATICAS = 'Mitigação das alterações climáticas';
    case ADAPTACAO_AS_ALTERACOES_CLIMATICAS = 'Adaptação às alterações climáticas';
    case RECURSOS_HIBRIDOS_E_MARINHOS = 'Recursos hídricos e marinhos';
    case ECONOMIA_CIRCULAR = 'Economia circular';
    case POLUICAO = 'Poluição';
    case BIODIVERSIDADE_E_ECOSSISTEMA = 'Biodiversidade e ecossistemas';

    /**
     * Get the label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::MITIGACAO_DAS_ALTERACOES_CLIMATICAS => 'Mitigação das alterações climáticas',
            self::ADAPTACAO_AS_ALTERACOES_CLIMATICAS => 'Adaptação às alterações climáticas',
            self::RECURSOS_HIBRIDOS_E_MARINHOS => 'Utilização sustentável e proteção dos recursos hídricos e marinhos',
            self::ECONOMIA_CIRCULAR => 'Transição para uma economia circular',
            self::POLUICAO => 'Prevenção e controlo da poluição',
            self::BIODIVERSIDADE_E_ECOSSISTEMA => 'Proteção e restauro da biodiversidade e dos ecossistemas',
        };
    }

    /**
     * Get the label for the enum value.
     */
    public function acronymo(): string
    {
        return match ($this) {
            self::MITIGACAO_DAS_ALTERACOES_CLIMATICAS => 'ccm',
            self::ADAPTACAO_AS_ALTERACOES_CLIMATICAS => 'cca',
            self::RECURSOS_HIBRIDOS_E_MARINHOS => 'supwmr',
            self::ECONOMIA_CIRCULAR => 'ceco',
            self::POLUICAO => 'pol',
            self::BIODIVERSIDADE_E_ECOSSISTEMA => 'prbe',
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
