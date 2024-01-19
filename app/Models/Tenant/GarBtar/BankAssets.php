<?php

namespace App\Models\Tenant\GarBtar;

use App\Http\Livewire\Traits\Taxonomy\BankAssetsTrait;
use App\Models\Tenant\Company;
use App\Models\Tenant\Filters\GarBtarAssets\AssetTypeFilter;
use App\Models\Tenant\Filters\GarBtarAssets\EntityTypeFilter;
use App\Models\Tenant\Filters\GarBtarAssets\NaceFilter;
use Illuminate\Database\Eloquent\Model;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;

class BankAssets extends Model
{
    use HasDataColumn;
    use HasFilters;
    use IsSearchable;
    use BankAssetsTrait;

    /**
     * @var string
     */
    final const VN = 'volum';

    /**
     * @var string
     */
    final const VN_PREFIX = 'volume_de_negócios-';

    /**
     * @var string
     */
    final const CAPEX = 'capex';

    /**
     * @var string
     */
    final const CAPEX_PREFIX = 'capex-';

    /**
     * @var string
     */
    final const OPEX = 'opex';

    /**
     * @var string
     */
    final const OPEX_PREFIX = 'opex-';

    /**
     * @var string
     */
    final const STOCK = 'stock';

    /**
     * @var string
     */
    final const BANK_BALANCE = 'bank_balance';

    /**
     * @var string
     */
    final const STOCK_FIELD = 'valor_total';

    /**
     * @var string
     */
    final const FLOW = 'flow';

    /**
     * @var string
     */
    final const FLOW_FIELD = 'variação_exercício';

    /**
     * @var string
     */
    final const GAR = 'gar';

    /**
     * @var string
     */
    final const BTAR = 'btar';

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD = 'empresas_não_sujeitas_a_nfrd';

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_DETAILED = self::COMPANIES_NOT_SUBJECT_TO_NFRD . '_' . self::DETAILED;

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_TAXONOMIC_INFORMATION = self::COMPANIES_NOT_SUBJECT_TO_NFRD . '_' . self::TAXONOMIC_INFORMATION;

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_EU = self::COMPANIES_NOT_SUBJECT_TO_NFRD . '_ue';

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU = self::COMPANIES_NOT_SUBJECT_TO_NFRD . '_ex_ue';

    /**
     * @var string
     */
    final const YES = 'S';

    /**
     * @var string
     */
    final const NO = 'N';

    /**
     * @var string
     */
    final const TOTAL_VALUE = 'valor_total';

    /**
     * @var string
     */
    final const CHANGE_IN_THE_FINANCIAL_YEAR = 'variação_exercício';

    /**
     * @var string
     */
    final const NIPC = 'nipc';

    /**
     * @var string
     */
    final const NAME_COMPANY = 'nome_da_empresa';

    /**
     * @var string
     */
    final const TYPE = 'tipo';

    /**
     * @var string
     */
    final const ENTITY_TYPE = 'entidade_empresarial';

    /**
     * @var string
     */
    final const NACE_CODE = 'código_nace';

    /**
     * @var string
     */
    final const SUBJECT_NFDR = 'sujeição_nfdr';

    /**
     * @var string
     */
    final const EUROPEAN_COMPANY = 'empresa_europeia';

    /**
     * @var string
     */
    final const SPECIFIC_PURPOSE = 'fim_específico';

    /**
     * @var string
     */
    final const CCM = 'ccm';

    /**
     * @var string
     */
    final const CCA = 'cca';

    /**
     * @var string
     */
    final const ELIGIBLE = 'elegibilidade';

    /**
     * @var string
     */
    final const ALIGNED = 'alinhamento';

    /**
     * @var string
     */
    final const NOT_ALIGNED = 'não_alinhados';

    /**
     * @var string
     */
    final const TRANSITIONAL = 'transição';

    /**
     * @var string
     */
    final const ADAPTING = 'adaptação';

    /**
     * @var string
     */
    final const TRANSITIONAL_ADAPTING = 'transição_adaptação';

    /**
     * @var string
     */
    final const ENABLING = 'capacitante';

    /**
     * @var string
     */
    final const WITH_DATA = 'com_dados';

    /**
     * @var string
     */
    final const NO_DATA = 'sem_dados';

    /**
     * @var string
     */
    final const CCM_ELIGIBLE = self::CCM . '_' . self::ELIGIBLE;

