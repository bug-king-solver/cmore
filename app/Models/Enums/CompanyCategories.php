<?php

namespace App\Models\Enums;

use App\Models\Traits\EnumToArray;

enum CompanyCategories: string
{
    use EnumToArray;

    case CreditInstitutions = 'credit-institutions';
    case InvestmentCompanies = 'investment-companies';
    case ManagementCompanies = 'management-companies';
    case InsuranceCompanies = 'insurance-companies';
    case NonFinancialCompanies = 'non-financial-companies';


    public function label(): string
    {
        return match ($this) {
            self::NonFinancialCompanies => __('Empresas não financeiras'),
            self::ManagementCompanies => __('Gestores de Ativos'),
            self::CreditInstitutions => __('Instituições de crédito'),
            self::InvestmentCompanies => __('Empresas de Investimento'),
            self::InsuranceCompanies => __('Empresas de seguros e resseguros'),
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
