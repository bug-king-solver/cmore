<?php

namespace App\Models\Enums\Territory;

use App\Models\Traits\EnumToArray;
use Symfony\Component\Intl\Countries;

enum Continent: string
{
    use EnumToArray;

    case AFRICA = '1';
    case ASIA = '2';
    case EUROPE = '3';
    case NORTH_AMERICA = '4';
    case SOUTH_AMERICA = '5';
    case OCEANIA = '6';

    public function label(): string
    {
        return match ($this) {
            Continent::AFRICA => __('Africa'),
            Continent::ASIA => __('Asia'),
            Continent::EUROPE => __('Europe'),
            Continent::NORTH_AMERICA => __('North America'),
            Continent::SOUTH_AMERICA => __('Sout America'),
            Continent::OCEANIA => __('Oceania'),
        };
    }

    /**
     * Get all countries of a Continent
     */
    public function countries(): array
    {
        return array_filter(
            Country::cases(),
            fn (Country $enum) => substr($enum->value, 0, 1) === $this->value
        );
    }
}