    /**
     * @var string
     */
    final const CCM_ALIGNED = self::CCM . '_' . self::ALIGNED;

    /**
     * @var string
     */
    final const CCM_TRANSITIONAL = self::CCM . '_' . self::TRANSITIONAL;

    /**
     * @var string
     */
    final const CCM_ENABLING = self::CCM . '_' . self::ENABLING;

    /**
     * @var string
     */
    final const CCA_ELIGIBLE = self::CCA . '_' . self::ELIGIBLE;

    /**
     * @var string
     */
    final const CCA_ALIGNED = self::CCA . '_' . self::ALIGNED;

    /**
     * @var string
     */
    final const CCA_ADAPTING = self::CCA . '_' . self::ADAPTING;

    /**
     * @var string
     */
    final const CCA_ENABLING = self::CCA . '_' . self::ENABLING;

    /**
     * @var string
     */
    final const VN_CCM_ELIGIBLE = self::VN_PREFIX . self::CCM_ELIGIBLE;

    /**
     * @var string
     */
    final const VN_CCM_ALIGNED = self::VN_PREFIX . self::CCM_ALIGNED;

    /**
     * @var string
     */
    final const VN_CCM_TRANSITIONAL = self::VN_PREFIX . self::CCM_TRANSITIONAL;

    /**
     * @var string
     */
    final const VN_CCM_ENABLING = self::VN_PREFIX . self::CCM_ENABLING;

    /**
     * @var string
     */
    final const VN_CCA_ELIGIBLE = self::VN_PREFIX . self::CCA_ELIGIBLE;

    /**
     * @var string
     */
    final const VN_CCA_ALIGNED = self::VN_PREFIX . self::CCA_ALIGNED;

    /**
     * @var string
     */
    final const VN_CCA_ADAPTING = self::VN_PREFIX . self::CCA_ADAPTING;

    /**
     * @var string
     */
    final const VN_CCA_ENABLING = self::VN_PREFIX . self::CCA_ENABLING;

    /**
     * @var string
     */
    final const CAPEX_CCM_ELIGIBLE = self::CAPEX_PREFIX . self::CCM_ELIGIBLE;

    /**
     * @var string
     */
    final const CAPEX_CCM_ALIGNED = self::CAPEX_PREFIX . self::CCM_ALIGNED;

    /**
     * @var string
     */
    final const CAPEX_CCM_TRANSITIONAL = self::CAPEX_PREFIX . self::CCM_TRANSITIONAL;

    /**
     * @var string
     */
    final const CAPEX_CCM_ENABLING = self::CAPEX_PREFIX . self::CCM_ENABLING;

    /**
     * @var string
     */
    final const CAPEX_CCA_ELIGIBLE = self::CAPEX_PREFIX . self::CCA_ELIGIBLE;

    /**
     * @var string
     */
    final const CAPEX_CCA_ALIGNED = self::CAPEX_PREFIX . self::CCA_ALIGNED;

    /**
     * @var string
     */
    final const CAPEX_CCA_ADAPTING = self::CAPEX_PREFIX . self::CCA_ADAPTING;

    /**
     * @var string
     */
    final const CAPEX_CCA_ENABLING = self::CAPEX_PREFIX . self::CCA_ENABLING;

    /**
     * @var string
     */
    final const OPEX_CCM_ELIGIBLE = self::OPEX_PREFIX . self::CCM_ELIGIBLE;

    /**
     * @var string
     */
    final const OPEX_CCM_ALIGNED = self::OPEX_PREFIX . self::CCM_ALIGNED;

    /**
     * @var string
     */
    final const OPEX_CCM_TRANSITIONAL = self::OPEX_PREFIX . self::CCM_TRANSITIONAL;

    /**
     * @var string
     */
    final const OPEX_CCM_ENABLING = self::OPEX_PREFIX . self::CCM_ENABLING;

    /**
     * @var string
     */
    final const OPEX_CCA_ELIGIBLE = self::OPEX_PREFIX . self::CCA_ELIGIBLE;

    /**
     * @var string
     */
    final const OPEX_CCA_ALIGNED = self::OPEX_PREFIX . self::CCA_ALIGNED;

    /**
     * @var string
     */
    final const OPEX_CCA_ADAPTING = self::OPEX_PREFIX . self::CCA_ADAPTING;

    /**
     * @var string
     */
    final const OPEX_CCA_ENABLING = self::OPEX_PREFIX . self::CCA_ENABLING;

