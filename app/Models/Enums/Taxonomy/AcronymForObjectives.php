<?php

namespace App\Models\Enums\Taxonomy;

use App\Models\Traits\EnumToArray;

enum AcronymForObjectives: string
{
    use EnumToArray;

    // Climate change mitigation
    case CLIMATE_CHANGE_MITIGATION = 'CCM';

    // Climate change adaptation
    case CLIMATE_CHANGE_ADAPTATION = 'CCA';

    // Sustainable use and protection of water and marine resources
    case WATER_MARINE_RESOURCES = 'SUPWMR';

    // Transition to a circular economy
    case CIRCULAR_ECONOMY = 'CECO';

    // Pollution prevention and control
    case POLLUTION = 'POL';

    // Protection and restoration of biodiversity and ecosystems
    case BIODIVERSITY_AND_ECOSYSTEMS = 'PRBE';

    /**
     * Get the label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::CLIMATE_CHANGE_MITIGATION => 'Mitigação das alterações climáticas',
            self::CLIMATE_CHANGE_ADAPTATION => 'Adaptação às alterações climáticas',
            self::WATER_MARINE_RESOURCES => 'Utilização sustentável e proteção dos recursos hídricos e marinhos',
            self::CIRCULAR_ECONOMY => 'Transição para uma economia circular',
            self::POLLUTION => 'Prevenção e controlo da poluição',
            self::BIODIVERSITY_AND_ECOSYSTEMS => 'Proteção e restauro da biodiversidade e dos ecossistemas',
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
