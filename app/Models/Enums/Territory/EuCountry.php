<?php

namespace App\Models\Enums\Territory;

use App\Models\Traits\EnumToArray;

enum EuCountry: string
{
    use EnumToArray;

    case AUSTRIA = 'AUT';
    case BELGIUM = 'BEL';
    case BULGARIA = 'BGR';
    case CROATIA = 'HRV';
    case CYPRUS = 'CYP';
    case CZECHIA = 'CZE';
    case DENMARK = 'DNK';
    case ESTONIA = 'EST';
    case FINLAND = 'FIN';
    case FRANCE = 'FRA';
    case GERMANY = 'DEU';
    case GREECE = 'GRC';
    case HUNGARY = 'HUN';
    case IRELAND = 'IRL';
    case ITALY = 'ITA';
    case LATVIA = 'LVA';
    case LITHUANIA = 'LTU';
    case LUXEMBOURG = 'LUX';
    case MALTA = 'MLT';
    case NETHERLANDS_THE = 'NLD';
    case POLAND = 'POL';
    case PORTUGAL = 'PRT';
    case ROMANIA = 'ROU';
    case SLOVAKIA = 'SVK';
    case SLOVENIA = 'SVN';
    case SPAIN = 'ESP';
    case SWEDEN = 'SWE';
    case UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND_THE = 'GBR';

    public function label(): string
    {
        return match ($this) {

            self::AUSTRIA => __('AUSTRIA'),
            self::BELGIUM => __('BELGIUM'),
            self::BULGARIA => __('BULGARIA'),
            self::CROATIA => __('CROATIA'),
            self::CYPRUS => __('CYPRUS'),
            self::CZECHIA => __('CZECHIA'),
            self::DENMARK => __('DENMARK'),
            self::ESTONIA => __('ESTONIA'),
            self::FINLAND => __('FINLAND'),
            self::FRANCE => __('FRANCE'),
            self::GERMANY => __('GERMANY'),
            self::GREECE => __('GREECE'),
            self::HUNGARY => __('HUNGARY'),
            self::IRELAND=> __('IRELAND'),
            self::ITALY => __('ITALY'),
            self::LATVIA => __('LATVIA'),
            self::LITHUANIA => __('LITHUANIA'),
            self::LUXEMBOURG => __('LUXEMBOURG'),
            self::MALTA => __('MALTA'),
            self::NETHERLANDS_THE => __('NETHERLANDS_THE'),
            self::POLAND => __('POLAND'),
            self::PORTUGAL => __('PORTUGAL'),
            self::ROMANIA => __('ROMANIA'),
            self::SLOVAKIA => __('SLOVAKIA'),
            self::SLOVENIA => __('SLOVENIA'),
            self::SPAIN => __('SPAIN'),
            self::SWEDEN => __('SWEDEN'),
            self::UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND_THE => __('UNITED_KINGDOM_OF_GREAT_BRITAIN_AND_NORTHERN_IRELAND_THE'),
        };
    }

    /**
     * Returns an array representation of the ChartRange object.
     *
     * @return array An array representation of the ChartRange object.
     */
    public static function toArray(): array
    {
        return self::casesArray();
    }
}