    /**
     * @var string
     */
    final const SIMULATION = 'simulation';

    /**
     * @var string
     */
    final const FILL_MODE = 'fillMode';

    /**
     * @var string
     */
    final const REAL = 'real';

    /**
     * @var string
     */
    final const BANK = 'bank';

    /**
     * @var string
     */
    final const TAXONOMY = 'taxonomy';

    /**
     * @var string
     */
    final const TAXONOMY_ACTIVITY = 'taxonomyActivity';

    /**
     * @var string
     */
    final const ACTIVITY = 'activity';

    /**
     * @var string
     */
    final const PERCENT = 'per';

    /**
     * @var string
     */
    final const ABSOLUTE_VALUE = 'va';

    /**
     * @var string
     */
    final const DETAILED = 'detailed';

    /**
     * @var string
     */
    final const BANK_ASSETS = 'ativo_bancário';

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR = 'ativo_excluído_do_numerador_e_denominador';

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR_PERCENT = self::ASSETS_EXCLUDED_NUMERATOR_DENOMINATOR . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ASSETS_RELEVANT_FOR_RATIOS = 'ativo_relevante_para_rácios';

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_FROM_NUMERATOR_GAR = 'ativo_excluído_do_numerador_gar';

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_PERCENT = self::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR = 'ativo_excluído_do_numerador_gar_e_btar';

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_PERCENT = self::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR_DETAILED = self::ASSETS_EXCLUDED_FROM_NUMERATOR_GAR_BTAR . '_' . self::DETAILED;

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR = 'ativo_excluído_do_numerador_gar_empresas';

    /**
     * @var string
     */
    final const ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR_PERCENT = self::ASSETS_EXCLUDED_NUMERATOR_COMPANIES_GAR . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ASSETS_COVERED = 'ativo_abrangido';

    /**
     * @var string
     */
    final const ASSETS_COVERED_GAR = self::ASSETS_COVERED . '_' . self::GAR;

    /**
     * @var string
     */
    final const ASSETS_COVERED_GAR_PERCENT = self::ASSETS_COVERED_GAR . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ASSETS_COVERED_BTAR = self::ASSETS_COVERED . '_' . self::BTAR;

    /**
     * @var string
     */
    final const ASSETS_COVERED_BTAR_PERCENT = self::ASSETS_COVERED_BTAR . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const REAL_STATE = 'bens_imóveis';

    /**
     * @var string
     */
    final const PUBLIC_SECTOR = 'setor_público';

    /**
     * @var string
     */
    final const PUBLIC_SECTOR_LOCAL = 'setor_público_local';

    /**
     * @var string
     */
    final const PUBLIC_SECTOR_LOCAL_AND_REAL_STATE_DETAILED = 'setor_público_local_e_bens_imóveis_' . self::DETAILED;

    /**
     * @var string
     */
    final const FAMILIES = 'famílias';

    /**
     * @var string
     */
    final const FAMILIES_DETAILED = self::FAMILIES . '_' . self::DETAILED;

    /**
     * @var string
     */
    final const COMPANIES = 'empresas';

    /**
     * @var string
     */
    final const COMPANIES_DETAILED = self::COMPANIES . '_' . self::DETAILED;

    /**
     * @var string
     */
    final const SPECIALIZED_CREDIT = 'credito_especializado';

    /**
     * @var string
     */
    final const NON_FINANCIAL_COMPANIES = 'empresas_não_financeiras';

    /**
     * @var string
     */
    final const INSURANCE_COMPANIES = 'empresas_de_seguros';

    /**
     * @var string
     */
    final const MANAGEMENT_COMPANIES = 'sociedades_gestoras';

    /**
     * @var string
     */
    final const INVESTMENT_COMPANIES = 'empresas_de_investimento';

    /**
     * @var string
     */
    final const CREDIT_INSTITUTIONS = 'instituições_de_crédito';

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE = 'segmentação_tipos_de_activos';

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_ABSOLUTE_VALUE = self::SEGMENTATION_ASSETS_TYPE . '_' . self::ABSOLUTE_VALUE;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_PERCENT = self::SEGMENTATION_ASSETS_TYPE . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_EU_DETAILED = self::COMPANIES_NOT_SUBJECT_TO_NFRD_EU . '_' . self::DETAILED;

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_DETAILED = self::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU . '_' . self::DETAILED;

