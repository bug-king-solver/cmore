<?php

namespace App\Models\Enums\Regulamentation;

use App\Models\Traits\EnumToArray;

enum AssetTypeEnum: string
{
    use EnumToArray;

    case LOANS_AND_PREPAYMENTS_TO_COMPANIES = '1';
    case DEBT_SECURITIES_INCLUDING_PARTICIPATION_UNITS = '2';
    case EQUITY_INSTRUMENTS = '3';
    case LOANS_TO_HOUSEHOLDS_SECURED_BY_RESIDENTIAL_PROPERTY = '4';
    case LOANS_TO_HOUSEHOLDS_FOR_THE_RENOVATION_OF_BUILDINGS = '5';
    case LOANS_TO_HOUSEHOLDS_FOR_THE_PURCHASE_OF_CARS = '6';
    case LOANS_TO_THE_PUBLIC_SECTOR_FOR_HOUSING_CONSTRUCTION = '7';
    case OTHER_LOANS_TO_THE_LOCAL_PUBLIC_SECTOR = '8';
    case RESIDENTIAL_AND_COMMERCIAL_REAL_ESTATE_OBTAINED_BY_ACQUIRING_OWNERSHIP = '9';
    case DERIVATIVES = '10';
    case INTERBANK_DEMAND_LOANS = '11';
    case CASH_AND_CASH_EQUIVALENTS = '12';
    case OTHER_ASSETS = '13';
    case CREDITS_OVER_SOVEREIGN_ENTITIES = '14';
    case CENTRAL_BANK_EXPOSURES = '15';
    case NEGOTIATION_PORTFOLIO = '16';
    case GARANTIAS_BANCÁRIAS = '17';
    case ATIVOS_SOB_GESTÃO = '18';

    public function label(): string
    {
        return match ($this) {
            self::LOANS_AND_PREPAYMENTS_TO_COMPANIES => __('Loans and prepayments to companies'),
            self::DEBT_SECURITIES_INCLUDING_PARTICIPATION_UNITS => __('Debt securities, including participation units'),
            self::EQUITY_INSTRUMENTS => __('Equity instruments'),
            self::LOANS_TO_HOUSEHOLDS_SECURED_BY_RESIDENTIAL_PROPERTY => __('Loans to households secured by residential property'),
            self::LOANS_TO_HOUSEHOLDS_FOR_THE_RENOVATION_OF_BUILDINGS => __('Loans to households for the renovation of buildings'),
            self::LOANS_TO_HOUSEHOLDS_FOR_THE_PURCHASE_OF_CARS => __('Loans to households for the purchase of cars'),
            self::LOANS_TO_THE_PUBLIC_SECTOR_FOR_HOUSING_CONSTRUCTION => __('Loans to the public sector for housing construction'),
            self::OTHER_LOANS_TO_THE_LOCAL_PUBLIC_SECTOR => __('Other Loans to the local public sector'),
            self::RESIDENTIAL_AND_COMMERCIAL_REAL_ESTATE_OBTAINED_BY_ACQUIRING_OWNERSHIP => __('Residential and commercial real estate obtained by acquiring ownership'),
            self::DERIVATIVES => __('Derivatives'),
            self::INTERBANK_DEMAND_LOANS => __('Interbank demand loans'),
            self::CASH_AND_CASH_EQUIVALENTS => __('Cash and cash equivalents'),
            self::OTHER_ASSETS => __('Other assets (e.g. goodwill, merchandise, etc.) '),
            self::CREDITS_OVER_SOVEREIGN_ENTITIES => __('Credits over sovereign entities'),
            self::CENTRAL_BANK_EXPOSURES => __('Central bank exposures'),
            self::NEGOTIATION_PORTFOLIO => __('Negotiation portfolio'),
            self::GARANTIAS_BANCÁRIAS => __('Garantias Bancárias'),
            self::ATIVOS_SOB_GESTÃO => __('Ativos sob gestão'),
        };
    }

