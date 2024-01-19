<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum NaturalHazard: string
{
    use EnumToArray;

    case RIVER_FLOOD = 'river_flood';
    case URBAN_FLOOD = 'urban_flood';
    case COASTAL_FLOOD = 'coastal_flood';
    case EARTHQUAKE = 'earthquake';
    case LANDSLIDE = 'landslide';
    case TSUNAMI = 'tsunami';
    case VOLCANO = 'volcano';
    case CYCLONE = 'cyclone';
    case WATER_SCARCITY = 'water_scarcity';
    case EXTREME_HEAT = 'extreme_heat';
    case WILDFIRE = 'wildfire';

    public function label(): string
    {
        return match ($this) {
            NaturalHazard::RIVER_FLOOD => __('River flood'),
            NaturalHazard::URBAN_FLOOD => __('Urban flood'),
            NaturalHazard::COASTAL_FLOOD => __('Coastal flood'),
            NaturalHazard::EARTHQUAKE => __('Earthquake'),
            NaturalHazard::LANDSLIDE => __('Landslide'),
            NaturalHazard::TSUNAMI => __('Tsunami'),
            NaturalHazard::VOLCANO => __('Volcano'),
            NaturalHazard::CYCLONE => __('Cyclone'),
            NaturalHazard::WATER_SCARCITY => __('Water scarcity'),
            NaturalHazard::EXTREME_HEAT => __('Extreme heat'),
            NaturalHazard::WILDFIRE => __('Wildfire'),
        };
    }
}