    /**
     * @var string
     */
    final const NON_FINANCIAL_COMPANIES_SUBJECT_NFRD = self::NON_FINANCIAL_COMPANIES . '_sujeitas_nfrd';

    /**
     * @var string
     */
    final const COMPANIES_GAR = 'empresas_para_efeitos_de_' . self::GAR;

    /**
     * @var string
     */
    final const ELIGIBLE_ASSETS = 'ativos_elegíveis';

    /**
     * @var string
     */
    final const ELIGIBLE_ASSETS_PERCENT = self::ELIGIBLE_ASSETS . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ELIGIBLE_AND_ALIGNED_ASSETS = 'ativos_elegíveis_e_alinhados';

    /**
     * @var string
     */
    final const ELIGIBLE_AND_ALIGNED_ASSETS_PERCENT = self::ELIGIBLE_AND_ALIGNED_ASSETS . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ELIGIBLE_AND_NOT_ALIGNED_ASSETS = 'ativos_elegíveis_e_não_alinhados';

    /**
     * @var string
     */
    final const ELIGIBLE_AND_NOT_ALIGNED_ASSETS_PERCENT = self::ELIGIBLE_AND_NOT_ALIGNED_ASSETS . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ASSETS_WITHOUT_DATA = 'ativos_sem_dados';

    /**
     * @var string
     */
    final const ASSETS_WITHOUT_DATA_PERCENT = self::ASSETS_WITHOUT_DATA . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const ASSETS_WITH_DATA = 'ativos_com_dados';

    /**
     * @var string
     */
    final const ASSETS_WITH_DATA_PERCENT = self::ASSETS_WITH_DATA . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const NOT_ELIGIBLE_ASSETS = 'ativos_não_elegíveis';

    /**
     * @var string
     */
    final const NOT_ELIGIBLE_ASSETS_PERCENT = self::NOT_ELIGIBLE_ASSETS . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const DENOMINATOR = 'denominador';

    /**
     * @var string
     */
    final const DENOMINATOR_PERCENT = self::DENOMINATOR . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const COVERAGE = 'abrangência';

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY = 'segmentação_actividade_económica';

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_ABSOLUTE_VALUE = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::ABSOLUTE_VALUE;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_PERCENT = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::PERCENT;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_COVERED = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_abrangidos';

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_WITHOUT_DATA = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::NO_DATA;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_NOT_COVERED = self::SEGMENTATION_ECONOMIC_ACTIVITY . 'não_abrangidos';

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_ELIGIBLE = self::SEGMENTATION_ASSETS_TYPE . '_' . self::ELIGIBLE;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_ALIGNED = self::SEGMENTATION_ASSETS_TYPE . '_' . self::ALIGNED;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_TRANSITIONAL = self::SEGMENTATION_ASSETS_TYPE . '_' . self::TRANSITIONAL;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_ADAPTING = self::SEGMENTATION_ASSETS_TYPE . '_' . self::ADAPTING;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_ENABLING = self::SEGMENTATION_ASSETS_TYPE . '_' . self::ENABLING;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_NOT_ALIGNED = self::SEGMENTATION_ASSETS_TYPE . '_' . self::NOT_ALIGNED;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_COVERED = self::SEGMENTATION_ASSETS_TYPE . '_abrangidos';

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_WITH_DATA = self::SEGMENTATION_ASSETS_TYPE . '_' . self::WITH_DATA;

    /**
     * @var string
     */
    final const SEGMENTATION_ASSETS_TYPE_WITHOUT_DATA = self::SEGMENTATION_ASSETS_TYPE . '_' . self::NO_DATA;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_ELIGIBLE = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::ELIGIBLE;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_ALIGNED = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::ALIGNED;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_TRANSITIONAL = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::TRANSITIONAL;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_ADAPTING = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::ADAPTING;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_ENABLING = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::ENABLING;

    /**
     * @var string
     */
    final const SEGMENTATION_ECONOMIC_ACTIVITY_NOT_ALIGNED = self::SEGMENTATION_ECONOMIC_ACTIVITY . '_' . self::NOT_ALIGNED;

    /**
     * @var string
     */
    final const CCA_LONG = 'adaptação_às_alterações_climáticas';

    /**
     * @var string
     */
    final const CCA_ALIGNED_LONG = self::CCA_LONG . '_' . self::ALIGNED;

    /**
     * @var string
     */
    final const CCM_LONG = 'mitigação_das_alterações_climáticas';