    /**
     * Return an array of the enum values.
     */
    public static function toArray(): array
    {
        return self::casesArray();
    }

    /**
     * Mount an array for use as select in forms
     */
    public static function formList()
    {
        return [
            [
                'id' => self::LOANS_AND_PREPAYMENTS_TO_COMPANIES,
                'title' => self::LOANS_AND_PREPAYMENTS_TO_COMPANIES->label()
            ],
            [
                'id' => self::DEBT_SECURITIES_INCLUDING_PARTICIPATION_UNITS,
                'title' => self::DEBT_SECURITIES_INCLUDING_PARTICIPATION_UNITS->label()
            ],
            [
                'id' => self::EQUITY_INSTRUMENTS,
                'title' => self::EQUITY_INSTRUMENTS->label()
            ],
            [
                'id' => self::LOANS_TO_HOUSEHOLDS_SECURED_BY_RESIDENTIAL_PROPERTY,
                'title' => self::LOANS_TO_HOUSEHOLDS_SECURED_BY_RESIDENTIAL_PROPERTY->label()
            ],
            [
                'id' => self::LOANS_TO_HOUSEHOLDS_FOR_THE_RENOVATION_OF_BUILDINGS,
                'title' => self::LOANS_TO_HOUSEHOLDS_FOR_THE_RENOVATION_OF_BUILDINGS->label()
            ],
            [
                'id' => self::LOANS_TO_HOUSEHOLDS_FOR_THE_PURCHASE_OF_CARS,
                'title' => self::LOANS_TO_HOUSEHOLDS_FOR_THE_PURCHASE_OF_CARS->label()
            ],
            [
                'id' => self::LOANS_TO_THE_PUBLIC_SECTOR_FOR_HOUSING_CONSTRUCTION,
                'title' => self::LOANS_TO_THE_PUBLIC_SECTOR_FOR_HOUSING_CONSTRUCTION->label()
            ],
            [
                'id' => self::OTHER_LOANS_TO_THE_LOCAL_PUBLIC_SECTOR,
                'title' => self::OTHER_LOANS_TO_THE_LOCAL_PUBLIC_SECTOR->label()
            ],
            [
                'id' => self::RESIDENTIAL_AND_COMMERCIAL_REAL_ESTATE_OBTAINED_BY_ACQUIRING_OWNERSHIP,
                'title' => self::RESIDENTIAL_AND_COMMERCIAL_REAL_ESTATE_OBTAINED_BY_ACQUIRING_OWNERSHIP->label()
            ],
            [
                'id' => self::DERIVATIVES,
                'title' => self::DERIVATIVES->label()
            ],
            [
                'id' => self::INTERBANK_DEMAND_LOANS,
                'title' => self::INTERBANK_DEMAND_LOANS->label()
            ],
            [
                'id' => self::CASH_AND_CASH_EQUIVALENTS,
                'title' => self::CASH_AND_CASH_EQUIVALENTS->label()
            ],
            [
                'id' => self::OTHER_ASSETS,
                'title' => self::OTHER_ASSETS->label()
            ],
            [
                'id' => self::CREDITS_OVER_SOVEREIGN_ENTITIES,
                'title' => self::CREDITS_OVER_SOVEREIGN_ENTITIES->label()
            ],
            [
                'id' => self::CENTRAL_BANK_EXPOSURES,
                'title' => self::CENTRAL_BANK_EXPOSURES->label()
            ],
            [
                'id' => self::NEGOTIATION_PORTFOLIO,
                'title' => self::NEGOTIATION_PORTFOLIO->label()
            ],
            [
                'id' => self::GARANTIAS_BANCÁRIAS,
                'title' => self::GARANTIAS_BANCÁRIAS->label()
            ],
            [
                'id' => self::ATIVOS_SOB_GESTÃO,
                'title' => self::ATIVOS_SOB_GESTÃO->label()
            ],
        ];
    }

}