    /**
     * @var string
     */
    final const CCM_ALIGNED_LONG = self::CCM_LONG . '_' . self::ALIGNED;

    /**
     * @var string
     */
    final const ACTIVITIES_TRANSITIONAL_ADAPTING = 'atividades_de_' . self::TRANSITIONAL_ADAPTING;

    /**
     * @var string
     */
    final const ACTIVITIES_CAPACITING = 'atividades_de_capacitação';

    /**
     * @var string
     */
    final const TAXONOMIC_INFORMATION = 'informação_taxonómica';

    /**
     * @var string
     */
    final const TAXONOMIC_INFORMATION_DETAILED = self::TAXONOMIC_INFORMATION . '_' . self::DETAILED;

    /**
     * @var string
     */
    final const NON_FINANCIAL_COMPANIES_TAXONOMIC_INFORMATION = self::NON_FINANCIAL_COMPANIES . '_' . self::TAXONOMIC_INFORMATION;

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_EU_TAXONOMIC_INFORMATION = self::COMPANIES_NOT_SUBJECT_TO_NFRD_EU . '_' . self::TAXONOMIC_INFORMATION;

    /**
     * @var string
     */
    final const COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU_TAXONOMIC_INFORMATION = self::COMPANIES_NOT_SUBJECT_TO_NFRD_EX_EU . '_' . self::TAXONOMIC_INFORMATION;

    /**
     * @var string
     */
    final const SPECIALIZED_LOANS = 'empréstimos_especializados';


    /**  * @return array<string, mixed> */
    protected $fillable = [
        'company_id','data', 'original'
    ];

    /**  * @return array<string, mixed> */
    protected array $filters = [
        EntityTypeFilter::class,
        AssetTypeFilter::class,
        NaceFilter::class,
    ];

    /**
     * @return array<mixed>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'company_id',
            'original',
            'data',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * Get a list of the bank assets to use with paginate
     *
     * @return Collection
     */
    public static function list(): \Illuminate\Database\Eloquent\Builder
    {
        return self::with('company');
    }

    /**
     * Belongs to company
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getAssetsByType(array $type)
    {
        return self::whereIn('data->' . self::TYPE, $type);
    }

    public function getAssetsByTypeAndEntity(array $type, array $entity)
    {
        return self::whereIn('data->' . self::TYPE, $type)->whereIn('data->' . self::ENTITY_TYPE, $entity);
    }

    public function getDataForRegulatoryTables($query, $showOnlyTotalValues = false, $kpi = BankAssets::VN_PREFIX): array
    {
        $assets = $query->get();
        $baseArray = [
            self::ELIGIBLE => 0,
            self::ALIGNED => 0,
            self::SPECIALIZED_CREDIT => 0,
            self::TRANSITIONAL_ADAPTING => 0,
            self::ENABLING => 0,
        ];
        $result = [
            self::STOCK => 0,
            self::FLOW => 0,
            self::CCA => [
                self::STOCK => $baseArray,
                self::FLOW => $baseArray,
            ],
            self::CCM => [
                self::STOCK => $baseArray,
                self::FLOW => $baseArray,
            ],
        ];
        foreach ($assets as $item) {
            $result[self::STOCK] += $item[self::TOTAL_VALUE];
            $result[self::FLOW] += $item[self::CHANGE_IN_THE_FINANCIAL_YEAR];

            $result[self::CCA][self::STOCK][self::ELIGIBLE] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ELIGIBLE] * $item[self::TOTAL_VALUE]);
            $result[self::CCA][self::STOCK][self::ALIGNED] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ALIGNED] * $item[self::TOTAL_VALUE]);
            $result[self::CCA][self::STOCK][self::TRANSITIONAL_ADAPTING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ADAPTING] * $item[self::TOTAL_VALUE]);
            $result[self::CCA][self::STOCK][self::ENABLING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ENABLING] * $item[self::TOTAL_VALUE]);

            $result[self::CCM][self::STOCK][self::ELIGIBLE] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_ELIGIBLE] * $item[self::TOTAL_VALUE]);
            $result[self::CCM][self::STOCK][self::ALIGNED] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_ALIGNED] * $item[self::TOTAL_VALUE]);
            $result[self::CCM][self::STOCK][self::TRANSITIONAL_ADAPTING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_TRANSITIONAL] * $item[self::TOTAL_VALUE]);
            $result[self::CCM][self::STOCK][self::ENABLING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_ENABLING] * $item[self::TOTAL_VALUE]);

            $result[self::CCA][self::FLOW][self::ELIGIBLE] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ELIGIBLE] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
            $result[self::CCA][self::FLOW][self::ALIGNED] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ALIGNED] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
            $result[self::CCA][self::FLOW][self::TRANSITIONAL_ADAPTING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ADAPTING] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
            $result[self::CCA][self::FLOW][self::ENABLING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ENABLING] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);

            $result[self::CCM][self::FLOW][self::ELIGIBLE] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_ELIGIBLE] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
            $result[self::CCM][self::FLOW][self::ALIGNED] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_ALIGNED] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
            $result[self::CCM][self::FLOW][self::TRANSITIONAL_ADAPTING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_TRANSITIONAL] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
            $result[self::CCM][self::FLOW][self::ENABLING] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCM_ENABLING] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);

            if ($item[self::SPECIFIC_PURPOSE] === self::YES) {
                $result[self::CCA][self::STOCK][self::SPECIALIZED_CREDIT] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ALIGNED] * $item[self::TOTAL_VALUE]);
                $result[self::CCM][self::STOCK][self::SPECIALIZED_CREDIT] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ALIGNED] * $item[self::TOTAL_VALUE]);

                $result[self::CCA][self::FLOW][self::SPECIALIZED_CREDIT] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ALIGNED] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
                $result[self::CCM][self::FLOW][self::SPECIALIZED_CREDIT] += $showOnlyTotalValues ? 0 : ($item[$kpi . self::CCA_ALIGNED] * $item[self::CHANGE_IN_THE_FINANCIAL_YEAR]);
            }
        }
        return $result;
    }

    /**
     * @return void
     */
    public static function importFile($pathFile): void
    {
        $data = [];
        $i = -1;
        $csvFile = fopen($pathFile, 'r');
        $indicatorsIds = [];
        $mapValues = [
            self::TOTAL_VALUE,
            self::CHANGE_IN_THE_FINANCIAL_YEAR,
            self::NIPC,
            self::NAME_COMPANY,
            self::TYPE,
            self::ENTITY_TYPE,
            self::NACE_CODE,
            self::SUBJECT_NFDR,
            self::EUROPEAN_COMPANY,
            self::SPECIFIC_PURPOSE,
            self::VN_CCM_ELIGIBLE,
            self::VN_CCM_ALIGNED,
            self::VN_CCM_TRANSITIONAL,
            self::VN_CCM_ENABLING,
            self::VN_CCA_ELIGIBLE,
            self::VN_CCA_ALIGNED,
            self::VN_CCA_ADAPTING,
            self::VN_CCA_ENABLING,
            self::CAPEX_CCM_ELIGIBLE,
            self::CAPEX_CCM_ALIGNED,
            self::CAPEX_CCM_TRANSITIONAL,
            self::CAPEX_CCM_ENABLING,
            self::CAPEX_CCA_ELIGIBLE,
            self::CAPEX_CCA_ALIGNED,
            self::CAPEX_CCA_ADAPTING,
            self::CAPEX_CCA_ENABLING,
            self::OPEX_CCM_ELIGIBLE,
            self::OPEX_CCM_ALIGNED,
            self::OPEX_CCM_TRANSITIONAL,
            self::OPEX_CCM_ENABLING,
            self::OPEX_CCA_ELIGIBLE,
            self::OPEX_CCA_ALIGNED,
            self::OPEX_CCA_ADAPTING,
            self::OPEX_CCA_ENABLING,
        ];
        while (($row = fgetcsv($csvFile, null, ',')) !== false) {
            ++$i;
            if ($i === 0) {
                foreach ($row as $key => $id) {
                    $indicatorsIds[$key] = $id ?: null;
                }

                continue;
            }

            $newItem = [];
            foreach ($row as $key => $value) {
                $value = trim((string) $value);
                if (is_numeric($value)) {
                    $value = (float) $value;
                }

                $newItem[$mapValues[$key]] = $value;
            }

            $data[] = $newItem;
        }

        fclose($csvFile);
        BankAssets::truncate();
        foreach ($data as $item) {
            $company = Company::updateOrCreate(['vat_number' => $item[self::NIPC]], [
                'name' => $item[self::NAME_COMPANY] ?? 'No name'
            ]);
            $item['company_id'] = $company->id;
            (new BankAssets())->forceFill($item)->save();
        }

        (new BankAssets())->createJsonFile();
    }
}
